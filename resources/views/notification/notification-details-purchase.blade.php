<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurement Request Details - AML</title>
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

        .procurement-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin-top: 20px;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
        }

        .action-buttons {
            margin-top: 30px;
            display: grid;
            gap: 10px;
        }

        .action-btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            width: 100%;
        }

        .accept-btn {
            background: #28a745;
            color: white;
        }

        .reject-btn {
            background: #dc3545;
            color: white;
        }

        .mark-btn {
            background: #6c757d;
            color: white;
        }

        .action-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="notification-details">
            <a href="{{ route('dashboard.purchase_manager') }}" class="back-btn">← Back to Dashboard</a>
        
            <h1>Procurement Request Details</h1>
            @php
                $message = $notification->message;
                $details = [];
                
                // Check if message is JSON or a string
                if (json_decode($message, true)) {
                    $details = json_decode($message, true);
                } else {
                    // Parse legacy message format
                    preg_match('/Type: (.*?) Quantity:/', $message, $type);
                    preg_match('/Quantity: (.*?) Supplier:/', $message, $quantity);
                    preg_match('/Supplier: (.*?) Est\. Cost:/', $message, $supplier);
                    preg_match('/Est\. Cost: £(.*?) Notes:/', $message, $cost);
                    preg_match('/Notes: (.*?)$/', $message, $notes);
                    
                    $details = [
                        'media_type' => $type[1] ?? 'N/A',
                        'quantity' => $quantity[1] ?? 'N/A',
                        'supplier_name' => $supplier[1] ?? 'N/A',
                        'estimated_cost' => $cost[1] ?? 'N/A',
                        'additional_notes' => $notes[1] ?? 'N/A',
                    ];
                }
            @endphp
            
            <div class="procurement-details">
                <h3>Request Details:</h3>
                <ul style="list-style-type: disc; margin-left: 20px;">
                    <li><strong>Media Type:</strong> {{ $details['media_type'] ?? 'N/A' }}</li>
                    <li><strong>Quantity:</strong> {{ $details['quantity'] ?? 'N/A' }}</li>
                    <li><strong>Supplier Name:</strong> {{ $details['supplier_name'] ?? 'N/A' }}</li>
                    <li><strong>Estimated Cost:</strong> £{{ $details['estimated_cost'] ?? 'N/A' }}</li>
                    <li><strong>Additional Notes:</strong> {{ $details['additional_notes'] ?? 'N/A' }}</li>
                </ul>
            </div>
            
        
            <div class="action-buttons">
                <form action="{{ route('notifications.accept', $notification->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="action-btn accept-btn">Accept Request</button>
                </form>
        
                <form action="{{ route('notifications.reject', $notification->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="action-btn reject-btn">Reject Request</button>
                </form>
        
                <form action="{{ route('notifications.toggle', $notification->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="action-btn mark-btn">
                        Mark as {{ $notification->status === 'read' ? 'Unread' : 'Read' }}
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
