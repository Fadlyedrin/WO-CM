@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold">Edit Package</h2>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card"><div class="card-body">
    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Package Name</label>
                <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat) <option value="{{ $cat->id }}" {{ $package->category_id==$cat->id ? 'selected':'' }}>{{ $cat->name }}</option> @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Price (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ $package->price }}" required>
            </div>
            <div class="col-md-12 mb-4">
                <label class="form-label fw-semibold">Add More Images</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                <div class="form-text text-muted small mt-1"><i class="bi bi-info-circle"></i> You can select multiple images by holding CTRL (or CMD).</div>
                
                @if($package->images->count() > 0)
                <div class="mt-4 p-3 bg-light rounded-4 border">
                    <label class="form-label fw-semibold text-dark mb-3"><i class="bi bi-images"></i> Current Images (Check to Delete)</label>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($package->images as $img)
                        <div class="position-relative shadow-sm rounded-4 bg-white p-2" style="width: 120px; transition: all 0.2s;">
                            <img src="{{ Storage::url($img->image_path) }}" class="w-100 rounded-3" style="height: 90px; object-fit: cover;">
                            <div class="form-check mt-2 d-flex justify-content-center align-items-center gap-1 mb-0">
                                <input class="form-check-input mt-0" type="checkbox" name="delete_images[]" value="{{ $img->id }}" id="del_{{ $img->id }}" style="cursor: pointer;">
                                <label class="form-check-label text-danger small fw-medium" style="font-size: 0.8rem; cursor: pointer;" for="del_{{ $img->id }}">Delete</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="col-12 mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control ckeditor-field" rows="5">{{ $package->description }}</textarea>
            </div>
            <div class="col-12 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ $package->is_available ? 'checked':'' }} id="statusSwitch">
                    <label class="form-check-label fw-semibold" for="statusSwitch">Available to Public</label>
                </div>
            </div>
        </div>
        <button class="btn btn-primary"><i class="bi bi-save"></i> Update Package</button>
    </form>
</div></div>

{{-- ===== COMPONENT MANAGEMENT ===== --}}
<div class="card mt-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Komponen Layanan (Bawa Vendor Sendiri)</h5>
                <p class="text-muted small mb-0">Tambahkan rincian layanan yang bisa dilepas oleh pelanggan jika mereka punya vendor sendiri — harga akan dikurangi otomatis.</p>
            </div>
        </div>

        @if($package->components->count() > 0)
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle mb-0 border-top">
                <thead style="background-color: #f8f9fa;">
                    <tr>
                        <th class="ps-4 border-0 py-3 text-muted fw-semibold">Icon</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Nama Layanan</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Harga</th>
                        <th class="border-0 py-3 text-muted fw-semibold">Tipe</th>
                        <th class="border-0 py-3 text-muted fw-semibold text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($package->components as $comp)
                    <tr>
                        <td class="ps-4 py-3"><i class="bi {{ $comp->icon }} fs-5 text-primary"></i></td>
                        <td class="py-3">
                            <div class="fw-bold text-dark">{{ $comp->name }}</div>
                            @if($comp->description) <div class="text-muted small">{{ Str::limit(strip_tags($comp->description), 80) }}</div> @endif
                        </td>
                        <td class="py-3 fw-bold text-success">Rp {{ number_format($comp->price, 0, ',', '.') }}</td>
                        <td class="py-3">
                            @if($comp->is_optional)
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium">Opsional</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-medium">Wajib</span>
                            @endif
                        </td>
                        <td class="text-end pe-4 py-3">
                            <button class="btn btn-sm btn-light text-primary fw-medium" 
                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $comp->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.components.destroy', $comp) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus komponen ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger fw-medium"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editModal-{{ $comp->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg rounded-4">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Edit Komponen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.components.update', $comp) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="row g-3">
                                            <div class="col-8">
                                                <label class="form-label fw-semibold">Nama Layanan</label>
                                                <input type="text" name="name" class="form-control" value="{{ $comp->name }}" required>
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label fw-semibold">Icon (Bootstrap)</label>
                                                <input type="text" name="icon" class="form-control" value="{{ $comp->icon }}" placeholder="bi-camera">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Detail Deskripsi (Opsional)</label>
                                                <textarea name="description" class="form-control ckeditor-field" rows="4">{{ $comp->description }}</textarea>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Harga (Rp)</label>
                                                <input type="number" name="price" class="form-control" value="{{ $comp->price }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold">Tipe</label>
                                                <select name="is_optional" class="form-select">
                                                    <option value="1" {{ $comp->is_optional ? 'selected':'' }}>Opsional</option>
                                                    <option value="0" {{ !$comp->is_optional ? 'selected':'' }}>Wajib</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-4">
                                            <button type="submit" class="btn btn-primary flex-fill">Simpan</button>
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-4 text-muted bg-light rounded-4 mb-4">
            <i class="bi bi-puzzle" style="font-size:2rem; opacity:0.4;"></i>
            <p class="mt-2 mb-0 small">Belum ada komponen layanan. Tambahkan di bawah ini.</p>
        </div>
        @endif

        <div class="p-4 border rounded-4 bg-light">
            <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle text-success me-1"></i> Tambah Komponen Baru</h6>
            <form action="{{ route('admin.packages.components.store', $package) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="cth: Fotografer & Videografer" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Icon Bootstrap</label>
                        <input type="text" name="icon" class="form-control" value="bi-star" placeholder="bi-camera">
                        <div class="form-text"><a href="https://icons.getbootstrap.com" target="_blank">Cari icon →</a></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Harga Komponen (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" placeholder="5000000" required min="0">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Detail Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control ckeditor-field" rows="4" placeholder="Ketik rincian lengkap di sini..."></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tipe</label>
                        <select name="is_optional" class="form-select">
                            <option value="1">Opsional (bisa dilepas)</option>
                            <option value="0">Wajib (tidak bisa dilepas)</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i> Tambah Komponen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection