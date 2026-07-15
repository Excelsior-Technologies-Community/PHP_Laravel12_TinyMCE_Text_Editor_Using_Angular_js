<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Show Create Form
     */
    public function create()
    {
        return view('simple');
    }

    /**
     * Store Post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'required|in:Draft,Published',
        ]);

        Post::create([
            'title'   => $request->title,
            'content' => $request->content,
            'status'  => $request->status,
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display Posts
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $posts = Post::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('content', 'LIKE', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $totalPosts = Post::count();

        $todayPosts = Post::whereDate('created_at', today())->count();

        $publishedPosts = Post::where('status', 'Published')->count();

        $draftPosts = Post::where('status', 'Draft')->count();

        $latestPost = Post::latest()->first();

        return view('posts.index', compact(
            'posts',
            'search',
            'status',
            'totalPosts',
            'todayPosts',
            'publishedPosts',
            'draftPosts',
            'latestPost'
        ));
    }

    /**
     * Show Single Post
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Delete Post
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}