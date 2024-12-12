<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
    <style>
    .center-search {
            margin: 0px;
            padding: 15px;
            left: 30%; 
            }
    .btn {
        margin: 0px;
        position: absolute;
        top: 35%;
        left: 46%;
        padding: 10px 15px;
        background: #007bff;
        color: white;
        height: 70px;
        width: 100px;
        text-decoration: none;
        border-radius: 4px;
        }
    footer {
  text-align: left;
  padding-top: 30px;
  color: Black;
}

</style>
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
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="dashboard-container">
        <h1>Welcome, {{ $user->name }}!</h1>
        <div class="center-search">
                <form action="{{ route('searchUser') }}" method="GET" class="searchUser" align: >
                    <input type="text" name="query" placeholder="Search Users..." value="{{ request('query') }}">
                    <button type="submit">&#128269;</button>
                </form>
        </div>
</main>

<!--<button class = "btn" a href="{{ route('branch_profits') }}">See Branch Profits</button>-->
<div>
    <a href="{{ route('branch_profits') }}" class = "btn">View branch profits</a>
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

<script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
</footer>
</html>