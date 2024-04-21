<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getProfileData()
    {
        $user_id = Auth::guard('api')->id();

        $user = User::with([
            'posts' => fn($q) => $q->orderByDesc('created_at')
        ])->findOrFail($user_id);

        return (new UserResource($user))->additional([
            'posts' => PostResource::collection($user->getRelation('posts'))
        ]);
    }
}
