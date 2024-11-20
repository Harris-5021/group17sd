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
                   <li><a href="{{ route('viewProcurements') }}">Procurement Management</a></li>
                   <li><a href="{{ route('signout') }}">Sign Out</a></li>
               </ul>
           </nav>
       </div>
   </header>

   <main class="dashboard-container">
       <h1>Create New Procurement Record</h1>
       
       <div class="dashboard-grid">
           <!-- Notifications Card -->
           <div class="dashboard-card">
               <h2>Notifications</h2>
               @if($notifications->count() > 0)
                   <div class="notifications-list">
                       @foreach($notifications as $notification)
                           <div class="notification-item {{ $notification->status }}">
                               <div class="notification-content">
                                   <span class="notification-title">{{ $notification->title }}</span>
                                   <span class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                   <a href="{{ route('notifications.show', $notification->id) }}" class="view-link">View Details</a>
                               </div>
                           </div>
                       @endforeach
                   </div>
               @else
                   <p>No new notifications</p>
               @endif
           </div>

           <!-- Procurement Form Card -->
           <div class="dashboard-card">
               <h2>Procure New Media</h2>
               <form action="{{ route('procurement.store') }}" method="POST">
                   @csrf
                   <div class="form-group">
                       <label for="media_type">Select Media Type</label>
                       <select name="media_type" id="media_type" required>
                           <option value="">Select Media Type</option>
                           <option value="Book">Book</option>
                           <option value="DVD">DVD</option>
                           <option value="Magazine">Magazine</option>
                           <option value="E-Book">E-Book</option>
                           <option value="Audio">Audio</option>
                       </select>
                   </div>
               
                   <div class="form-group">
                       <label for="title">Media Title</label>
                       <input type="text" name="title" id="title" required>
                   </div>
               
                   <div class="form-group">
                       <label for="author">Author</label>
                       <input type="text" name="author" id="author">
                   </div>
               
                   <div class="form-group">
                       <label for="publication_year">Publication Year</label>
                       <input type="number" name="publication_year" id="publication_year" min="1000" max="{{ date('Y') }}">
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

                   <div class="form-group">
                       <label for="branch_location">Branch Location</label>
                       <select name="branch_location" id="branch_location" required>
                           @foreach(DB::table('branches')->get() as $branch)
                               <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                           @endforeach
                       </select>
                   </div>
                   
                   <button type="submit" class="btn-submit">Submit Procurement</button>
               </form>
           </div>
       </div>
   </main>

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

   <style>
   .dashboard-grid {
       display: grid;
       grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
       gap: 20px;
       padding: 20px;
   }

   .dashboard-card {
       background: white;
       border-radius: 8px;
       padding: 20px;
       box-shadow: 0 2px 4px rgba(0,0,0,0.1);
   }

   .notifications-list {
       max-height: 300px;
       overflow-y: auto;
   }

   .notification-item {
       padding: 15px;
       border-bottom: 1px solid #eee;
       margin-bottom: 10px;
   }

   .notification-item.unread {
       background: #f0f7ff;
       border-left: 4px solid #0066cc;
   }

   .notification-content {
       display: flex;
       flex-direction: column;
       gap: 5px;
   }

   .notification-title {
       font-weight: 500;
       color: #333;
   }

   .notification-time {
       font-size: 0.8em;
       color: #666;
   }

   .view-link {
       color: #0066cc;
       text-decoration: none;
       font-size: 0.9em;
   }

   .view-link:hover {
       text-decoration: underline;
   }

   .form-group {
       margin-bottom: 15px;
   }

   .form-group label {
       display: block;
       margin-bottom: 5px;
       color: #555;
   }

   .form-group input,
   .form-group select {
       width: 100%;
       padding: 8px;
       border: 1px solid #ddd;
       border-radius: 4px;
   }

   .btn-submit {
       background: #0066cc;
       color: white;
       padding: 10px 20px;
       border: none;
       border-radius: 4px;
       cursor: pointer;
       width: 100%;
   }

   .btn-submit:hover {
       background: #0052a3;
   }
   </style>

   <script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
</body>
</html>
