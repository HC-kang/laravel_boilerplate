<?php

namespace App\Traits;

use Carbon\CarbonPeriod;
use DOMDocument;
use Illuminate\Support\Carbon;
use Exception;

trait Utils
{
    public function compose(array $fns): callable
    {
        return function ($x) use ($fns) {
            $result = $x;
            foreach ($fns as $fn) {
                $result = $fn($result);
            }
            return $result;
        };
    }

    /**
     * file_get_contents_utf8()의 wrapper.
     * 필요시 쿠키를 전달할 수 있도록 기능 추가.
     */
    public function file_get_content_ex(string $url, array $cookies = null): string
    {
        if (!empty($cookies)) {
            $cookieString = http_build_query($cookies, '', '; ');
        } else {
            $cookieString = '';
        }

        $options = [
            'http' => [
                'method' => 'GET',
                'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36',
                'header' => "Accept-language: ko\r\n" .
                    (!empty($cookieString) ? "Cookie: " . $cookieString . "\r\n" : ''),
            ],
        ];
        $context = stream_context_create($options);
        return $this->file_get_contents_utf8($url, false, $context);
    }

    /**
     * file_get_contents()의 wrapper.
     * file_get_contents()는 기본적으로 EUC-KR로 인코딩된 문자열을 반환하는데,
     * 이를 UTF-8로 변환하여 반환.
     */
    public function file_get_contents_utf8(string $url, bool $use_include_path = false, $context = null)
    {
        $content = file_get_contents($url, $use_include_path, $context);
        return mb_convert_encoding(
            $content,
            'UTF-8',
            mb_detect_encoding($content, 'auto')
        );
    }

    public function parseDom($html_string): DOMDocument
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html_string);
        return $dom;
    }

    /**
     * base_url을 입력받아, 'query parameter를 입력받아 url을 생성하는 함수'를 반환하는 커링 함수.
     */
    public function _urlGenerator(string $base_url): callable
    {
        return function (array $queryParameters) use ($base_url) {
            if (empty($queryParameters)) {
                return $base_url;
            }
            $query = http_build_query($queryParameters);
            return $base_url . '?' . $query;
        };
    }

    /**
     * 입력받은 날짜가 '0000-00-00'인 경우 null로 변환.
     * 쓸모없는 데이터 제거를 위해 사용.
     */
    public function sanitizeDate(string $date): ?string
    {
        return $this->isEmptyDate($date) ? null : $date;
    }

    public function isEmptyDate(?string $dateString): bool
    {
        return $dateString === '0000-00-00';
    }

    /**
     * 입력받은 날짜가 유효한 날짜인지 확인.
     * 스크래핑 대상 사이트의 입력 실수 등을 걸러내기 위해 사용.
     * '0000-00-00'은 대상 사이트에서 '의도적으로 기입한'값이므로 유효한 날짜로 간주.
     */
    public function isValidDate(?string $dateString): bool
    {
        if (empty($dateString)) {
            return false;
        }
        if ($this->isEmptyDate($dateString)) {
            return true;
        }

        $dateArray = explode('-', substr($dateString, 0, 10));
        if (count($dateArray) !== 3) {
            return false;
        }
        return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
    }

    public function createPeriodArray(string $endDate): array
    {
        return array_map(function ($date) {
            return $date->format('Y-m-d');
        }, CarbonPeriod::create(
            Carbon::now()->startOfDay()->format('Y-m-d'),
            '1 days',
            Carbon::parse($endDate)->format('Y-m-d')
        )->toArray());
    }

    public function getDateDiffFromToday(string $dateString): int
    {
        $today = Carbon::now()->startOfDay();
        $targetDate = Carbon::createFromDate($dateString)->startOfDay();
        $interval = $today
            ->diff($targetDate);
        return $interval->days;
    }

    /**
     * 작은 따옴표로 감싸진 문자열을 추출.
     */
    public function extractSingleQuoted(string $str): string
    {
        $pattern = "/'([^']*)'/";
        if (preg_match($pattern, $str, $matches)) {
            $quotedText = $matches[1];
            return $quotedText;
        }
        return '';
    }

    /**
     * 쿼리 파라미터를 추출.
     */
    public function extractQueryParameters(string $str): string
    {
        $pattern = "/\?([^\/]*)/";
        if (preg_match($pattern, $str, $matches)) {
            $queryParameters = $matches[1];
            return $queryParameters;
        }
        return '';
    }

    /**
     * 쿼리 파라미터를 배열로 변환.
     */
    public function parseQueryParameters(string $str): array
    {
        $queryParameters = explode('&', $str);
        $result = [];
        foreach ($queryParameters as $queryParameter) {
            $queryParameter = explode('=', $queryParameter);
            $result[$queryParameter[0]] = $queryParameter[1];
        }
        return $result;
    }

    /**
     * 헤더 텍스트 어레이에서 쿠키를 추출.
     */
    private function extractCookiesFromHeader(array $http_response_header): array
    {
        if (empty($http_response_header)) {
            // $this->info_('No HTTP Response Header');
            return [];
        }
        $cookies = [];
        foreach ($http_response_header as $header) {
            if (preg_match('/^Set-Cookie:\s*([^;]+)/', $header, $matches)) {
                parse_str($matches[1], $tmp);
                $cookies += $tmp;
            }
        }
        return $cookies;
    }

    public function jitterSleep(int $milliseconds, float $amplitude = 0.3): void
    {
        if ($amplitude < 0 || $amplitude > 1) {
            throw new Exception("Amplitude must be between 0 and 1");
        }
        $minJitter = 1 - $amplitude; // Minimum jitter factor
        $maxJitter = 1 + $amplitude; // Maximum jitter factor

        $jitterRange = $milliseconds * ($maxJitter - $minJitter);
        $jitter = $milliseconds + (mt_rand() / mt_getrandmax()) * $jitterRange - ($jitterRange / 2);
        usleep($jitter * 1000);
    }

    public function utf8Decode($text)
    {
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }

    public function removeEmojis($text)
    {
        $emoji_pattern = '/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}]/u';
        return preg_replace($emoji_pattern, '', $text);
    }

    public function removeCommasFromNumber($text): int
    {
        return (int) str_replace(',', '', $text);
    }

    public function isKorean($text): bool
    {
        return preg_match('/^[가-힣0-9 ]+$/', $text);
    }

    public function isEnglish($text): bool
    {
        return preg_match('/^[a-zA-Z0-9 ]+$/', $text);
    }
}
