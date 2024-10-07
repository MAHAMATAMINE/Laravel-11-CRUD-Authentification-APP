<?php

namespace App\Http\Controllers;

use App\Models\Post; // Correctly import the Post model (uppercase "P")
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware(){
        return[
            new Middleware('auth::sanctum',except:['index,','show'])
        ];
    }

    public function index()
    {
        // Return all posts
        return Post::all(); // Corrected to use Post::all() (uppercase "P")
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $fields=$request->validate([
        'title'=>'required|max:255',
        'body'=>'required'

     ]);
     $post=$request->user()->post()->create($fields);
     return  $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return  $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify',$post);
        $fields=$request->validate([
            'title'=>'required|max:255',
            'body'=>'required'

         ]);
         $post->update($fields);
         return  $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete the specified post
        Gate::authorize('modify',$post);
        $post->delete();

        // Return a 204 No Content response to indicate successful deletion
        return ['message'=>'its deleted'];
    }
}
