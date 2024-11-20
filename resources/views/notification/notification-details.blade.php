<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Details - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .notification-details {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .back-btn {
            display: inline-block;
            color: #666;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .back-btn:hover {
            color: #333;
        }

        .notification-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .notification-header h1 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 24px;
        }

        .timestamp {
            color: #888;
            font-size: 14px;
        }

        .notification-body {
            margin-bottom: 30px;
            line-height: 1.6;
            color: #444;
        }

        .notification-actions {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-select, .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 2px rgba(0,102,204,0.2);
        }

        textarea.form-input {
            min-height: 100px;
            resize: vertical;
        }

        .mark-read-btn, .mark-unread-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
            width: 100%;
        }

        .mark-read-btn {
            background-color: #4CAF50;
            color: white;
        }

        .mark-unread-btn {
            background-color: #808080;
            color: white;
        }

        .procurement-form {
            margin-top: 20px;
        }

        .procurement-form h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .submit-btn {
            background-color: #0066cc;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .submit-btn:hover {
            background-color: #0052a3;
        }

        /* Form validation styles */
        .form-input:invalid, .form-select:invalid {
            border-color: #dc3545;
        }

        /* Success message styles */
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .notification-details {
                padding: 20px;
            }

            .notification-actions {
                padding: 15px;
            }
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
                    <li><a href="{{ route('browse') }}">Browse Media</a></li>
                    <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                    <li><a href="{{ route('borrowed') }}">My Borrowed Items</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="query" placeholder="Search Media..." value="{{ request('query') }}">
                    <button type="submit">&#128269;</button>
                </form>
            </div>
        </div>
    </header>


    <main class="container">
        <div class="notification-details">
            <a href="{{ route('dashboard.branch_manager') }}" class="back-btn">← Back to Dashboard</a>
            
            <div class="notification-header">
                <h1>{{ $notification->title }}</h1>
                <span class="timestamp">{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}</span>
            </div>

            <div class="notification-body">
                <p>{{ $notification->message }}</p>
            </div>

            <div class="notification-actions">
                <form action="{{ route('notifications.toggle', $notification->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="{{ $notification->status === 'read' ? 'mark-unread-btn' : 'mark-read-btn' }}">
                        Mark as {{ $notification->status === 'read' ? 'Unread' : 'Read' }}
                    </button>
                </form>
                @if(Auth::user()->role === 'branch_manager')
                <form action="{{ route('notifications.forward', $notification->id) }}" method="POST" class="procurement-form">
                    @csrf
                    <h2>Forward to Purchase Manager</h2>
                    
                    <div class="form-group">
                        <label>Media Type</label>
                        <select name="media_type" required class="form-select">
                            <option value="">Select Media Type</option>
                            <option value="Book">Book</option>
                            <option value="DVD">DVD</option>
                            <option value="Magazine">Magazine</option>
                            <option value="E-Book">E-Book</option>
                            <option value="Audio">Audio</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quantity Needed</label>
                        <input type="number" name="quantity" required min="1" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Preferred Supplier (optional)</label>
                        <input type="text" name="supplier_name" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Additional Notes</label>
                        <textarea name="additional_notes" class="form-input"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Estimated Cost (optional)</label>
                        <input type="number" name="estimated_cost" step="0.01" class="form-input">
                    </div>

                    <button type="submit" class="submit-btn">Send to Purchase Manager</button>
                </form>
            </div>
            @endif
        </div>
    </main>
</body>
</html>