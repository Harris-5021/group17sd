<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('AML.png') }}" alt="AML Logo">
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register-user') }}">Sign Up</a></li>
                    <li><a href="{{ route('test') }}">Contact us</a></li>
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
    
    
    <main>
        <h1>About AML</h1>
        <p>AML is a library system containing...</p>
    </main>
    
    <!-- Add this HTML to your pages -->
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

<!-- Accessibility Bar -->
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
    </div>
</div>

<style>
/* Toolbar Styles */
.accessibility-toolbar {
    position: fixed;
    right: 20px;
    top: 100px;
    z-index: 9999;
}

.toolbar-toggle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #008B8B;  /* Changed to match your teal theme */
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.toolbar-panel {
    position: absolute;
    right: 0;
    top: 55px;
    width: 250px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.toolbar-panel.hidden {
    display: none;
}

.toolbar-section {
    margin-bottom: 15px;
}

.toolbar-section label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.button-group {
    display: flex;
    gap: 5px;
}

.button-group button,
.toolbar-section button {
    padding: 8px 12px;
    background: #f0f0f0;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

/* High Contrast Mode */
.high-contrast {
    background: black !important;
    color: white !important;
}

.high-contrast button,
.high-contrast input {
    background: white !important;
    color: black !important;
    border: 2px solid white !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('accessibilityToggle');
    const panel = document.getElementById('toolbarPanel');
    const increaseBtn = document.getElementById('increaseText');
    const decreaseBtn = document.getElementById('decreaseText');
    const contrastBtn = document.getElementById('toggleContrast');
    
    let currentSize = 100;

    toggle.addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });

    increaseBtn.addEventListener('click', () => {
        if (currentSize < 150) {
            currentSize += 10;
            document.body.style.zoom = currentSize + '%';
        }
    });

    decreaseBtn.addEventListener('click', () => {
        if (currentSize > 70) {
            currentSize -= 10;
            document.body.style.zoom = currentSize + '%';
        }
    });

    contrastBtn.addEventListener('click', () => {
        document.body.classList.toggle('high-contrast');
    });

    // Close panel when clicking outside
    document.addEventListener('click', (e) => {
        if (!panel.contains(e.target) && !toggle.contains(e.target)) {
            panel.classList.add('hidden');
        }
    });
});
</script>
</body>
</html>
