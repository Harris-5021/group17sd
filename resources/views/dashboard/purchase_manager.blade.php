<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Manager Dashboard - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
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

    <main class="dashboard-container">
        <h1>Welcome, {{ $user->name }}!</h1>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="{{ route('browse') }}" class="action-btn">Browse Books</a>
                    <a href="{{ route('wishlist') }}" class="action-btn">View Wishlist</a>
                    <a href="{{ route('borrowed') }}" class="action-btn">Return Books</a>  <!-- Changed from media.return -->
                </div>
            </div>

            <div class="dashboard-card">
                <h2>Currently Borrowed</h2>
                @if($borrowedItems->count() > 0)
                    <div class="borrowed-items">
                        @foreach($borrowedItems as $item)
                            <div class="borrowed-item">
                                <h3>{{ $item->title }}</h3>
                                <p>Author: {{ $item->author }}</p>
                                <p>Borrowed: {{ \Carbon\Carbon::parse($item->borrowed_date)->format('d/m/Y') }}</p>
                                <p>Due: {{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>You haven't borrowed any items yet.</p>
                @endif
            </div>
            
            <div class="dashboard-card">
                <h2>My Wishlist</h2>
                @if($wishlistItems->count() > 0)
                    <div class="wishlist-items">
                        @foreach($wishlistItems as $item)
                            <div class="wishlist-item">
                                <h3>{{ $item->title }}</h3>
                                <p>By {{ $item->author }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Your wishlist is empty.</p>
                @endif
            </div>
        </div>
    </main>
    <!-- Add this HTML to your pages -->
<div class="accessibility-toolbar">
    <button id="accessibilityToggle" class="toolbar-toggle">
        <span class="icon">Aa</span>
    </button>
    
    <div id="toolbarPanel" class="toolbar-panel hidden">
        <h3>Accessibility Options</h3>
        
        <div class="toolbar-section">
            <label>Text Size</label>
            <div class="button-group">
                <button id="decreaseText">A-</button>
                <button id="increaseText">A+</button>
            </div>
        </div>

        <div class="toolbar-section">
            <label>Contrast</label>
            <button id="toggleContrast">Toggle High Contrast</button>
        </div>

        <div class="toolbar-section">
            <label>Text Weight</label>
            <button id="toggleBold">Toggle Bold Text</button>
        </div>
    </div>
</div>

<div class="accessibility-toolbar">
    <button id="accessibilityToggle" class="toolbar-toggle">
        <span class="icon">Aa</span>
    </button>
    
    <div id="toolbarPanel" class="toolbar-panel hidden">
        <h3>Accessibility Options</h3>
        
        <div class="toolbar-section">
            <label>Text Size</label>
            <div class="button-group">
                <button id="decreaseText">A-</button>
                <button id="increaseText">A+</button>
            </div>
        </div>

        <div class="toolbar-section">
            <label>Contrast</label>
            <button id="toggleContrast">Toggle High Contrast</button>
        </div>

        <div class="toolbar-section">
            <label>Text Weight</label>
            <button id="toggleBold">Toggle Bold Text</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
</body>
</html>