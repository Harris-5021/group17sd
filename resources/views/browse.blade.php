<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Media - AML</title>
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
        <h1>Browse Media</h1>
        <div class="media-grid">
            @foreach($media as $item)
                <div class="media-card">
                    <h2>{{ $item->title }}</h2>
                    <p>By {{ $item->author }}</p>
                    <p>Type: {{ $item->type }}</p>
                    <p>Published: {{ $item->publication_year }}</p>
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
                        <form action="{{ route('wishlist.add', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="wishlist-btn">Add to Wishlist</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
        {{ $media->links() }}
    </main>
</body>
</html>