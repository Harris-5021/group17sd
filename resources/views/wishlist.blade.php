<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - AML</title>
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
        <h1>My Wishlist</h1>
        <div class="media-grid">
            @foreach($wishlistItems as $item)
                <div class="media-card">
                    <h2>{{ $item->title }}</h2>
                    <p>By {{ $item->author }}</p>
                    <p>Type: {{ $item->type }}</p>
                    <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-btn">Remove from Wishlist</button>
                    </form>
                </div>
            @endforeach
        </div>
    </main>
</body>
</html>