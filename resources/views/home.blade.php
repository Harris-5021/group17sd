<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About AML</title>
    <style>
        /* Base Styles */
        body {
            margin: 0;
            font-family: system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            background: #f5f7fa;
        }

        /* Header Styles */
        .modern-header {
            background: #008080;
            color: white;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 50px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        nav ul {
            display: flex;
            gap: 1rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Search Styles */
        .search-form {
            display: flex;
            gap: 0.5rem;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 25px;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-button {
            background: #006666;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Main Content Styles */
        .hero-section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .animate-title {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeInUp 0.8s ease forwards;
        }

        .hero-text {
            font-size: 1.1rem;
            color: #34495e;
            max-width: 1000px;
            margin: 0 auto 3rem auto;
            text-align: center;
            line-height: 1.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: #008080;
            margin-bottom: 0.5rem;
        }

        /* Accessibility Toolbar Styles */
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
            background: #008080;
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .toolbar-toggle:hover {
            background: #006666;
        }

        .toolbar-panel {
            position: absolute;
            right: 0;
            top: 55px;
            width: 280px;
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 10000;
            transition: all 0.3s ease;
        }

        .toolbar-panel.hidden {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            pointer-events: none;
        }

        .toolbar-section {
            margin-bottom: 16px;
        }

        .toolbar-section label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .button-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-button {
            padding: 6px 12px;
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toolbar-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 4px;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Theme Modes */
        .high-contrast {
            background: black !important;
            color: white !important;
        }

        .dark-mode {
            background: #1a1a1a !important;
            color: #ffffff !important;
        }

        .dyslexic-font {
            font-family: Arial, sans-serif !important;
            letter-spacing: 0.05em;
            word-spacing: 0.1em;
            line-height: 1.5;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .header-right {
                flex-direction: column;
                gap: 1rem;
            }
            
            .search-input {
                width: 200px;
            }
            
            .hero-section {
                padding: 2rem 1rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="modern-header">
        <div class="logo">
            <img src="{{ asset('AML.png') }}" alt="AML Logo">
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li><a href="{{ route('register-user') }}" class="nav-link">Sign Up</a></li>
                    <li><a href="{{ route('login') }}" class="nav-link">Contact us</a></li>
                </ul>
            </nav>
            <div class="search">
                <form action="{{ route('search') }}" method="GET" class="search-form">
                    <input type="text" name="query" placeholder="Search Media..." class="search-input">
                    <button type="submit" class="search-button">&#128269;</button>
                </form>
            </div>
        </div>
    </header>

    <main class="hero-section">
        <div class="content-wrapper">
            <h1 class="animate-title">About AML</h1>
            
            <p class="hero-text">
                AML is a library system containing a vast collection of media resources, including books, journals, periodicals, CDs, DVDs, games, and multimedia titles. Operating across England through a network of connected branches, AML provides 24/7 online access to its digital services while maintaining physical locations with standard operating hours from 8:30 AM to 5:30 PM Monday through Saturday. With a commitment to accessibility and user convenience, AML serves a growing user base through multiple channels including in-branch services, online platforms, and telephone support. Our centralized system ensures efficient resource sharing between branches, making it easier for members to access materials regardless of their location.
            </p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Vast Collection</h3>
                    <p>Access thousands of books and multimedia resources</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3>24/7 Access</h3>
                    <p>Online services available around the clock</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📍</div>
                    <h3>Multiple Locations</h3>
                    <p>Network of branches across England</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">♿</div>
                    <h3>Accessible</h3>
                    <p>Designed for everyone's needs</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Accessibility Toolbar -->
    <div class="accessibility-toolbar">
        <button id="accessibilityToggle" class="toolbar-toggle">
            Aa
        </button>
        
        <div id="toolbarPanel" class="toolbar-panel hidden">
            <h3>Accessibility Settings</h3>
            
            <div class="toolbar-section">
                <label>Text Size</label>
                <div class="button-group">
                    <button id="decreaseText" class="toolbar-button">A-</button>
                    <span id="fontSizeDisplay">16px</span>
                    <button id="increaseText" class="toolbar-button">A+</button>
                </div>
            </div>

            <div class="toolbar-section">
                <label>Color Contrast</label>
                <select id="contrastSelect" class="toolbar-select">
                    <option value="normal">Normal</option>
                    <option value="high">High Contrast</option>
                    <option value="dark">Dark Mode</option>
                </select>
            </div>

            <div class="toolbar-section">
                <label>Font Style</label>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="boldText"> Bold Text
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" id="dyslexicFont"> Readable Font
                    </label>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug console logs
            console.log('Script loaded');

            // Get elements
            const toggle = document.getElementById('accessibilityToggle');
            const panel = document.getElementById('toolbarPanel');
            const decreaseText = document.getElementById('decreaseText');
            const increaseText = document.getElementById('increaseText');
            const fontSizeDisplay = document.getElementById('fontSizeDisplay');
            const contrastSelect = document.getElementById('contrastSelect');
            const boldText = document.getElementById('boldText');
            const dyslexicFont = document.getElementById('dyslexicFont');

            // Check if elements exist
            if (!toggle || !panel) {
                console.error('Essential elements not found');
                return;
            }

            console.log('Elements found');

            // Load saved settings
            let fontSize = 16;
            try {
                const settings = JSON.parse(localStorage.getItem('accessibilitySettings') || '{}');
                fontSize = settings.fontSize || 16;
                
                if (settings.fontSize) {
                    document.body.style.fontSize = settings.fontSize + 'px';
                    fontSizeDisplay.textContent = settings.fontSize + 'px';
                }
                if (settings.contrast) {
                    contrastSelect.value = settings.contrast;
                    applyContrast(settings.contrast);
                }
                if (settings.bold) {
                    boldText.checked = true;
                    document.body.style.fontWeight = 'bold';
                }
                if (settings.dyslexic) {
                    dyslexicFont.checked = true;
                    document.body.classList.add('dyslexic-font');
                }
            } catch (e) {
                console.error('Error loading settings:', e);
            }

            // Toggle panel visibility
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Toggle clicked');
                panel.classList.toggle('hidden');
            });

            // Text size controls
            decreaseText.addEventListener('click', () => {
                if (fontSize > 12) {
                    fontSize -= 2;
                    updateFontSize();
                }
            });

            increaseText.addEventListener('click', () => {
                if (fontSize < 24) {
                    fontSize += 2;
                    updateFontSize();
                }
            });

            function updateFontSize() {
                document.body.style.fontSize = fontSize + 'px';
                fontSizeDisplay.textContent = fontSize + 'px';
                saveSettings();
            }

            // Contrast controls
            contrastSelect.addEventListener('change', (e) => {
                applyContrast(e.target.value);
                saveSettings();
            });

            function applyContrast(value) {
                document.body.classList.remove('high-contrast', 'dark-mode');
                if (value !== 'normal') {
                    document.body.classList.add(value === 'high' ? 'high-contrast' : 'dark-mode');
                }
            }

            // Bold text
            boldText.addEventListener('change', (e) => {
                document.body.style.fontWeight = e.target.checked ? 'bold' : 'normal';
                saveSettings();
            });

            // Dyslexic font
            dyslexicFont.addEventListener('change', (e) => {
                document.body.classList.toggle('dyslexic-font', e.target.checked);
                saveSettings();
            });

            // Save settings
            function saveSettings() {
                const settings = {
                    fontSize: fontSize,
                    contrast: contrastSelect.value,
                    bold: boldText.checked,
                    dyslexic: dyslexicFont.checked
                };
                localStorage.setItem('accessibilitySettings', JSON.stringify(settings));
            }

            // Close panel when clicking outside
            document.addEventListener('click', (e) => {
                if (!panel.contains(e.target) && !toggle.contains(e.target)) {
                    panel.classList.add('hidden');
                }
            });

            // Feature cards animation
            const cards = document.querySelectorAll('.feature-card');
            
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>