<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns Management - AML Librarian Dashboard</title>
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
                    <li><a href="{{ route('returns.processed') }}">Processed Returns</a></li>
                    <li><a href="{{ route('fines') }}">Manage Fines</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('returns.search') }}" method="GET" class="search">
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
                        <table class="returns-table">
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
                        <table class="fines-table">
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

            <!-- Damaged Items Card -->
            <div class="dashboard-card">
                <h2>Damaged Items</h2>
                <div class="damaged-list">
                    @if($damagedItems->count() > 0)
                        <table class="damaged-table">
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
    <div id="processReturnModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Process Return</h2>
            <form action="{{ route('returns.process') }}" method="POST">
                @csrf
                <input type="hidden" name="return_id" id="return_id">
                
                <div class="form-group">
                    <label for="damage_notes">Damage Notes (if any):</label>
                    <textarea name="damage_notes" id="damage_notes" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="fine_amount">Fine Amount (if applicable):</label>
                    <input type="number" name="fine_amount" id="fine_amount" step="0.01" min="0">
                </div>

                <div class="button-group">
                    <button type="submit" name="status" value="approved" class="btn-approve">Process Return</button>
                    <button type="submit" name="status" value="damaged" class="btn-damaged">Mark as Damaged</button>
                    <button type="button" onclick="closeModal()" class="btn-cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

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

    <script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
    <script>
        function showProcessModal(returnId) {
            document.getElementById('return_id').value = returnId;
            document.getElementById('processReturnModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('processReturnModal').style.display = 'none';
        }
    </script>
    <!-- Add this modal HTML at the bottom of your librarian.blade.php before the closing body tag -->
<div id="processReturnModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Process Return</h2>
        <form action="{{ route('return.process') }}" method="POST" id="processReturnForm">
            @csrf
            <input type="hidden" name="loan_id" id="loan_id">
            
            <div class="form-group">
                <label for="damage_notes">Damage Notes (if any):</label>
                <textarea name="damage_notes" id="damage_notes" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="fine_amount">Fine Amount (£):</label>
                <input type="number" name="fine_amount" id="fine_amount" step="0.01" min="0" value="0" class="form-control">
            </div>

            <div class="button-group">
                <button type="submit" class="btn-primary">Process Return</button>
                <button type="button" onclick="closeModal()" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Add this CSS to style the modal -->
<style>
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
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.button-group {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

/* Style status badges */
.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.875rem;
}

.status-badge.returned {
    background-color: #d1fae5;
    color: #065f46;
}

.status-badge.damaged {
    background-color: #fee2e2;
    color: #991b1b;
}
</style>

<!-- Add this JavaScript -->
<script>
function showProcessModal(loanId) {
    document.getElementById('loan_id').value = loanId;
    document.getElementById('processReturnModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('processReturnModal').style.display = 'none';
    document.getElementById('processReturnForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    let modal = document.getElementById('processReturnModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>