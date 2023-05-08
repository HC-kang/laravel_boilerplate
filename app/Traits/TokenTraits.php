<?php

namespace App\Traits;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

trait TokenTraits
{
    protected function respondWithToken(?User $user): JsonResponse
    {
        $role = 'normal';
        $token = $user->createToken('my-project', [$role]);
        return response()->json([
            'success'      => true,
            // 'user'         => $user,
            'access_token' => $token->accessToken,
            'token_type'   => 'bearer',
            'expires_in'   => Carbon::parse($token->token->expires_at)->getTimestamp(),
        ]);
    }

    protected function respondWithTokenToTSAdmin(User $user): JsonResponse
    {
        $token = $user->createToken('my-project', ['admin']);
        return response()->json([
            'success'      => true,
            'access_token' => $token->accessToken,
            'token_type'   => 'bearer',
            'expires_in'   => Carbon::parse($token->token->expires_at)->getTimestamp(),
        ]);
    }

    public function generateUserToken(string $name, string $userStringId): string
    {
        return md5(vsprintf("%s:%s:%s", [
            $name,
            $userStringId,
            microtime()
        ]));
    }
}
