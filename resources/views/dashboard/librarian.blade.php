<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns Management - AML Librarian Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                    <li><a href="{{ route('returns.processed') }}">Processed Returns</a></li>
                    <li><a href="{{ route('fines') }}">Manage Fines</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('returns.search') }}" method="GET">
                    <input type="text" name="query" placeholder="Search by member or media ID..." value="{{ request('query') }}">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </header>

    <main class="dashboard-container">
        <h1>Welcome, {{ $user->name }}!</h1>
        
        <div class="dashboard-grid">
            <!-- Pending Returns Card -->
            <div class="dashboard-card">
                <h2>Pending Returns</h2>
                <div class="returns-list">
                    @if($pendingReturns->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Media ID</th>
                                    <th>Title</th>
                                    <th>Member</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingReturns as $return)
                                    <tr>
                                        <td>{{ $return->media_id }}</td>
                                        <td>{{ $return->title }}</td>
                                        <td>{{ $return->user_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($return->due_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <button onclick="showProcessModal({{ $return->id }})" class="btn-process">Process Return</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No pending returns</p>
                    @endif
                </div>
            </div>

            <!-- Recent Fines Card -->
            <div class="dashboard-card">
                <h2>Recent Fines</h2>
                <div class="fines-list">
                    @if($recentFines->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Loan ID</th>
                                    <th>Member</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentFines as $fine)
                                    <tr>
                                        <td>{{ $fine->loan_id }}</td>
                                        <td>{{ $fine->user_name }}</td>
                                        <td>£{{ number_format($fine->amount, 2) }}</td>
                                        <td>{{ $fine->reason }}</td>
                                        <td>{{ $fine->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No recent fines</p>
                    @endif
                </div>
            </div>

            <!-- Transfer Requests Card -->
            <div class="dashboard-card">
                <h2>Transfer Requests</h2>
                <div class="transfers-list">
                    @if($transferRequests->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Media Title</th>
                                    <th>From Branch</th>
                                    <th>To Branch</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transferRequests as $transfer)
                                    <tr>
                                        <td>{{ $transfer->media_title }}</td>
                                        <td>{{ $transfer->from_branch_name }}</td>
                                        <td>{{ $transfer->to_branch_name }}</td>
                                        <td>
                                            <span class="status-badge {{ $transfer->status }}">
                                                {{ ucfirst($transfer->status) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($transfer->status == 'pending')
                                                <button onclick="showTransferModal({{ $transfer->id }})" class="btn-process">Process Transfer</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No transfer requests</p>
                    @endif
                </div>
            </div>

            <!-- Damaged Items Card -->
            <div class="dashboard-card">
                <h2>Damaged Items</h2>
                <div class="damaged-list">
                    @if($damagedItems->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Media ID</th>
                                    <th>Title</th>
                                    <th>Damage Notes</th>
                                    <th>Reported Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($damagedItems as $item)
                                    <tr>
                                        <td>{{ $item->media_id }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->damage_notes }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->reported_date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No damaged items reported</p>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Process Return Modal -->
    <div id="processReturnModal" class="modal">
        <div class="modal-content">
            <h2>Process Return</h2>
            <form action="{{ route('returns.process', ['id' => '_ID_']) }}" method="POST" id="processReturnForm">
                @csrf
                <div class="form-group">
                    <label for="damage_notes">Damage Notes (if any):</label>
                    <textarea name="damage_notes" id="damage_notes" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="fine_amount">Fine Amount (£):</label>
                    <input type="number" name="fine_amount" id="fine_amount" step="0.01" min="0" value="0" class="form-control">
                </div>

                <div class="button-group">
                    <button type="submit" name="status" value="returned" class="btn-primary">Process Return</button>
                    <button type="submit" name="status" value="damaged" class="btn-damaged">Mark as Damaged</button>
                    <button type="button" onclick="closeReturnModal()" class="btn-cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Base styles */
        .dashboard-container {
            padding: 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 20px;
        }

        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Button styles */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-process {
            background-color: #4169E1;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #008B8B;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-damaged {
            background-color: #DC3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #6C757D;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
        }

        /* Status badge styles */
        .status-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-badge.approved {
            background-color: #D1FAE5;
            color: #065F46;
        }
    </style>

    <script>
        function showProcessModal(returnId) {
            const modal = document.getElementById('processReturnModal');
            const form = document.getElementById('processReturnForm');
            
            // Update the form action with the correct ID
            form.action = form.action.replace('_ID_', returnId);
            
            modal.style.display = 'block';
            console.log('Opening modal for return ID:', returnId);
        }

        function closeReturnModal() {
            const modal = document.getElementById('processReturnModal');
            const form = document.getElementById('processReturnForm');
            modal.style.display = 'none';
            form.reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('processReturnModal');
            if (event.target === modal) {
                closeReturnModal();
            }
        }
    </script>
</body>
</html>