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
            <div class="media-card">
                <h2>{{ $item->title }}</h2>
                <p>By: {{ $item->author }}</p>
                <p>Type: {{ $item->type }}</p>
                <p>Published: {{ $item->publication_year }}</p>
                <button class="details-toggle" onclick="toggleDetails(this)">More Details</button>

                <!-- Hidden details section -->
                <div class="media-card-details">
                    <p>Description: {{ $item->description ?? 'No description available.' }}</p>
                    <p>Additional Info: {{ $item->additional_info ?? 'N/A' }}</p>
                    <p id="copies-available-{{ $item->id }}" class="quantity">Copies available: Select a branch</p>

                    @if($item->status === 'available')
                        <div class="action-sections">
                            <!-- Pickup Media Section -->
                            <div class="action-section">
                                <h3>Pickup Media</h3>
                                <form action="{{ route('borrow', $item->id) }}" method="POST" id="borrow-form-{{ $item->id }}">
                                    @csrf
                                    <select name="branch_id" required class="branch-select" data-media-id="{{ $item->id }}">
                                        <option value="">Select Branch</option>
                                        @foreach(DB::table('branches')->get() as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="borrow-btn">Borrow</button>
                                </form>

                                <form action="{{ route('wishlist.add', $item->id) }}" method="POST" id="wishlist-form-{{ $item->id }}">
                                    @csrf
                                    <button type="submit" class="wishlist-btn">Add to Wishlist</button>
                                </form>
                            </div>

                            <!-- Delivery Section -->
                            <div class="action-section">
                                <h3>Request Delivery</h3>
                                <form action="{{ route('delivery.request', $item->id) }}" method="POST" id="delivery-form-{{ $item->id }}">
                                    @csrf
                                    <label for="address-{{ $item->id }}">Delivery Address:</label>
                                    <input type="text" name="address" id="address-{{ $item->id }}" required placeholder="Enter your delivery address">

                                    <label for="delivery_date-{{ $item->id }}">Preferred Delivery Date:</label>
                                    <input type="date" name="delivery_date" id="delivery_date-{{ $item->id }}" required>

                                    <input type="hidden" name="branch_id" id="delivery-branch-{{ $item->id }}">
                                    <button type="submit" class="delivery-btn">Request Delivery</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{ $media->links() }}
</main>

<script>
    function toggleDetails(button) {
        const details = button.nextElementSibling;
        const isVisible = details.style.display === 'block';
        details.style.display = isVisible ? 'none' : 'block';
        button.textContent = isVisible ? 'More Details' : 'Less Details';
    }

    $(document).ready(function () {
        $('.branch-select').change(function () {
            const branchId = $(this).val();
            const mediaId = $(this).data('media-id');
            $(`#delivery-branch-${mediaId}`).val(branchId);

            if (branchId) {
                $.ajax({
                    url: `/media/inventory/${mediaId}/${branchId}`,
                    method: 'GET',
                    success: function (data) {
                        $(`#copies-available-${mediaId}`).text('Copies available: ' + data.quantity);

                        const borrowBtn = $(`#borrow-form-${mediaId} .borrow-btn`);
                        const wishlistBtn = $(`#wishlist-form-${mediaId} .wishlist-btn`);

                        if (data.quantity === 0) {
                            borrowBtn.hide();
                            wishlistBtn.show();
                        } else {
                            borrowBtn.show();
                            wishlistBtn.hide();
                        }
                    },
                    error: function () {
                        $(`#copies-available-${mediaId}`).text('Error loading quantity');
                    }
                });
            } else {
                $(`#copies-available-${mediaId}`).text('Copies available: Select a branch');
            }
        });
    });
</script>

</body>
</html>
