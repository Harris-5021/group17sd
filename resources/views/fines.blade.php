<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fines - AML</title>
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
                    <li><a href="{{ route('returns.processed') }}">Processed Returns</a></li>
                    <li><a href="{{ route('fines') }}">Manage Fines</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="dashboard-container">
        <h1>Manage Fines</h1>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                @if($fines->count() > 0)
                    <table class="fines-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Member</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Paid Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fines as $fine)
                                <tr>
                                    <td>{{ $fine->id }}</td>
                                    <td>{{ $fine->user_name }}</td>
                                    <td>{{ $fine->title }}</td>
                                    <td>£{{ number_format($fine->amount, 2) }}</td>
                                    <td>{{ ucfirst($fine->reason) }}</td>
                                    <td>
                                        <span class="status-badge {{ $fine->status }}">
                                            {{ ucfirst($fine->status) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($fine->due_date)->format('d/m/Y') }}</td>
                                    <td>{{ $fine->paid_date ? \Carbon\Carbon::parse($fine->paid_date)->format('d/m/Y') : 'Not paid' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination">
                        {{ $fines->links() }}
                    </div>
                @else
                    <p>No fines found.</p>
                @endif
            </div>
        </div>
    </main>

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
</body>
</html>
