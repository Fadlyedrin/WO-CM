@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Upload to Gallery</h2>
    <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card" style="max-width: 600px;"><div class="card-body">
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Upload Image</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Description (Optional)</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <button class="btn btn-primary w-100"><i class="bi bi-cloud-upload"></i> Upload</button>
    </form>
</div></div>
@endsection