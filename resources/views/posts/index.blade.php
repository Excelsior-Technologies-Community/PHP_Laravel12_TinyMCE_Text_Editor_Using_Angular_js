<!DOCTYPE html>
<html>
<head>
    <title>All Posts</title>
    <style>
        body {
            font-family: -apple-system, sans-serif;
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #2c3e50;
            margin: 0;
        }
        .btn-create {
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .posts-container {
            display: grid;
            gap: 20px;
        }
        .post-card {
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 20px;
            background: white;
        }
        .post-title {
            font-size: 1.4rem;
            color: #2c3e50;
            margin: 0 0 10px 0;
        }
        .post-content {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .post-meta {
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        .no-posts {
            text-align: center;
            color: #95a5a6;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>All Posts</h1>
        <a href="{{ url('/') }}" class="btn-create">Create New Post</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($posts->count() > 0)
        <div class="posts-container">
            @foreach($posts as $post)
                <div class="post-card">
                    <h3 class="post-title">{{ $post->title }}</h3>
                    <div class="post-content">
                        {!! $post->content !!}
                    </div>
                    <div class="post-meta">
                        Created: {{ $post->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-posts">
            <h3>No posts yet</h3>
            <p>Create your first post by clicking the "Create New Post" button above.</p>
        </div>
    @endif
</body>
</html>