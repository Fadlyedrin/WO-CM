@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Create Package</h2>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card"><div class="card-body">
    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Package Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Price (Rp)</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Upload Images (Select Multiple)</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control ckeditor-field" rows="5"></textarea>
            </div>
            <div class="col-12 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_available" value="1" checked id="statusSwitch">
                    <label class="form-check-label fw-semibold" for="statusSwitch">Available to Public</label>
                </div>
            </div>
        </div>

        {{-- ===== COMPONENT MANAGEMENT (CREATE) ===== --}}
        <hr class="my-4 border-2">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Komponen Layanan (Opsional)</h5>
                    <p class="text-muted small mb-0">Tambahkan rincian layanan langsung di sini, atau lewati dan tambahkan nanti di halaman Edit.</p>
                </div>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="addComponent()">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Komponen
                </button>
            </div>
            
            <div id="components-container"></div>
        </div>

        <button class="btn btn-primary"><i class="bi bi-save"></i> Save Package</button>
    </form>
</div></div>

@push('scripts')
<script>
    let compIndex = 0;
    function addComponent() {
        const html = `
        <div class="card bg-light border-0 shadow-sm mb-3 component-item rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-primary">Komponen #${compIndex + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.component-item').remove()"><i class="bi bi-trash"></i> Hapus</button>
                </div>
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="components[${compIndex}][name]" class="form-control" required placeholder="cth: Fotografer">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Icon Bootstrap</label>
                        <input type="text" name="components[${compIndex}][icon]" class="form-control" value="bi-star">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="components[${compIndex}][price]" class="form-control" required min="0" placeholder="5000000">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Detail Deskripsi (Opsional)</label>
                        <textarea name="components[${compIndex}][description]" class="form-control" id="dyn-editor-${compIndex}" rows="3" placeholder="Rincian layanan..."></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipe</label>
                        <select name="components[${compIndex}][is_optional]" class="form-select">
                            <option value="1">Opsional (bisa dilepas)</option>
                            <option value="0">Wajib (tidak bisa dilepas)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>`;
        
        document.getElementById('components-container').insertAdjacentHTML('beforeend', html);
        
        // Init CKEditor for the newly added textarea
        setTimeout(() => {
            const el = document.getElementById('dyn-editor-' + compIndex);
            if (window.initCKEditor && el) {
                window.initCKEditor(el);
            }
            compIndex++;
        }, 50);
    }
</script>
@endpush

@endsection