<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Items - AML</title>
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
                <form action="{{ route('search') }}" method="GET" class="search">
                    <input type="text" name="query" placeholder="Search Media..." value="{{ request('query') }}">
                    <button type="submit">&#128269;</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <h1>My Borrowed Items</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="media-grid">
            @if($activeLoans->count() > 0)
                @foreach($activeLoans as $item)
                    <div class="media-card">
                        <h2>{{ $item->title }}</h2>
                        <p>By {{ $item->author }}</p>
                        <p>Borrowed: {{ \Carbon\Carbon::parse($item->borrowed_date)->format('d/m/Y') }}</p>
                        <p>Due: {{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}</p>
                        <form action="{{ route('return', $item->loan_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="return-btn">Return Book</button>
                        </form>
                    </div>
                @endforeach
            @else
                <p>You haven't borrowed any items yet.</p>
            @endif
        </div>
    </main>
</body>
</html>