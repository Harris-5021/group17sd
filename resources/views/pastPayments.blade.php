<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
    </head>
    <header>
        <div class="logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('AML.png') }}" alt="AML Logo">
            </a>
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li><a href="{{ route('dashboard.accountant') }}">User search dashboard</a></li>
                    <li><a href="{{ route('signout') }}">Sign Out</a></li>
                    <li><a href="{{ route('dashboard.accountant') }}">Contact us</a></li>
                </ul>
            </nav>
        </div>
    </header>
<div>
</head>
<body>
<div>
<h1>Previous payments</h1>

<table>
<tr>
    <td>Subscription ID</td>
    <td>User ID</td>
    <td>Plan Type</td>
    <td>Amount</td>
    <td>Date Paid</td>
</tr>
<tr>
    @foreach($payments as $item)
    <td>{{$item->subscription_id}}</td>
    <td>{{$item->user_id}}</td>
    <td>{{$item->plan_type}}</td>
    <td>{{$item->amount}}</td>
    <td>{{$item->date_paid}}</td>
</tr>

@endforeach
</table>
<div>
<button class="action-btn" onclick="history.back()">Go Back</button>
</div>
</body>
