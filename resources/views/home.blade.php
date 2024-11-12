<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About AML</title>
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
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register-user') }}">Sign Up</a></li>
                    <li><a href="{{ route('test') }}">Contact us</a></li>
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
    
    
    <main>
        <h1>About AML</h1>
        <p>AML is a library system containing...</p>
    </main>
    
    <script src="script.js"></script>
</body>
</html>
