<!-- resources/views/vendors/showMedia.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Media from {{ $vendor->name }}</title>
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


    <h1>Media Available from {{ $vendor->name }}</h1>

    <ul>
        @foreach($mediaList as $media)
            <li>
                <strong>{{ $media->title }}</strong><br>
                <em>{{ $media->type }}</em><br>
                @if($media->price)
                    <p>Price: ${{ number_format($media->price, 2) }}</p>  <!-- Displaying price -->
                @else
                    <p>Price: N/A</p>  <!-- In case no price is set -->
                @endif

                <!-- Form to add media to procurement -->
                <form action="{{ route('procurement.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <input type="hidden" name="media_id" value="{{ $media->id }}">
                    <button type="submit">Add to Procurement</button>
                </form>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('vendors.index') }}">Back to Vendor List</a>
</body>
</html>
