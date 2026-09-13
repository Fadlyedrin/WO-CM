@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Manage Packages</h2>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Package</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">Package Name</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Category</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Price</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Status</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($packages as $pkg)
                    <tr>
                        <td class="ps-4 py-3 fw-bold text-dark">{{ $pkg->name }}</td>
                        <td class="py-3 text-muted fw-medium">{{ $pkg->category->name }}</td>
                        <td class="py-3 fw-bold text-dark">Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
                        <td class="py-3">
                            @if($pkg->is_available) <span class="badge bg-success rounded-pill px-3 py-2 fw-medium">Available</span>
                            @else <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium">Hidden</span> @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <a href="{{ route('admin.packages.edit', $pkg) }}" class="btn btn-sm btn-light text-primary fw-medium"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.packages.destroy', $pkg) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger fw-medium" onclick="return confirm('Delete this package?')"><i class="bi bi-trash"></i></button>
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