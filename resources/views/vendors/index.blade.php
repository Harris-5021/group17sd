<!-- resources/views/vendors/index.blade.php -->

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
<body>
    <h1>Vendors List</h1>
    <a href="{{ route('vendors.create') }}">Add New Vendor</a>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <ul>
        @foreach($vendors as $vendor)
            <li>
                <a href="{{ route('vendors.showMedia', $vendor->id) }}">{{ $vendor->name }}</a>
            </li>
        @endforeach
    </ul>
</body>
</html>
