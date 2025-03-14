<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait UserApiTokens
{

    public function createToken($request, $name, array $abilities = ['*'])
    {
        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken = Str::random(40)),
            'abilities' => $abilities,
            'os' => $request->os ?? 'desktop',
        ]);

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

    public function updateOrCreateToken($request, $name, array $abilities = ['*'])
    {
        $token = $this->tokens()->where('name', $name)->first();

        if ($token) {

            $token->update([
                'token' => hash('sha256', $plainTextToken = Str::random(40)),
                'abilities' => $abilities,
                'firebase_token' => $request->firebase_token ?? $token->firebase_token,
            ]);

        } else {

            $token = $this->tokens()->create([
                'name' => $name,
                'token' => hash('sha256', $plainTextToken = Str::random(40)),
                'abilities' => $abilities,
                'os' => $request->os ?? 'ios',
                'firebase_token' => $request->firebase_token ?? null,
            ]);
        }

        return new NewAccessToken($token, $token->getKey() . '|' . $plainTextToken);
    }

}
