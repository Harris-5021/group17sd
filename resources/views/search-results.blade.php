<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - AML</title>
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
                    <input type="text" name="query" placeholder="Search by member or media ID..." value="{{ $query }}">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </header>

    <main class="dashboard-container">
        <h1>Search Results for "{{ $query }}"</h1>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                @if($returns->count() > 0)
                    <table class="returns-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Member</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                                <tr>
                                    <td>{{ $return->id }}</td>
                                    <td>{{ $return->title }}</td>
                                    <td>{{ $return->user_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($return->due_date)->format('d/m/Y') }}</td>
                                    <td>
                                        <button onclick="showProcessModal({{ $return->id }})" class="btn-process">
                                            Process Return
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No returns found matching your search.</p>
                @endif
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
                    <textarea name="damage_notes" id="damage_notes" rows="3" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="fine_amount">Fine Amount (if applicable):</label>
                    <input type="number" name="fine_amount" id="fine_amount" step="0.01" min="0" class="form-control">
                </div>

                <div class="button-group">
                    <button type="submit" name="status" value="returned" class="btn-approve">Process Return</button>
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
</body>
</html>