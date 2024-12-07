<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription details - AML</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/accessibility-toolbar.css') }}">
    <style>
        /* Modal overlay styles */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-overlay:target {
            display: flex;
        }

        /* Modal content styles */
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .modal-buttons button {
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
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
    <body>
<div>
<h1>Subscription details for: "{{ $name }}"</h1>

<table>
<tr>
    <td>Subscription ID</td>
    <td>User ID</td>
    <td>Plan Type</td>
    <td>Amount</td>
    <td>Status</td>
    <td>Start Date</td>
    <td>End Date</td>
    <td>Next Billing Date</td>
    <td>Fee paid</td>
</tr>
<tr>
    @foreach($subscriptions as $sub)
    <td>{{$sub -> id}}</td>
    <td>{{$sub -> user_id}}</td>
    <td>
    {{$sub -> plan_type}}<br>
    <a href="#edit-plan-type-{{$sub->id}}">Edit</a>
    </td>
    <td>
    {{$sub -> amount}}<br>
    </td>
    <td>{{$sub -> status}}</td>
    <td>
    {{$sub -> start_date}}<br>
    <a href ="#edit-start-date-{{$sub->id}}">Edit</a>
    </td>
    <td>{{$sub -> end_date}}</td>
    <td>{{$sub -> next_billing_date}}</td>
    <td>
    {{ $sub->fee_paid == '1' ? 'Y' : 'N' }}<br>
    <a href="#edit-fee-paid-{{$sub->id}}">Edit</a>
    </td>


    <div id="edit-plan-type-{{$sub->id}}" class="modal-overlay">
        <div class="modal-content">
            <h2>Edit Plan Type</h2>
            <form action="{{ route('subscription.updateSubscription', $sub->id)}}" method="POST" >
            @csrf
                <input type="text" name="plan_type" placeholder="Enter new Plan Type">
                <div class="modal-buttons">
                    <button type="submit">Save</button>
                    <button><a href="#">Cancel</a></button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="edit-start-date-{{$sub->id}}" class="modal-overlay">
        <div class="modal-content">
            <h2>Edit Start Date</h2>
            <form action="{{ route('subscription.updateSubscription', $sub->id)}}" method="POST" >
            @csrf
                <input type="date" name="start_date" placeholder="Enter new Start Date">
                <div class="modal-buttons">
                    <button type="submit">Save</button>
                    <button><a href="#">Cancel</a></button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-fee-paid-{{$sub->id}}" class="modal-overlay">
        <div class="modal-content">
            <h2>Edit Fee Paid</h2>
            <form method="POST" action="{{ route('subscription.updateSubscription', $sub->id) }}">
                @csrf
                <select name="fee_paid">
                    <option value="1" {{ $sub->fee_paid == '1' ? 'selected' : '' }}>Y</option>
                    <option value="0" {{ $sub->fee_paid == '0' ? 'selected' : '' }}>N</option>
                </select>
                <button type="submit">Save</button>
                <a href="#" class="cancel-btn">Cancel</a>
            </form>
        </div>
    </div>

    @if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif



</tr>
</table>
<form method="GET" action="{{ route('subscription.showPastPayments', ['id' => $sub->id]) }}">
                        <button class="action-btn" type = "submit"> View Past Payments </button> 
                        </form>
    @endforeach
</div>


</body>
<footer>

</footer>