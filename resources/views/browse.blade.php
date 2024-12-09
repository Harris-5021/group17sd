<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Media - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <form action="{{ route('search') }}" method="GET">
                    <input type="text" name="query" placeholder="Search Media...">
                    <button type="submit">&#128269;</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
    <h1>Browse Media</h1>
    <div class="media-grid">
        @foreach($media as $item)
            <div class="media-card" onclick="toggleDetails(event, this)">
                <h2>{{ $item->title }}</h2>
                <p>By {{ $item->author }}</p>
                <p>Type: {{ $item->type }}</p>
                <p>Published: {{ $item->publication_year }}</p>
    
                <!-- Hidden extra details that will expand -->
                <div class="media-card-details" style="display: none;">
                    <p>Description: {{ $item->description ?? 'No description available.' }}</p>
                    <p>Additional Info: {{ $item->additional_info ?? 'N/A' }}</p>
                    <p id="copies-available-{{ $item->id }}" class="quantity">Copies available: Select a branch</p>
                
                    @if($item->status === 'available')
                        <!-- Borrow Form -->
                        <form action="{{ route('borrow', $item->id) }}" method="POST" onsubmit="event.stopPropagation();" id="borrow-form-{{ $item->id }}">
                            @csrf
                            <select name="branch_id" required class="branch-select" data-media-id="{{ $item->id }}">
                                <option value="">Select Branch</option>
                                @foreach(DB::table('branches')->get() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="borrow-btn">Borrow</button>
                        </form>

                        <!-- Wishlist Form -->
                        <form action="{{ route('wishlist.add', $item->id) }}" method="POST" onsubmit="event.stopPropagation();" id="wishlist-form-{{ $item->id }}" style="display: none;">
                            @csrf
                            <button type="submit" class="wishlist-btn">Add to Wishlist</button>
                        </form>

                        <!-- Delivery Form -->
                        <form action="{{ route('delivery.request', $item->id) }}" method="POST" onsubmit="event.stopPropagation();" id="delivery-form-{{ $item->id }}">
    @csrf
    <h3>Request Delivery</h3>
    <label for="address">Delivery Address:</label>
    <input type="text" name="address" id="address-{{ $item->id }}" required placeholder="Enter your delivery address">

    <label for="delivery_date">Preferred Delivery Date:</label>
    <input type="date" name="delivery_date" id="delivery_date-{{ $item->id }}" required>

    <!-- Hidden branch_id field -->
    <input type="hidden" name="branch_id" id="delivery-branch-{{ $item->id }}">

    <button type="submit" class="delivery-btn">Request Delivery</button>
</form>

                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{ $media->links() }}
</main>

<script>
   function toggleDetails(event, card) {
        const details = card.querySelector('.media-card-details');
        const isVisible = details.style.display === 'block';

        // If the details are already visible and the click was on a button/form, stop propagation to prevent collapsing
        if (isVisible && (event.target.closest('button') || event.target.closest('form'))) {
            event.stopPropagation();
            return; // Do nothing if the click is on a button or form when expanded
        }

        // Toggle visibility based on the current state
        if (isVisible) {
            details.style.display = 'none';  // Collapse the card
        } else {
            details.style.display = 'block'; // Expand the card
        }
    }
</script>

<script src="{{ asset('js/accessibility-toolbar.js') }}"></script>
<script>
$(document).ready(function() {
    $('.branch-select').change(function() {
        var branchId = $(this).val();
        var mediaId = $(this).data('media-id');

        // Update the hidden branch_id field in the delivery form
        $(`#delivery-branch-${mediaId}`).val(branchId);

        if(branchId) {
            $.ajax({
                url: `/media/inventory/${mediaId}/${branchId}`,
                method: 'GET',
                success: function(data) {
                    $(`#copies-available-${mediaId}`).text('Copies available: ' + data.quantity);
                    
                    // Show/hide buttons based on quantity
                    if (data.quantity == 0) {
                        $(`#borrow-form-${mediaId} .borrow-btn`).hide();
                        $(`#wishlist-form-${mediaId}`).show();
                    } else {
                        $(`#borrow-form-${mediaId} .borrow-btn`).show();
                        $(`#wishlist-form-${mediaId}`).hide();
                    }
                    // Always keep the branch select visible
                    $(`#borrow-form-${mediaId} .branch-select`).show();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $(`#copies-available-${mediaId}`).text('Error loading quantity');
                }
            });
        } else {
            $(`#copies-available-${mediaId}`).text('Copies available: Select a branch');
            $(`#borrow-form-${mediaId} .borrow-btn`).show();
            $(`#wishlist-form-${mediaId}`).hide();
        }
    });
});

</script>

</body>
</html>
