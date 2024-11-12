<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - AML</title>
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
                    <li><a href="{{ route('dashboard') }}">My Account Dashboard</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                    <li><a href="{{ route('test') }}">Contact us</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="query" placeholder="Search Media..." value="{{ $query }}">
                    <button type="submit">&#128269;</button>
                </form>
            </div>
        </div>
    </header>

    <main class="search-results-container">
        <h1>Search Results for "{{ $query }}"</h1>
        
        @if($media->count() > 0)
            <div class="media-grid">
                @foreach($media as $item)
                    <div class="media-card">
                        <h2>{{ $item->title }}</h2>
                        <p class="author">By {{ $item->author }}</p>
                        <p class="type">Type: {{ $item->type }}</p>
                        <p class="status">Status: {{ $item->status }}</p>
                        <p class="publication">Published: {{ $item->publication_year }}</p>
                        <p class="publisher">Publisher: {{ $item->publisher }}</p>
                      <!-- In searchresults.blade.php -->
<div class="actions">
    @if($item->status === 'available')
        <form action="{{ route('borrow', $item->id) }}" method="POST">
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
    
    <form action="{{ route('wishlist.add', $item->id) }}" method="POST">
        @csrf
        <button type="submit" class="wishlist-btn">Add to Wishlist</button>
    </form>
</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-results">No results found for "{{ $query }}"</p>
        @endif
    </main>
</body>
</html>