<?php

namespace App\Http\Controllers\Api;

// import Model "Post" (22052023)
use App\Models\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// import Resource "PostResource" (22052023)
use App\Http\Resources\PostResource;

// import Facade "Validator"
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    // membuat function index (22052023)
    public function index() {

        // get all posts (22052023)
        $posts = Post::latest()->paginate(5);

        // return collection of posts as a resource (22052023)
        return new PostResource(true, 'List Data Posts', $posts); // memanggil API Resource
    }

    public function store(Request $request) {
        // define validation rules (22052023)
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        // check if validation fails (22052023)
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload image (22052023)
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        // create posts (22052023)
        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // return response (22052023)
        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

}
