@extends('layouts.erp')

@section('content')
    <div class="container">

        <div class="mb-3 d-flex justify-content-between">
            <button class="btn btn-secondary ms-2">Deleted Users(Soft Delete)</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-warning ms-2">
                View Users
            </a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->deleted_at }}</td>

                        <td>

                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm">Restore</button>
                            </form>

                            <form action="{{ route('admin.users.forceDelete', $user->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Permanently delete?')">
                                    Delete Forever
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
@endsection
