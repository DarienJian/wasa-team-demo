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
        $data = array_merge($validated, ['user_id' => auth()->user()->id]);
        return Post::create($data);
    }

    public function show($id)
    {
        return Post::find($id);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $validated = $request->validated();
        return Post::find($id)->update($validated);
    }

    public function destroy($id)
    {
        return Post::find($id)->delete();
    }
}
