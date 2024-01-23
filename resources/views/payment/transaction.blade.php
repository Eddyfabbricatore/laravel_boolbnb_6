@extends('layouts.app')

@section('content')
    <div>
        <h1>Payment Result</h1>

        @if(session('success'))
            <div style="color: green;">
                {{ session('success') }}
            </div>
        @elseif(session('errors'))
            <div style="color: red;">
                {{ session('errors') }}
            </div>
        @endif

        @isset($transaction)
            <h2>Transaction Details:</h2>
            <ul>
                <li>ID: {{ $transaction }}</li>
                <li>Amount: {{ $transaction->amount }}</li>
                <li>Status: {{ $transaction->status }}</li>
                <!-- Add more transaction details as needed -->
            </ul>
        @endisset
    </div>
@endsection
