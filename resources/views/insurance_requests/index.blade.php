@inject('set', 'App\Http\Controllers\InsuranceRequestController')
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Insurance Requests</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Session ID</th>
                    <th>Phone Number</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($insuranceRequests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->session_id }}</td>
                        <td>{{ $request->phone_number }}</td>
                        <td>{{ $request->state }}</td>
                        <td>
                            <form action="{{ route('admin.insurance-requests.update-state', $request->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <select name="state" class="form-control" onchange="this.form.submit()">
                                    <option value="pending" {{ $request->state === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $request->state === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $request->state === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
