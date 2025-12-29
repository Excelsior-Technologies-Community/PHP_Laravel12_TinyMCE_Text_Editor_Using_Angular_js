# PHP_Laravel12_TinyMCE_Text_Editor_Using_ Angular.js

A complete and working implementation of a TinyMCE HTML rich text editor integrated with Laravel 12 and AngularJS. This project demonstrates how to build a CRUD-based text editor without using any TinyMCE API key or paid services. It is suitable for learning, interviews, and real-world projects.

---

## Project Overview

This application allows users to create, edit, view, and delete posts using a WYSIWYG editor. The frontend is built with AngularJS and Bootstrap, while the backend is powered by Laravel 12 with RESTful APIs.

---

## Features

* TinyMCE open-source editor (no API key required)
* Full CRUD operations for posts
* Rich text editing with HTML storage
* AngularJS-based dynamic frontend
* Laravel 12 REST API backend
* MySQL database integration
* Clean MVC architecture
* Beginner-friendly setup

---

## Prerequisites

* PHP 8.1 or higher
* Composer
* MySQL or SQLite
* Laravel 12
* Node.js (optional, for development)

---

## Installation Guide

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-tinymce-angular.git
cd laravel-tinymce-angular
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file and configure the database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_tinymce
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Run the Application

```bash
php artisan serve
```

Open your browser and visit:

```
http://127.0.0.1:8000
```
---
## Screenshot
### Create Page
<img width="1118" height="801" alt="image" src="https://github.com/user-attachments/assets/5eeb7bff-8c2b-458c-b7a8-a7d227304d2b" />
### All Posts
<img width="1298" height="375" alt="image" src="https://github.com/user-attachments/assets/e0b02ba9-598b-4d08-8911-de6ff80c1e70" />
---

## Project Structure

```
laravel-tinymce-angular/
├── app/
│   ├── Models/
│   │   └── Post.php
│   └── Http/Controllers/
│       └── PostController.php
├── database/
│   └── migrations/
│       └── xxxx_create_posts_table.php
├── resources/
│   └── views/
│       └── index.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── public/
└── README.md
```

---

## Database Schema

### Posts Table

```sql
CREATE TABLE posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## Backend Implementation

### Post Model

```php
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];
}
```

### Post Controller

```php
class PostController extends Controller
{
    public function index()
    {
        return Post::latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        return Post::create($request->all());
    }

    public function show($id)
    {
        return Post::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return $post;
    }

    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}
```

---

## Routes Configuration

### API Routes (`routes/api.php`)

```php
Route::apiResource('posts', PostController::class);
```

### Web Routes (`routes/web.php`)

```php
Route::get('/', function () {
    return view('index');
});
```

---

## Frontend Implementation

### Main View (`resources/views/index.blade.php`)

```html
<!DOCTYPE html>
<html ng-app="tinyMCEApp">
<head>
    <title>TinyMCE Editor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body ng-controller="PostController as vm" class="container mt-4">

<h3>Create Post</h3>
<input type="text" class="form-control mb-2" ng-model="vm.post.title" placeholder="Post Title">
<textarea id="editor" class="form-control"></textarea>
<button class="btn btn-primary mt-2" ng-click="vm.savePost()">Save</button>

<hr>

<h3>Posts</h3>
<div ng-repeat="post in vm.posts" class="border p-2 mb-2">
    <h5>{{ post.title }}</h5>
    <div ng-bind-html="post.content"></div>
    <button class="btn btn-danger btn-sm" ng-click="vm.deletePost(post.id)">Delete</button>
</div>

<script>
angular.module('tinyMCEApp', [])
.controller('PostController', function($http, $scope) {
    var vm = this;
    vm.posts = [];
    vm.post = {};

    vm.loadPosts = function() {
        $http.get('/api/posts').then(res => vm.posts = res.data);
    };

    vm.savePost = function() {
        vm.post.content = tinymce.get('editor').getContent();
        $http.post('/api/posts', vm.post).then(() => {
            vm.post = {};
            tinymce.get('editor').setContent('');
            vm.loadPosts();
        });
    };

    vm.deletePost = function(id) {
        $http.delete('/api/posts/' + id).then(() => vm.loadPosts());
    };

    vm.loadPosts();
});

tinymce.init({
    selector: '#editor',
    height: 300,
    menubar: false,
    plugins: 'lists link code',
    toolbar: 'undo redo | bold italic | bullist numlist | link | code'
});
</script>

</body>
</html>
```

---

## API Endpoints

| Method | Endpoint        | Description     |
| ------ | --------------- | --------------- |
| GET    | /api/posts      | Get all posts   |
| POST   | /api/posts      | Create post     |
| GET    | /api/posts/{id} | Get single post |
| PUT    | /api/posts/{id} | Update post     |
| DELETE | /api/posts/{id} | Delete post     |

---

## Security Considerations

* CSRF protection enabled
* Server-side validation
* Blade escaping for XSS protection
* No secret keys exposed

---

## Performance Tips

* Implement pagination for large datasets
* Cache frequently accessed posts
* Minify assets for production

---

## Deployment

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Update `.env`:

```env
APP_ENV=production
APP_DEBUG=false
```

---

## Future Enhancements

* Edit post feature
* Image upload support
* Post categories
* Search & filtering
* Role-based access
* API-only version

---

## License

This project is open-source and available under the MIT License.
