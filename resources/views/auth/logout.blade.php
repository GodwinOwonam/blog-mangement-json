<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Logout</title>
</head>
<body>
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

    <p><a href="{{ route('index') }}">Back to Homepage</a></p>

</body>
</html>
