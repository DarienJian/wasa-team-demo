<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        return Post::create($validated);
    }

    public function show($id)
    {
        return Post::find($id);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        $post->update($validated);

        return $post;
    }

    public function destroy(Post $post)
    {
        return $post->delete();
    }
}
