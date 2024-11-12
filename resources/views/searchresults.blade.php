<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - AML</title>
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
                        <p class="publication">Published: {{ $item->publication_year }}</p>
                        <p class="publisher">Publisher: {{ $item->publisher }}</p>
                        <p class="branch">Available at: {{ $item->branch_name }}</p>
                        <p class="quantity">Copies available: {{ $item->quantity }}</p>

                        <div class="actions">
                            @if($item->quantity > 0)
                                <form action="{{ route('borrow', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="branch_id" value="{{ $item->branch_id }}">
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