<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $media->title }} - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('AML.png') }}" alt="AML Logo">
            </a>
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('browse') }}">Browse Media</a></li>
                    <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                    <li><a href="{{ route('borrowed') }}">My Borrowed Items</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="query" placeholder="Search Media...">
                    <button type="submit">&#128269;</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="media-details">
            <h1>{{ $media->title }}</h1>
            <p class="author">By {{ $media->author }}</p>
            <p class="type">Type: {{ $media->type }}</p>
            <p class="year">Published: {{ $media->publication_year }}</p>
            <p class="publisher">Publisher: {{ $media->publisher }}</p>
            <p class="description">{{ $media->description }}</p>
            
            @if($media->status === 'available')
                <form action="{{ route('borrow', $media->id) }}" method="POST">
                    @csrf
                    <select name="branch_id" required class="branch-select">
                        <option value="">Select Branch</option>
                        @foreach(DB::table('branches')->get() as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="borrow-btn">Borrow</button>
                </form>
            @endif
        </div>
    </main>
</body>
</html>