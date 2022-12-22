<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blogs</title>
</head>
<body>
    <header>
        <div style="display: flex; align-items:center; justify-content:space-around;">
            <p><a href="{{ route('index')}}">Home</a></p>
            <p><a href="{{ route('blogs.show-create')}}">Create a blog</a></p>
            <p><a href="{{ route('auth.login')}}">Login</a></p>
            <p><a  style="color: red" href="{{ route('auth.logout')}}">Logout</a></p>
            <p>Viewing as: {{ $is_admin ? "Admin" : "Guest"}}</p>
        </div>
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
    </header>
    @if (!empty($blogs))

    <div style="display:flex; align-items:center; justify-content:space-around">
        <div>
            <select name="category" id="category">
                <option value="none" hidden>Choose Category to filter</option>
                @if (!empty($categories))
                    @foreach ($categories as $category)
                        <option value="{{$category}}">{{ strtoupper($category) }}</option>
                    @endforeach
                @endif
            </select>

            <button onclick="filterByCategory()">Filter</button>
        </div>
        <div>
            <input type="search" name="search" id="search" placeholder="search blog by title">
            <button onclick="searchByTitle()">Search</button>
        </div>
        <div>
            <p><a href="{{ route('index')}}">Clear Filters</a></p>
        </div>
    </div>

        @foreach ($blogs as $blog)
            <div style="display: flex; align-items:center;">
                <div>
                    <h4>Author: {{ $blog['author_name'] }}</h4>
                    @if($is_admin and $is_admin === true)
                        <h6>Author Email: {{ $blog['author_email'] }}</h6>
                    @endif
                    <h5>Title: {{ $blog['title'] }}</h5>
                    <div>
                        <h5>Description</h5>
                        <p>{{ $blog['description'] }}</p>
                    </div>
                    <div>
                        <h5>Categories</h5>
                        @foreach ($blog['categories'] as $category)
                            <p>{{ $category }}</p>
                        @endforeach
                    </div>
                    <h6>Published: {{ $blog['publish_date'] }} (UTC)</h6>
                </div>
                <div>
                    <img src="{{ $blog['thumbnail'] }}" alt="blog thumbnail" style="max-height: 10rem">
                </div>
            </div>
        @endforeach

    @else
    <p>No blogs yet!</p>
    <p><a href="{{ route('blogs.show-create')}}">Create a blog</a></p>
    @endif

</body>

<script>
    function searchByTitle() {
        window.location.href = getProperUrl('search', document.getElementById('search').value.trim());
    }

    function filterByCategory() {
        window.location.href = getProperUrl('category', document.getElementById('category').value.trim());
    }

    function getProperUrl(key, value) {
        const currentUrl = window.location.href;

        if(currentUrl.includes('?') && currentUrl.includes('=')) {
            return !currentUrl.endsWith('&') ? currentUrl + `&${key}=${value}` : currentUrl + `${key}=${value}`;
        } else {
            return  currentUrl.endsWith('?') ? currentUrl + `${key}=${value}` : currentUrl + `?${key}=${value}`;
        }

        return finalUrl;
    }
</script>
</html>
