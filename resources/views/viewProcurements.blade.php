<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurements List - AML</title>
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
                    <li><a href="{{ route('dashboard.purchase_manager') }}">Create Procurements</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h1>Procurement Records</h1>

        <table>
    <thead>
        <tr>
            <th>Media Title</th>
            <th>Procurement Date</th>
            <th>Procurement Type</th>
            <th>Supplier Name</th>
            <th>Procurement Cost</th>
            <th>Payment Status</th>
            <th>Update Status</th>
            <th>Branch Location</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($procurements as $procurement)
            <tr>
                <td>{{ $procurement->media->title ?? 'N/A' }}</td>
                <td>{{ $procurement->procurement_date }}</td>
                <td>{{ ucfirst($procurement->procurement_type) }}</td>
                <td>{{ $procurement->supplier_name }}</td>
                <td>{{ $procurement->procurement_cost ? '$' . number_format($procurement->procurement_cost, 2) : 'N/A' }}</td>

                <!-- Payment Status Column -->
                <td>
                    <form action="{{ route('procurement.updateStatus') }}" method="POST">
                        @csrf
                        <input type="hidden" name="procurement_id" value="{{ $procurement->procurement_id }}">

                        <select name="payment_status">
                            <option value="pending" {{ $procurement->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $procurement->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                </td>

                <!-- Update Status Column -->
                <td>
                        <select name="updateStatus">
                            <option value="pending" {{ $procurement->updateStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dispatched" {{ $procurement->updateStatus == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                            <option value="delivering" {{ $procurement->updateStatus == 'delivering' ? 'selected' : '' }}>Delivering</option>
                            <option value="delivered" {{ $procurement->updateStatus == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                </td>

                <td>{{ $procurement->branch_location }}</td>

                <!-- Actions Column: Submit Button -->
                <td>
                        <button type="submit">Update Status</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>




    </main>

    <footer>
        <p>&copy; 2024 AML | All rights reserved.</p>
    </footer>
</body>
</html>
