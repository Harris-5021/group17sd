<!-- resources/views/vendors/create.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Vendor</title>
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
    <h1>Add New Vendor</h1>

    <form action="{{ route('vendors.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Vendor Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="address">Vendor Address</label>
            <input type="text" id="address" name="address">
        </div>
        <div>
            <label for="contact">Vendor Contact</label>
            <input type="text" id="contact" name="contact">
        </div>
        <button type="submit">Add Vendor</button>
    </form>

    <a href="{{ route('vendors.index') }}">Back to Vendor List</a>
</body>
</html>
