<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f7fb;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .meta {
            color: #777;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .content {
            line-height: 1.9;
            color: #444;
            font-size: 16px;
        }

        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .content table,
        .content th,
        .content td {
            border: 1px solid #ddd;
        }

        .content th,
        .content td {
            padding: 10px;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            display: inline-block;
        }

        .btn-back {
            background: #6c757d;
        }

        .btn-home {
            background: #198754;
        }

        .btn-delete {
            background: #dc3545;
            border: none;
            cursor: pointer;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #eee;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="card">

            <h1>{{ $post->title }}</h1>

            <div class="meta">
                <strong>Created:</strong>
                {{ $post->created_at->format('d M Y h:i A') }}
                <br><br>

                <strong>Last Updated:</strong>
                {{ $post->updated_at->format('d M Y h:i A') }}
            </div>

            <hr>

            <div class="content">
                {!! $post->content !!}
            </div>

            <div class="actions">

                <a href="{{ route('posts.index') }}" class="btn btn-back">
                    ← Back to Posts
                </a>

                <a href="{{ route('posts.create') }}" class="btn btn-home">
                    + Create New Post
                </a>

                <form action="{{ route('posts.destroy',$post) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this post?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn-delete">
                        Delete Post
                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>