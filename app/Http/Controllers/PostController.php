<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Show form to create a new post
     */
    public function create()
    {
        return view('simple');
    }
    
    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        // Create the post
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        
        // Redirect or return response
        return redirect('/posts')->with('success', 'Post created successfully!');
        // OR for JSON response:
        // return response()->json([
        //     'message' => 'Post created successfully',
        //     'post' => $post
        // ], 201);
    }
    
    /**
     * Display all posts
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }
    
    /**
     * Display a single post
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }
}