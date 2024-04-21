<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Admin;
use App\Models\Post;
use App\Notifications\PostCreated;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $user = Auth::guard('api')->user();

        $posts = Post::with([
            'user' => fn($q) => $q->select('id', 'phone', 'username', 'email')
        ])->whereNot('user_id', $user->id)->orderByDesc('created_at')->paginate(10);

        return PostResource::collection($posts);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::guard('api')->id();

        $post = Post::create($data);

        foreach(Admin::all() as $admin)
            $admin->notify(new PostCreated($admin, $post));

        return new PostResource($post);
    }


}
