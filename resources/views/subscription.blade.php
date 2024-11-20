<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription details - AML</title>
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
                    <li><a href="{{ route('test') }}">Contact us</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <body>
<div>
<h1>Subscription details for: "{{ $name }}"</h1>

<table>]
<tr>
    <td>Subscription ID</td>
    <td>User ID</td>
    <td>Plan Type</td>
    <td>Amount</td>
    <td>Status</td>
    <td>Start Date</td>
    <td>End Date</td>
    <td>Next Billing Date</td>
</tr>
<tr>
    @foreach($subscriptions as $item)
    <td>{{$item -> id}}</td>

    @endforeach
</tr>

</table>

</div>

</body>