@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 fw-bold text-gray-800">Edit Gallery Image</h2>
    <a href="{{ route('admin.galleries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Gallery</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4 text-center">
                        <p class="text-muted small mb-2">Current Image</p>
                        <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}" class="img-thumbnail" style="max-height: 200px; object-fit: contain;">
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Upload New Image <small class="text-muted fw-normal">(Optional)</small></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="text-muted">Leave empty if you don't want to change the image. Max 5MB (JPG, PNG, WEBP).</small>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $gallery->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update Gallery Image</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
