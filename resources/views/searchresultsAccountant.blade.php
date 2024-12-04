<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Account results - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
</head>
    <header>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('AML.png') }}" alt="AML Logo">
            </a>
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard.accountant') }}">User search dashboard</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                    <li><a href="{{ route('dashboard.accountant') }}">Contact us</a></li>
                </ul>
            </nav>
          <main class="dashboard-container">
        </div>
    </header>
<body>

    <div class="center">
                <form action="{{ route('searchUser') }}" method="GET" class="searchUser" align: >
                    <input type="text" name="query" placeholder="Search Users..." value="{{ request('query') }}">
                    <button type="submit">&#128269;</button>
                </form>
        </div>
</main>

<div class="search-results-container">
<h1>Search Results for "{{ $query }}"</h1>
<main>
@if($users->count() > 0)
            <div class="media-grid">
                @foreach($users as $item)
                    <div class="media-card">
                        <h2>User ID: {{ $item->id}}</h2>
                        <h3>{{ $item->name }}</h3>
                        <p class="email"> Email: {{ $item->email }}</p>
                        <form method="GET" action="{{ route('subscription.showUser', ['id' => $item->id, 'name' => $item->name]) }}">
                        <button type = "submit"> View more </button> 
                        </form>
                    </div>
                        @endforeach

                @else
            <p class="no-results">No results found for "{{ $query }}"</p>
        @endif
    </main>

</div>
</body>
<footer>
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

</footer>
</html>