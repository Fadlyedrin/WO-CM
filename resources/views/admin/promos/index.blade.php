@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Promo Codes</h2>
    <a href="{{ route('admin.promos.create') }}" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg"></i> Create Promo</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow mb-4 border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">Code</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Discount</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Uses</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Valid Until</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Status</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($promos as $promo)
                    <tr>
                        <td class="ps-4 py-3 fw-bold text-dark">{{ $promo->code }}</td>
                        <td class="py-3 fw-medium">
                            @if($promo->discount_type === 'percentage')
                                <span class="text-primary">{{ $promo->discount_value }}%</span>
                            @else
                                <span class="text-success">Rp {{ number_format($promo->discount_value, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted">{{ $promo->uses }} / {{ $promo->max_uses ?? '∞' }}</td>
                        <td class="py-3 text-muted">{{ $promo->valid_until ? $promo->valid_until->format('d M Y') : 'No Expiry' }}</td>
                        <td class="py-3">
                            @if($promo->is_active)
                                <span class="badge bg-success rounded-pill px-3 py-2 fw-medium">Active</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <a href="{{ route('admin.promos.edit', $promo) }}" class="btn btn-sm btn-light text-primary fw-medium"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger fw-medium"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($promos->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No promo codes found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
