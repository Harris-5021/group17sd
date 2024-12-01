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
    <style>
        .wishlist-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .wishlist-item {
            width: 200px;
            padding: 1rem;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            cursor: grab;
        }
        .wishlist-item.dragging {
            opacity: 0.5;
        }
        .wishlist-item h3 {
            margin: 0 0 0.5rem;
            font-size: 1.2rem;
        }
        .wishlist-item p {
            margin: 0.3rem 0;
        }
        .save-priority {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .save-priority:hover {
            background-color: #0056b3;
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
    <p>Drag and drop items to reorder your wishlist by priority.</p>
    <div class="wishlist-container" id="wishlist">
        @foreach ($wishlistItems as $item)
            <div class="wishlist-item" data-id="{{ $item->id }}">
                <h3>{{ $item->title }}</h3>
                <p>By {{ $item->author }}</p>
                <p>Type: {{ $item->type }}</p>
                <p>Priority: <span class="priority-value">{{ $item->priority ?? 'Not Set' }}</span></p>
                <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" style="margin-top: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="remove-btn">Remove</button>
                </form>
            </div>
        @endforeach
    </div>
    <button id="save-priority" class="save-priority">Save Priority</button>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize sortable
        const wishlist = document.getElementById('wishlist');
        const sortable = new Sortable(wishlist, {
            animation: 150,
            onEnd: function () {
                updatePriorities(); // Update priorities after reordering
            }
        });

        // Update priority numbers in the UI
        function updatePriorities() {
            const items = document.querySelectorAll('.wishlist-item');
            items.forEach((item, index) => {
                item.querySelector('.priority-value').textContent = index + 1;
            });
        }

        // Save priorities to the server
        document.getElementById('save-priority').addEventListener('click', function () {
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
                body: JSON.stringify({ order }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Priority updated successfully!');
                    } else {
                        alert('Failed to update priority.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving priority.');
                });
        });
    });
</script>

</body>
</html>
