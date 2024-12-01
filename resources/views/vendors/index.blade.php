<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendors</title>
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
                    <li><a href="{{ route('viewProcurements') }}">Procurement Management</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                    <li><a href="{{ route('vendors.index') }}">Select Vendor</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>Vendors List</h1>

        <a href="{{ route('vendors.create') }}" class="add-new-vendor">Add New Vendor</a>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <div class="vendor-grid">
            @foreach($vendors as $vendor)
                <div class="vendor-card">
                    <h2><a href="{{ route('vendors.showMedia', $vendor->id) }}">{{ $vendor->name }}</a></h2>
                    <p>Click to view media or details</p>
                </div>
            @endforeach
        </div>
    </div>

</body>
</html>
