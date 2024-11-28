<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processed Returns - AML</title>
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
                    <li><a href="{{ route('browse') }}">Browse Media</a></li>
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
        <h1>Processed Returns</h1>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                @if($processedReturns->count() > 0)
                    <table class="returns-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Member</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Damage Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($processedReturns as $return)
                                <tr>
                                    <td>{{ $return->id }}</td>
                                    <td>{{ $return->title }}</td>
                                    <td>{{ $return->user_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($return->returned_date)->format('d/m/Y') }}</td>
                                    <td>{{ $return->status }}</td>
                                    <td>{{ $return->damaged_notes ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $processedReturns->links() }}
                @else
                    <p>No processed returns found.</p>
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