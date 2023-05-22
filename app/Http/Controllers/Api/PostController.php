<?php

namespace App\Http\Controllers\Api;

// import Model "Post" (22052023)
use App\Models\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// import Resource "PostResource" (22052023)
use App\Http\Resources\PostResource;

// import Facade "Storage"
use Illuminate\Support\Facades\Storage;

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

    // tambahkan fungsi show (22052023)
    public function show($id) {
        // find post by ID (22052023)
        $post = Post::find($id);

        // return single post as a resource
        return new PostResource(true, 'Detail Data Post', $post);
    }

    // tambahkan fungsi update (22052023)
    public function update(Request $request, $id) {
        // define validation rules (22052023)
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        // check if validation fails (22052023)
        if ($validator->fails()) {
            return response()->json($validator->errors(), 442);
        }

        // find post by ID (22052023)
        $post = Post::find($id);

        // check if image is not empty (22052023)
        if ($request->hasFile('image')) {

            // upload image (22052023)
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // update post with new image (22052023)
            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content,
            ]);
 
        } else {
            // update post without image (22052023)
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }

        // return response (22052023)
        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    // tambah fungsi destroy (22052023)
    public function destroy($id) {
        // find post by ID (22052023)
        $post = Post::find($id);

        // delete image (22052023)
        Storage::delete('public/posts/'.basename($post->image));

        // delete post (22052023)
        $post->delete();

        // return response (22052023) mengembalikan response JSON menggunakan API Resource.
        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }

}
