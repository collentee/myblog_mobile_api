<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Http\Resources\PostResource;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $post = Post::latest()->get();
        return PostResource::collection($post)->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title'=> 'required|min:3',
            'body'=> 'required|min:6',
            'image' => 'string|min:6',
            'user_id'=> 'integer',
        ]);

        $image = $request->image;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(6).'.'.'png';
        \File::put(storage_path(). '/app/public/images/posts/' . $imageName, base64_decode($image));
        
        

        $blog = Post::create([
            'title' => $request['title'],
            'body' => $request['body'],
            'image' => $imageName, //$request['image'] ,
            'user_id' => $request['user_id'],
        ]);

        if(!$blog){
            dd($blog);
            return response ([
                'message' => 'Blog not saved',
            ], 500);
        }

        return response([
            'message' => 'Post created',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post= Post::findOrFail($id);
        if($post){
            return new PostResource(Post::findOrFail($id));
        }
        
        
    }

    public function myPosts($id)
    {
        //
        $user= User::findOrFail($id);
        
        if($user){
            $myposts = Post::where('user_id', $id)->get();
            //dd($myposts);
            return PostResource::collection($myposts)->all();
        }
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request, $word)
    {
        $res = Post::where('title', 'LIKE', '%'.$word.'%' )->get();
        return PostResource::collection($res)->all();
    }
}
