<!DOCTYPE html>
<html>

<head>
    <title>All Posts</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f7fb;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        h1 {
            color: #2c3e50;
        }

        .btn {
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            color: #fff;
            display: inline-block;
        }

        .btn-create {
            background: #28a745;
        }

        .btn-view {
            background: #0d6efd;
        }

        .btn-delete {
            background: #dc3545;
            border: none;
            cursor: pointer;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .card h2 {
            font-size: 32px;
            color: #0d6efd;
            margin-top: 10px;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .search-box form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .search-box button {
            padding: 10px 20px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .alert {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .post {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .08);
        }

        .post h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .post-content {
            margin-bottom: 15px;
            color: #555;
            line-height: 1.7;
        }

        .meta {
            color: #888;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .pagination {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .custom-pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .custom-pagination a {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            background: #fff;
            color: #0d6efd;
            border: 1px solid #d0d7de;
            border-radius: 8px;
            font-weight: 600;
            transition: .3s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .custom-pagination a:hover {
            background: #0d6efd;
            color: #fff;
        }

        .custom-pagination a.active {
            background: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        svg {
            width: 18px;
        }

        @media(max-width:768px) {

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box form {
                flex-direction: column;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">

            <h1>📝 TinyMCE Posts</h1>

            <a href="{{ route('posts.create') }}" class="btn btn-create">
                Create New Post
            </a>

        </div>

        @if(session('success'))

        <div class="alert">
            {{ session('success') }}
        </div>

        @endif

        <div class="dashboard">

            <div class="card">
                <h4>Total Posts</h4>
                <h2>{{ $totalPosts }}</h2>
            </div>

            <div class="card">
                <h4>Today's Posts</h4>
                <h2>{{ $todayPosts }}</h2>
            </div>

            <div class="card">
                <h4>Latest Post</h4>

                @if($latestPost)

                <p style="margin-top:10px;">
                    {{ \Illuminate\Support\Str::limit($latestPost->title,30) }}
                </p>

                @else

                <p style="margin-top:10px;">No Post</p>

                @endif

            </div>

        </div>

        <div class="search-box">

            <form method="GET" action="{{ route('posts.index') }}">

                <input
                    type="text"
                    name="search"
                    placeholder="Search title or content..."
                    value="{{ request('search') }}">

                <button type="submit">
                    Search
                </button>

                @if(request('search'))

                <a href="{{ route('posts.index') }}"
                    class="btn"
                    style="background:#6c757d;">
                    Clear
                </a>

                @endif

            </form>

        </div>

        @if($posts->count())

        @foreach($posts as $post)

        <div class="post">

            <h3>{{ $post->title }}</h3>

            <div class="post-content">

                {!! \Illuminate\Support\Str::limit($post->content,300) !!}

            </div>

            <div class="meta">

                Created :
                {{ $post->created_at->format('d M Y h:i A') }}

            </div>

            <div class="actions">

                <a
                    href="{{ route('posts.show',$post) }}"
                    class="btn btn-view">

                    View

                </a>

                <form
                    action="{{ route('posts.destroy',$post) }}"
                    method="POST"
                    onsubmit="return confirm('Delete this post?')">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-delete">

                        Delete

                    </button>

                </form>

            </div>

        </div>

        @endforeach

        <div class="pagination">

            <div style="margin-top:30px">
                @if($posts->lastPage() > 1)
                <div class="custom-pagination">

                    @for($i = 1; $i <= $posts->lastPage(); $i++)

                        <a href="{{ $posts->url($i) }}"
                            class="{{ $posts->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </a>

                        @endfor

                </div>
                @endif
            </div>

        </div>

        @else

        <div class="card">

            <h3>No Posts Found.</h3>

        </div>

        @endif

    </div>

</body>

</html>