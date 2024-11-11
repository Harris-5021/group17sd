<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('AML.png') }}" alt="AML Logo">
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="#">Browse Media</a></li>
                    <li><a href="#">My Wishlist</a></li>
                    <li><a href="#">My Borrowed Items</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
            <div class="search">
                <input type="text" placeholder="Search Media...">
                <button>&#128269;</button>
            </div>
        </div>
    </header>

    <main class="dashboard-container">
        <h1>Welcome, {{ $user->name }}!</h1>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="#" class="action-btn">Browse Books</a>
                    <a href="#" class="action-btn">View Wishlist</a>
                    <a href="#" class="action-btn">Return Books</a>
                </div>
            </div>

            <div class="dashboard-card">
                <h2>Currently Borrowed</h2>
                <p>You haven't borrowed any items yet.</p>
            </div>

            <div class="dashboard-card">
                <h2>My Wishlist</h2>
                <p>Your wishlist is empty.</p>
            </div>
        </div>
    </main>
</body>
</html>