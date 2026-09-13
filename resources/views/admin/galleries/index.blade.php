@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Manage Gallery</h2>
    <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Upload Image</a>
</div>

<div class="row g-4">
    @forelse($galleries as $img)
    <div class="col-md-3">
        <div class="card h-100 shadow-sm border-0">
            <img src="{{ Storage::url($img->image_path) }}" class="card-img-top" style="height:200px; object-fit:cover;">
            <div class="card-body">
                <h6 class="fw-bold mb-1">{{ $img->title }}</h6>
                <p class="text-muted small mb-3">{{ Str::limit($img->description, 50) }}</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.galleries.edit', $img) }}" class="btn btn-sm btn-outline-primary flex-fill" title="Edit Image">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.galleries.destroy', $img) }}" method="POST" class="flex-fill m-0 p-0 d-flex">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Delete this image?')" title="Delete Image">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="alert alert-light text-center py-5 text-muted border">No images uploaded yet.</div></div>
    @endforelse
</div>
@endsection