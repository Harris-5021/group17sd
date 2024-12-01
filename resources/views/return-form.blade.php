<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Return - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('AML.png') }}" alt="AML Logo">
            </a>
        </div>
    </header>

    <main class="dashboard-container">
        <h1>Process Return</h1>
        
        <div class="dashboard-card">
            <form action="{{ route('return.process', $loan->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <p><strong>Title:</strong> {{ $media->title }}</p>
                    <p><strong>Member:</strong> {{ $user->name }}</p>
                </div>

                <div class="form-group">
                    <label for="damage_notes">Damage Notes (if any):</label>
                    <textarea name="damage_notes" id="damage_notes" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="fine_amount">Fine Amount (£):</label>
                    <input type="number" name="fine_amount" id="fine_amount" step="0.01" min="0" value="0">
                </div>

                <button type="submit">Process Return</button>
                <a href="{{ url()->previous() }}">Cancel</a>
            </form>
        </div>
    </main>
</body>
</html>