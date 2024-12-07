<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Borrowed Items - AML</title>
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
                    <li><a href="{{ route('dashboard.member') }}">My Account Dashboard</a></li>
                    <li><a href="{{ route('browse') }}">Browse Media</a></li>
                    <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                    <li><a href="{{ route('borrowed') }}">My Borrowed Items</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('search') }}" method="GET" class="search">
                    <input type="text" name="query" placeholder="Search Media..." value="{{ request('query') }}">
                    <button type="submit">&#128269;</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <h1>My Borrowed Items</h1>
        
        <!-- Notification popup for returns -->
        <div id="returnNotification" class="notification-popup" style="display: none;">
            <span id="notificationMessage"></span>
            <button onclick="closeNotification()" class="close-btn">&times;</button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="media-grid">
            @if($activeLoans->count() > 0)
                @foreach ($activeLoans as $loan)
                    <div class="media-card">
                        <h2>{{ $loan->title ?? 'Unknown Title' }}</h2>
                        <p>By {{ $loan->author ?? 'Unknown Author' }}</p>
                        <p>Borrowed: {{ \Carbon\Carbon::parse($loan->borrowed_date)->format('d/m/Y') }}</p>
                        <p>Due: {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</p>
                        <p>Original Branch: {{ $loan->branch_name }}</p>
                
                        <button onclick="showReturnModal({{ $loan->loan_id }})" class="return-btn">Return Book</button>
                    </div>
                @endforeach
            @else
                <p>You haven't borrowed any items yet.</p>
            @endif
        </div>
    </main>

    <!-- Return Modal -->
    <div id="returnModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Return Media Item</h2>
            <form id="returnMediaForm">
                @csrf
                <input type="hidden" id="loan_id" name="loan_id">
                <div class="form-group">
                    <label for="branch_id">Select Return Branch:</label>
                    <select name="branch_id" id="branch_id" class="form-control" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-button">Return Item</button>
                    <button type="button" onclick="closeReturnModal()" class="cancel-button">Cancel</button>
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

    <style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .media-card {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .media-card h2 {
        margin-top: 0;
        color: #333;
    }

    .return-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }

    .return-btn:hover {
        background-color: #45a049;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .notification-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background-color: #4CAF50;
        color: white;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
        animation: slideIn 0.5s ease-out;
    }

    .notification-popup.error {
        background-color: #dc3545;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .button-group {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .submit-button, .cancel-button {
        padding: 8px 16px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .submit-button {
        background-color: #4CAF50;
        color: white;
    }

    .cancel-button {
        background-color: #6c757d;
        color: white;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        float: right;
        margin-left: 10px;
    }

    .alert {
        padding: 15px;
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
    </style>

    <script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
    <script>
    function showReturnModal(loanId) {
        document.getElementById('loan_id').value = loanId;
        document.getElementById('returnModal').style.display = 'block';
    }

    function closeReturnModal() {
        document.getElementById('returnModal').style.display = 'none';
    }

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('returnNotification');
        const notificationMessage = document.getElementById('notificationMessage');
        notification.className = `notification-popup ${type}`;
        notificationMessage.textContent = message;
        notification.style.display = 'block';
        
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }

    function closeNotification() {
        document.getElementById('returnNotification').style.display = 'none';
    }

    document.getElementById('returnMediaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const loanId = document.getElementById('loan_id').value;
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            if (key !== '_token') {
                data[key] = value;
            }
        });

        fetch(`/return/${loanId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message);
                closeReturnModal();
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else {
                throw new Error(data.error || 'An error occurred during return');
            }
        })
        .catch(error => {
            showNotification(error.message, 'error');
        });
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('returnModal');
        if (event.target == modal) {
            closeReturnModal();
        }
    }
    </script>
</body>
</html>