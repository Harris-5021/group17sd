<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Notification Popup -->
    <div id="notification" class="notification-popup" style="display: none;">
        <span id="notification-message"></span>
        <button onclick="closeNotification()" class="close-button">&times;</button>
    </div>

    <header>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('AML.png') }}" alt="AML Logo">
            </a>
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard.member') }}">Dashboard</a></li>
                    <li><a href="{{ route('browse') }}">Browse Media</a></li>
                    <li><a href="{{ route('wishlist') }}">My Wishlist</a></li>
                    <li><a href="{{ route('borrowed') }}">My Borrowed Items</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1>My Wishlist</h1>
        <p class="subtitle">Drag and drop items to reorder your wishlist by priority.</p>
        
        <button onclick="showRequestModal()" class="request-button">
            Can't find what you're looking for? Request it here!
        </button>

        <div class="wishlist-container" id="wishlist">
            @foreach ($wishlistItems as $item)
                <div class="wishlist-item" data-id="{{ $item->id }}">
                    <h3>{{ $item->title }}</h3>
                    <p class="author">By {{ $item->author }}</p>
                    <p class="type">Type: {{ $item->type }}</p>
                    <p class="priority">Priority: <span class="priority-value">{{ $item->priority ?? 'Not Set' }}</span></p>
                    
                    <div class="notification-toggle">
                        <label class="switch">
                            <input type="checkbox" class="notification-checkbox" data-id="{{ $item->id }}"
                                   {{ $item->notification_preferences ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <span>Notify when available</span>
                    </div>

                    <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-button">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>

        @if($wishlistItems->count() > 0)
            <button id="save-priority" class="save-button">Save Priority</button>
        @endif
    </main>

    <!-- Request Modal -->
    <div id="requestMediaModal" class="modal">
        <div class="modal-content">
            <h2>Request New Media</h2>
            <form id="requestMediaForm">
                @csrf
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" name="title" id="title" required>
                </div>

                <div class="form-group">
                    <label for="author">Author *</label>
                    <input type="text" name="author" id="author" required>
                </div>

                <div class="form-group">
                    <label for="media_type">Media Type *</label>
                    <select name="media_type" id="media_type" required>
                        <option value="Book">Book</option>
                        <option value="DVD">DVD</option>
                        <option value="Magazine">Magazine</option>
                        <option value="E-Book">E-Book</option>
                        <option value="Audio">Audio</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="additional_notes">Additional Notes</label>
                    <textarea name="additional_notes" id="additional_notes" rows="3" 
                        placeholder="Any additional information that might help us source this item..."></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="submit-button">Submit Request</button>
                    <button type="button" onclick="closeRequestModal()" class="cancel-button">Cancel</button>
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

    .subtitle {
        color: #666;
        margin-bottom: 20px;
    }

    .request-button {
        background-color: #4CAF50;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 20px;
        width: auto;
    }

    .wishlist-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .wishlist-item {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .wishlist-item h3 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .author, .type, .priority {
        margin: 5px 0;
        color: #666;
    }

    .notification-toggle {
        display: flex;
        align-items: center;
        margin: 15px 0;
        gap: 10px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 30px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:checked + .slider:before {
        transform: translateX(30px);
    }

    .remove-button {
        background-color: #dc3545;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }

    .save-button {
        background-color: #2196F3;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: block;
        margin: 20px auto;
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
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.5s ease-out;
    }

    .notification-popup.error {
        background-color: #dc3545;
    }

    .close-button {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 0 5px;
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

    /* Modal styles */
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
        padding: 30px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .button-group {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }

    .submit-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .cancel-button {
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    </style>

    <script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize sortable
        const wishlist = document.getElementById('wishlist');
        const sortable = new Sortable(wishlist, {
            animation: 150,
            onEnd: function () {
                updatePriorities();
            }
        });

        // Update priority numbers in the UI
        function updatePriorities() {
            const items = document.querySelectorAll('.wishlist-item');
            items.forEach((item, index) => {
                item.querySelector('.priority-value').textContent = index + 1;
            });
        }

        // Handle notification toggles
        document.querySelectorAll('.notification-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const wishlistId = this.dataset.id;
                const isChecked = this.checked;

                fetch('/wishlist/notifications/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        wishlist_id: wishlistId,
                        notifications_enabled: isChecked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Notification preferences updated!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Failed to update notification preferences', 'error');
                });
            });
        });

        // Save priorities
        document.getElementById('save-priority')?.addEventListener('click', function () {
            const items = document.querySelectorAll('.wishlist-item');
            const order = Array.from(items).map((item, index) => ({
                id: item.dataset.id,
                priority: index + 1
            }));

            fetch("{{ route('wishlist.updatePriority') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Priorities updated successfully!', 'success');
                } else {
                    showNotification('Failed to update priorities', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while saving priorities', 'error');
            });
        });

        // Handle media request form submission
        document.getElementById('requestMediaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            fetch("{{ route('wishlist.requestMedia') }}", {
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
                    showNotification('Media request submitted successfully. Branch managers have been notified.', 'success');
                    closeRequestModal();
                    this.reset();
                } else {
                    showNotification(data.error || 'Failed to submit request. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while submitting the request', 'error');
            });
        });
    });

    function showRequestModal() {
        document.getElementById('requestMediaModal').style.display = 'block';
    }

    function closeRequestModal() {
        document.getElementById('requestMediaModal').style.display = 'none';
        document.getElementById('requestMediaForm').reset();
    }

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notification-message');
        
        notification.className = 'notification-popup ' + type;
        notificationMessage.textContent = message;
        notification.style.display = 'flex';

        // Auto-hide after 5 seconds
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }

    function closeNotification() {
        const notification = document.getElementById('notification');
        notification.style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('requestMediaModal');
        if (event.target == modal) {
            closeRequestModal();
        }
    }
    </script>
</body>
</html>