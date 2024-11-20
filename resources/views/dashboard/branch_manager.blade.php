<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Manager Dashboard - AML</title>
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
                <form action="{{ route('search') }}" method="GET">
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
                    <a href="{{ route('borrowed') }}" class="action-btn">Return Books</a>
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

            <div class="dashboard-card notifications-card">
                <h2>Notifications</h2>
                @if($notifications->count() > 0)
                    <div class="notifications-container">
                        <div class="notifications-header">
                            <h3>Unread Messages</h3>
                        </div>
                        <div class="notifications-list">
                            @foreach($notifications as $notification)
                                @if($notification->status === 'unread')
                                    <div class="notification-item unread">
                                        <div class="notification-content">
                                            <h4>{{ $notification->title }}</h4>
                                            <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                        </div>
                                        <div class="notification-actions">
                                            <a href="{{ route('notifications.show', $notification->id) }}" class="view-btn">View Details</a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
            
                        <div class="notifications-header">
                            <h3>Read Messages</h3>
                        </div>
                        <div class="notifications-list">
                            @foreach($notifications as $notification)
                                @if($notification->status === 'read')
                                    <div class="notification-item read">
                                        <div class="notification-content">
                                            <h4>{{ $notification->title }}</h4>
                                            <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                        </div>
                                        <div class="notification-actions">
                                            <a href="{{ route('notifications.show', $notification->id) }}" class="view-btn">View Details</a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <p>No notifications to display.</p>
                @endif
            </div>
        </div>
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

    <style>
    .notifications-card {
        grid-column: 1 / -1;
    }

    .notifications-container {
        max-height: 500px;
        overflow-y: auto;
    }

    .notifications-header {
        background-color: #f5f5f5;
        padding: 10px;
        margin: 10px 0;
    }

    .notification-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: background-color 0.3s ease;
    }

    .notification-item.unread {
        background-color: #f0f7ff;
        border-left: 4px solid #0066cc;
    }

    .notification-item.read {
        background-color: white;
        opacity: 0.8;
    }

    .notification-content {
        flex-grow: 1;
        margin-right: 20px;
    }

    .notification-content h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .notification-content p {
        margin: 0 0 5px 0;
        color: #666;
    }

    .notification-content small {
        color: #999;
    }

    .notification-actions {
        min-width: 200px;
    }

    .procurement-form {
        margin-top: 15px;
        padding: 15px;
        background: #f5f5f5;
        border-radius: 4px;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .mark-read-btn, .mark-unread-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
        width: 100%;
        margin-bottom: 10px;
    }

    .mark-read-btn {
        background-color: #4CAF50;
        color: white;
    }

    .mark-unread-btn {
        background-color: #808080;
        color: white;
    }

    .forward-btn {
        background-color: #0066cc;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    .mark-read-btn:hover, .mark-unread-btn:hover, .forward-btn:hover {
        opacity: 0.9;
    }
    </style>

    <script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
</body>
</html>