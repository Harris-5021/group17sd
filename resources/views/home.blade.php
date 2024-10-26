<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Media Library - Your Gateway to Knowledge</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:p-4">Skip to main content</a>

    <!-- Header -->
    <header class="bg-blue-700 text-white shadow-lg">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img src="/api/placeholder/50/50" alt="AML Logo" class="h-12 w-12 rounded">
                <h1 class="text-2xl font-bold">Advanced Media Library</h1>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('test') }}" class="hover:text-blue-200 transition-colors" aria-label="Search media">Search</a>
                <a href="{{ route('test') }}" class="hover:text-blue-200 transition-colors" aria-label="Sign in to your account">Sign In</a>
                <a href="{{ route('test') }}" class="hover:text-blue-200 transition-colors" aria-label="Register for a new account">Register</a>
            </div>
            <button class="md:hidden p-2" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </nav>
    </header>

    <!-- Main Content -->
    <main id="main-content" class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Your Gateway to Knowledge</h2>
            <p class="text-xl text-gray-600 mb-8">Access millions of books, journals, and multimedia resources across England</p>
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <a href="#" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors" aria-label="Start searching our collection">
                    Search Collection
                </a>
                <a href="#" class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 transition-colors" aria-label="Learn more about our services">
                    Learn More
                </a>
            </div>
        </section>

        <!-- Features Grid -->
        <section class="grid md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Online Access</h3>
                <p class="text-gray-600">Browse and borrow from our extensive collection 24/7 from anywhere.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Multiple Formats</h3>
                <p class="text-gray-600">Access books, journals, periodicals, CDs, DVDs, and multimedia titles.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Nationwide Network</h3>
                <p class="text-gray-600">Find resources at branches across England with easy transfers.</p>
            </div>
        </section>

        <!-- Quick Access -->
        <section class="bg-gray-100 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Access</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="#" class="bg-white p-4 rounded shadow hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-blue-600">Check Availability</h3>
                    <p class="text-sm text-gray-600">View real-time media status</p>
                </a>
                <a href="#" class="bg-white p-4 rounded shadow hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-blue-600">My Wishlist</h3>
                    <p class="text-sm text-gray-600">Save items for later</p>
                </a>
                <a href="#" class="bg-white p-4 rounded shadow hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-blue-600">Manage Subscription</h3>
                    <p class="text-sm text-gray-600">View and update your plan</p>
                </a>
                <a href="#" class="bg-white p-4 rounded shadow hover:shadow-md transition-shadow">
                    <h3 class="font-semibold text-blue-600">Return Media</h3>
                    <p class="text-sm text-gray-600">Process your returns</p>
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="container mx-auto px-4 py-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <p>Phone: 0800 123 4567</p>
                    <p>Email: support@aml.co.uk</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Opening Hours</h3>
                    <p>Monday - Saturday: 8:30 AM - 5:30 PM</p>
                    <p>Sunday: Closed</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Accessibility</h3>
                    <p>This website follows WCAG 2.0 guidelines to ensure access for all users.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>