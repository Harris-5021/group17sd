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
        <h1>Browse library branches</h1>

    <main class="container">
    <h1>Browse Branches</h1>
    <div class="media-grid">
    @foreach($branches as $branch)
    <div class="media-card">
    <h2>{{$branch->name}}</h2>
    <p>Address: </p>
    <p>{{$branch->address}}</p>
    <p>Contact Number: {{$branch->contact_number}}</p>
    <p>Email: {{$branch->email}}</p>
    <p>Opening Hours: {{$branch->opening_hours}}</p>
    <form method="GET" action="{{ route('branch_media', ['branch_id'=> $branch->id, 'name' =>$branch->name])}}">
            <button type = "submit"> View more </button> 
    </form>
    </div>
    @endforeach


    </div>
    </body>