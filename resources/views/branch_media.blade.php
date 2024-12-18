<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Media - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <li><a href="{{ route('dashboard.member') }}">My Account Dashboard</a></li>
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
    <body>
    <h1>Media available at: {{$name}}</h1>
    <main>
        @if($branch_media->count() > 0)
            <div class="media-grid">
                @foreach($branch_media as $item)
                    <div class="media-card">
                        <h2>Title: {{$item->title}}</h2>
                        <h3>Author: {{ $item->author }}</h3>
                        <p>Type: {{ $item->type }}</p>
                        <p>Copies Available: {{ $item->quantity }}</p> <!-- Added this line -->
                        <!-- Borrow Form -->
                        <form action="{{ route('borrow', $item->id) }}" method="POST" onsubmit="event.stopPropagation();" id="borrow-form-{{ $item->id }}">
                            @csrf
                            <input type="hidden" name="branch_id" value="{{ $branch_id }}">
                            <button type="submit" class="borrow-btn" {{ $item->quantity < 1 ? 'disabled' : '' }}>
                                {{ $item->quantity < 1 ? 'Out of Stock' : 'Borrow' }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-results">No media in stock right now</p>
        @endif
        </main>

        <style>
            .media-card {
                /* ... existing styles ... */
                position: relative;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background: white;
            }
        
            .borrow-btn {
                padding: 8px 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
        
            .borrow-btn:disabled {
                background-color: #cccccc;
                cursor: not-allowed;
            }
        
            /* Optional: Add a nice transition effect */
            .borrow-btn {
                transition: background-color 0.3s ease;
            }
        </style>
    </body>