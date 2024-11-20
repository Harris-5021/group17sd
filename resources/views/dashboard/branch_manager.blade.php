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
            
            <div class="dashboard-grid">
                <!-- Your existing dashboard cards -->
            
                <!-- New Notifications Card -->
                <div class="dashboard-card notifications-card">
                    <h2>Notifications</h2>
                    @if($notifications->count() > 0)
                        <div class="notifications-container">
                            <div class="notifications-header">
                                <h3>Unread Messages</h3>
                            </div>
                            <div class="notifications-list">
                                @foreach($notifications->where('status', 'unread') as $notification)
                                    <div class="notification-item unread">
                                        <div class="notification-content">
                                            <h4>{{ $notification->title }}</h4>
                                            <p>{{ $notification->message }}</p>
                                            <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                        </div>
                                        <form action="{{ route('notifications.toggle', $notification->id) }}" method="POST" class="notification-actions">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="mark-read-btn">Mark as Read</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
            
                            <div class="notifications-header">
                                <h3>Read Messages</h3>
                            </div>
                            <div class="notifications-list">
                                @foreach($notifications->where('status', 'read') as $notification)
                                    <div class="notification-item read">
                                        <div class="notification-content">
                                            <h4>{{ $notification->title }}</h4>
                                            <p>{{ $notification->message }}</p>
                                            <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                                        </div>
                                        <form action="{{ route('notifications.toggle', $notification->id) }}" method="POST" class="notification-actions">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="mark-unread-btn">Mark as Unread</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p>No notifications to display.</p>
                    @endif
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
                align-items: center;
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
                margin-left: 15px;
            }
            
            .mark-read-btn, .mark-unread-btn {
                padding: 5px 10px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 0.9em;
            }
            
            .mark-read-btn {
                background-color: #4CAF50;
                color: white;
            }
            
            .mark-unread-btn {
                background-color: #808080;
                color: white;
            }
            
            .mark-read-btn:hover, .mark-unread-btn:hover {
                opacity: 0.9;
            }
            </style>
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