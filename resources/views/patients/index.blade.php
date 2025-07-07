@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Patient List</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>DOB</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $index => $patient)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->phone }}</td>
                         <td>{{ $patient->date_of_birth }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
