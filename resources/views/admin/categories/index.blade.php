@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Manage Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Category</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">Name</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Slug</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Description</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($categories as $cat)
                    <tr>
                        <td class="ps-4 py-3 fw-bold text-dark">{{ $cat->name }}</td>
                        <td class="py-3"><span class="badge bg-light text-secondary border px-2 py-1">{{ $cat->slug }}</span></td>
                        <td class="py-3 text-muted">{{ Str::limit($cat->description, 50) }}</td>
                        <td class="text-end pe-4 py-3">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-light text-primary fw-medium"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger fw-medium" onclick="return confirm('Delete category?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection