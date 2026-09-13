@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">User Management</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add New User</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">Name</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Email</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Phone</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Role</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 py-3 fw-bold text-dark">{{ $user->name }}</td>
                        <td class="py-3 text-muted"><a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a></td>
                        <td class="py-3 text-muted">{{ $user->phone ?? '-' }}</td>
                        <td class="py-3">
                            @if($user->is_admin)
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium">Admin</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fw-medium text-white">User</span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light text-primary fw-medium me-1" title="Edit User">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger fw-medium" title="Delete User">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <span class="badge bg-light text-muted border px-2 py-1">You</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
