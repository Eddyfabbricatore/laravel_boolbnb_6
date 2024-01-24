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
        <div class="card">
            <h2>Transaction Details:</h2>
            <ul>
                <li>Amount: {{ $transaction->amount }}</li>
                <li>Status: {{ $transaction->status }}</li>
                <li>Data di transizione: {{ $transaction->createdAt->format('Y-m-d H:i:s') }}</li>
                <!-- Add more transaction details as needed -->
            </ul>
        </div>
        @endisset
    </div>
@endsection
