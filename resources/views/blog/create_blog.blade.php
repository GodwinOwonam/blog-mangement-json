<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Blog</title>
</head>
<body>
    <div>
        <p><a href="{{ route('admin.index')}}">View Blogs</a></p>
    </div>
    <form action="{{ route('blogs.create') }}" id="createBlog" method="POST" enctype="multipart/form-data">
        @csrf
        <h4>Create a blog</h4>
        @if (isset($message))
            <p
            @if (isset($success) and $success === true)
                style="color: green;"
            @else
                style="color:red"
            @endif
            >
                {{ $message }}
            </p>
        @endif

        <input type="text" placeholder="blog title" name="title" required> <br>
        <textarea name="description" id="description" cols="30" rows="10" required></textarea> <br>
        <input type="text" placeholder="enter categories separated by comma" name="categories" required> <br>
        <input type="file" name="thumbnail" id="thumbnail" required> <br>
        <input type="text" placeholder="Author's name" name="author_name" required> <br>
        <input type="email" placeholder="Author's email" name="author_email" required> <br>
        <br>
        <button type="submit">Create</button>
    </form>
</body>
</html>
