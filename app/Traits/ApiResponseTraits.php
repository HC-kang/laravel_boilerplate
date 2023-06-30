<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Exception;

trait ApiResponseTraits
{
    public function successResponse($data = [], $code = Response::HTTP_OK)
    {
        if ($data instanceof Exception) {
            return $this->errorResponse($data);
        }
        return $this->successTrueResponse($data, $code);
    }

    private function successTrueResponse($data = [], $code = Response::HTTP_OK)
    {
        $result = [
            'success' => true,
        ];

        if (! empty($data)) {
            $result = array_merge($result, $data);
        }

        return response()->json($result, $code);
    }

    private function successFalseResponse($message, $code = Response::HTTP_OK)
    {
        $result = [
           'success' => false,
           'message' => $message,
        ];

        return response()->json($result, $code);
    }

    public function errorResponse(Exception $error, $code = Response::HTTP_BAD_REQUEST)
    {
        $result = [
            'success' => false,
        ];

        if ((int) $error->getCode() > 90000) {
            $message = $error->getMessage();
            $result = array_merge($result, ['message' => $message]);
        } else {
            Log::error(sprintf('[%s:%d] %s', $error->getFile(), $error->getLine(), $error->getMessage()));
        }

        return response()->json($result, $code);
    }
}