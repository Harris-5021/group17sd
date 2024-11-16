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

            <!-- Procurement Section -->
            <div class="dashboard-card">
                <h2>Procure New Media</h2>
                <form action="{{ route('procurement.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="media_id">Select Media</label>
                        <select name="media_id" id="media_id" required>
                            <option value="">Select Media Item</option>
                            
                             <option value="Book">Book</option>
                            
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="procurement_date">Procurement Date</label>
                        <input type="date" name="procurement_date" id="procurement_date" required>
                    </div>

                    <div class="form-group">
                        <label for="procurement_type">Procurement Type</label>
                        <select name="procurement_type" id="procurement_type" required>
                            <option value="purchase">Purchase</option>
                            <option value="license">License</option>
                            <option value="donation">Donation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="supplier_name">Supplier Name</label>
                        <input type="text" name="supplier_name" id="supplier_name" required>
                    </div>

                    <div class="form-group">
                        <label for="procurement_cost">Procurement Cost</label>
                        <input type="number" step="0.01" name="procurement_cost" id="procurement_cost">
                    </div>


                    <div class="form-group">
                        <label for="payment_status">Payment Status</label>
                        <select name="payment_status" id="payment_status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Submit Procurement</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Accessibility Toolbar -->
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
