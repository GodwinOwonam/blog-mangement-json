<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Login Credentials</title>
</head>
<body>
    <form action="{{ route('auth.login') }}" id="createBlog" method="POST" enctype="multipart/form-data">
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

        <input type="email" placeholder="email" name="email" required> <br>
        <input type="passsword" placeholder="password" name="password" required> <br>
        <input type="passsword" placeholder="confirm password" name="password_confirmation" required> <br>
        <br>
        <button type="submit">Create</button>
    </form>
</body>
</html>
