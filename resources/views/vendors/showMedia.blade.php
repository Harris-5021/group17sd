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
                   <li><a href="{{ route('dashboard.purchase_manager') }}">Create Procurements</a></li>
                   <li><a href="{{ route('vendors.index') }}">Select Vendor</a></li>
                   <li><a href="{{ route('signout') }}">Sign Out</a></li>
                   
               </ul>
           </nav>
       </div>
   </header>

   <h1>Media Available from {{ $vendor->name }}</h1>

   @if($mediaList->isEmpty())
       <p>No media available for this vendor at the moment.</p>
   @else
       <ul>
           @foreach($mediaList as $media)
               <li>
                   <strong>{{ $media->title }}</strong><br>
                   <em>{{ $media->type }}</em><br>
                   @if($media->procurement_cost)
                       <p>Price: ${{ number_format($media->procurement_cost, 2) }}</p>  <!-- Displaying price -->
                   @else
                       <p>Price: N/A</p>  <!-- In case no price is set -->
                   @endif

                    <!-- Form to add media to procurement -->
                   <form action="{{ route('procurement.store') }}" method="POST">
                       @csrf
                       <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                       <input type="hidden" name="media_id" value="{{ $media->id }}">
                       
                       <!-- Hidden fields to send additional media data -->
                       <input type="hidden" name="title" value="{{ $media->title }}">
                       <input type="hidden" name="author" value="{{ $media->author }}">
                       <input type="hidden" name="media_type" value="{{ $media->type }}">
                       <input type="hidden" name="publication_year" value="{{ $media->publication_year }}">
                       <input type="hidden" name="status" value="{{ $media->status }}">
                       <input type="hidden" name="description" value="{{ $media->description }}">
                       <input type="hidden" name="procurement_date" value="{{ \Carbon\Carbon::now()->toDateString() }}"> <!-- Default to today's date -->
                       <input type="hidden" name="procurement_type" value="purchase"> <!-- Default procurement type -->
                       <input type="hidden" name="supplier_name" value="{{ $vendor->name }}"> <!-- Set the supplier to the vendor's name -->
                       <input type="hidden" name="procurement_cost" value="{{ $media->procurement_cost }}"> <!-- Set default cost to media price -->
                       <input type="hidden" name="payment_status" value="pending"> <!-- Default payment status -->
                       
                       <label for="branch_location">Branch Location</label>
                       <select name="branch_location" id="branch_location" required>
                           <option value="">Select Branch</option>
                           @foreach($branches as $branch)
                               <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                           @endforeach
                       </select>
                       <label for="quantity">Quantity</label>
                       <input type="number" name="quantity" id="quantity" min="1" required><br><br>

                       <button type="submit">Add to Procurement</button>
                   </form>
               </li>
           @endforeach
       </ul>
   @endif

   <a href="{{ route('vendors.index') }}">Back to Vendor List</a>
</body>
</html>
