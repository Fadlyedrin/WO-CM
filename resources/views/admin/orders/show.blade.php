@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 fw-bold">Detail Pesanan #WO-{{ $order->id }}</h2>
            <p class="text-muted small mb-0 mt-1">Dibuat: {{ $order->created_at->translatedFormat('l, d F Y — H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>
            Kembali</a>
    </div>

    <div class="row g-4">

        {{-- ===== KOLOM KIRI: Info Klien + Event ===== --}}
        <div class="col-lg-8">

            {{-- Informasi Klien --}}
            <div class="card border-0 mb-4" style="border-radius:16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                <div class="card-header bg-white py-3"
                    style="border-radius:16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                    <h6 class="m-0 fw-bold"><i class="bi bi-person-circle text-primary me-2"></i>Informasi Klien</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">Nama Lengkap</div>
                            <div class="fw-bold">{{ $order->user->name }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">Email</div>
                            <div>{{ $order->user->email }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small mb-1">No. Telepon / WhatsApp</div>
                            <div class="d-flex align-items-center gap-2">
                                <span>{{ $order->user->phone ?? 'Tidak disertakan' }}</span>
                                @if ($order->user->phone)
                                    @php
                                        $waNumber = preg_replace(
                                            '/^0/',
                                            '62',
                                            preg_replace('/\D/', '', $order->user->phone),
                                        );
                                    @endphp
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank"
                                        class="btn btn-sm btn-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width:28px; height:28px;" title="Chat via WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Acara --}}
            <div class="card border-0 mb-4" style="border-radius:16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                <div class="card-header bg-white py-3"
                    style="border-radius:16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                    <h6 class="m-0 fw-bold"><i class="bi bi-calendar-event text-success me-2"></i>Detail Acara</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Tanggal Pernikahan</div>
                            <div class="fw-bold text-primary fs-6">
                                {{ \Carbon\Carbon::parse($order->wedding_date)->translatedFormat('l, d F Y') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Waktu Acara</div>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }} –
                                {{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }} WIB</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Nama Venue</div>
                            <div class="fw-bold">{{ $order->venue_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Alamat Venue</div>
                            <div>{{ $order->venue_address }}</div>
                        </div>
                        @if ($order->notes)
                            <div class="col-12">
                                <div class="text-muted small mb-1">Catatan Klien</div>
                                <div
                                    class="p-3 bg-light rounded-3 border-start border-4 border-primary fst-italic text-secondary">
                                    {!! nl2br(e($order->notes)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Vendor Sendiri / Komponen Ditiadakan --}}
            @if ($order->excluded_components && count($order->excluded_components) > 0)
                @php
                    $excludedComps = \App\Models\PackageComponent::whereIn('id', $order->excluded_components)->get();
                @endphp
                <div class="card border-0 mb-4"
                    style="border-radius:16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border-left: 4px solid #f59e0b !important;">
                    <div class="card-header bg-white py-3"
                        style="border-radius:16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                        <h6 class="m-0 fw-bold"><i class="bi bi-scissors text-warning me-2"></i>Klien Bawa Vendor Sendiri
                        </h6>
                        <p class="text-muted small mb-0 mt-1">Layanan berikut tidak disediakan oleh sanggar karena klien
                            menggunakan vendor sendiri.</p>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0 align-middle">
                            <thead style="background:#fef9f0;">
                                <tr>
                                    <th class="ps-4 py-3 fw-semibold text-muted" style="font-size:0.8rem;">Layanan</th>
                                    <th class="py-3 fw-semibold text-muted" style="font-size:0.8rem;">Potongan Harga</th>
                                    <th class="py-3 fw-semibold text-muted" style="font-size:0.8rem;">Nama Vendor Klien</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($excludedComps as $comp)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <i class="bi {{ $comp->icon }} text-warning me-2"></i>
                                            <span class="fw-semibold">{{ $comp->name }}</span>
                                        </td>
                                        <td class="py-3 text-danger fw-bold">
                                            - Rp {{ number_format($comp->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-muted">
                                            @if ($order->vendor_notes && isset($order->vendor_notes[$comp->id]) && $order->vendor_notes[$comp->id])
                                                <span class="badge bg-light text-dark border">
                                                    <i
                                                        class="bi bi-building me-1"></i>{{ $order->vendor_notes[$comp->id] }}
                                                </span>
                                            @else
                                                <span class="text-muted fst-italic" style="font-size:0.82rem;">Tidak
                                                    dicantumkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background:#fef9f0;">
                                <tr>
                                    <td class="ps-4 py-3 fw-bold text-warning">Total Penghematan</td>
                                    <td class="py-3 fw-bold text-warning fs-6">- Rp
                                        {{ number_format($order->component_discount ?? 0, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        {{-- ===== KOLOM KANAN: Status & Pembayaran ===== --}}
        <div class="col-lg-4">

            {{-- Status Card --}}
            <div class="card border-0 mb-4" style="border-radius:16px; box-shadow: 0 2px 12px rgba(0,0,0,0.06);">
                <div class="card-header bg-white py-3"
                    style="border-radius:16px 16px 0 0; border-bottom: 1px solid #f0f0f0;">
                    <h6 class="m-0 fw-bold"><i class="bi bi-receipt text-primary me-2"></i>Tagihan & Pembayaran</h6>
                </div>
                <div class="card-body">

                    {{-- Status badge --}}
                    <div class="mb-4 text-center py-2 rounded-3" style="background:#f8f9fc;">
                        @if ($order->computed_status == 'pending')
                            <span class="badge bg-warning text-dark fs-6 px-4 py-2">⏳ Menunggu Pembayaran</span>
                        @elseif($order->computed_status == 'paid')
                            <span class="badge bg-success fs-6 px-4 py-2">✅ Lunas</span>
                        @elseif($order->computed_status == 'completed')
                            <span class="badge bg-info fs-6 px-4 py-2">🎉 Selesai</span>
                        @else
                            <span class="badge bg-danger fs-6 px-4 py-2">❌ Dibatalkan</span>
                        @endif
                    </div>

                    {{-- REFUND INFO (jika dibatalkan setelah ada pembayaran) --}}
                    @if ($order->status === 'cancelled' && $order->paid_amount > 0)
                        <div class="mb-4 p-3 rounded-3"
                            style="background: linear-gradient(135deg,#fef9f0,#fef3e2); border: 1px solid #f59e0b;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-cash-coin text-warning fs-5"></i>
                                <span class="fw-bold text-warning">Informasi Pengembalian Dana</span>
                            </div>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Dana yang Sudah Dibayar</span>
                                    <span class="fw-bold">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Refund (10% sesuai kontrak)</span>
                                    <span class="fw-bold text-success">Rp
                                        {{ number_format($order->refund_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                    <span class="text-muted">Dana Ditahan (Penalti)</span>
                                    <span class="fw-bold text-danger">Rp
                                        {{ number_format($order->paid_amount - $order->refund_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @if ($order->refunded_at)
                                <div class="mt-2 pt-2 border-top">
                                    <span class="text-muted" style="font-size:0.78rem;">
                                        <i class="bi bi-clock me-1"></i>Diproses:
                                        {{ $order->refunded_at->translatedFormat('d F Y, H:i') }}
                                    </span>
                                </div>
                            @endif
                            @if ($order->cancellation_notes)
                                <div class="mt-2 p-2 bg-white rounded-2">
                                    <span class="text-muted" style="font-size:0.8rem;"><i
                                            class="bi bi-chat-text me-1"></i>{{ $order->cancellation_notes }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Paket --}}
                    <div class="mb-3 p-3 rounded-3" style="background:#f8f9fc;">
                        <div class="text-muted small mb-1">Paket Dipilih</div>
                        <div class="fw-bold">{{ $order->package->name }}</div>
                        <div class="small text-muted">Harga dasar: Rp
                            {{ number_format($order->package->price, 0, ',', '.') }}</div>
                    </div>

                    {{-- Breakdown Harga --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small">Harga Dasar Paket</span>
                            <span class="fw-medium">Rp {{ number_format($order->package->price, 0, ',', '.') }}</span>
                        </div>

                        @if ($order->component_discount > 0)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-warning small"><i class="bi bi-scissors me-1"></i>Vendor Sendiri</span>
                                <span class="text-warning fw-bold">- Rp
                                    {{ number_format($order->component_discount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        @if ($order->discount_amount > 0)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-success small"><i class="bi bi-tag me-1"></i>Kode Promo
                                    {{ $order->promo->code ?? '' }}</span>
                                <span class="text-success fw-bold">- Rp
                                    {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between py-3 mt-1"
                            style="background: linear-gradient(135deg,#f0f9ff,#e0f2fe); border-radius:10px; padding-left:0.75rem; padding-right:0.75rem;">
                            <span class="fw-bold fs-6">Total Tagihan</span>
                            <span class="fw-bold fs-6 text-primary">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 text-success">
                            <span class="small">Sudah Dibayar</span>
                            <span class="fw-bold">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        @if ($order->total_price - $order->paid_amount > 0)
                            <div class="d-flex justify-content-between py-2 text-danger">
                                <span class="small fw-bold">Sisa Tagihan</span>
                                <span class="fw-bold">Rp
                                    {{ number_format($order->total_price - $order->paid_amount, 0, ',', '.') }}</span>
                            </div>
                        @else
                            <div class="d-flex justify-content-between py-2 text-success">
                                <span class="small fw-bold">✅ Lunas</span>
                                <span class="fw-bold">Rp 0</span>
                            </div>
                        @endif
                    </div>

                    {{-- Tipe Pembayaran --}}
                    <div class="text-center mb-3">
                        <span
                            class="badge {{ $order->payment_type == 'dp' ? 'bg-warning text-dark' : 'bg-success' }} px-3 py-2 rounded-pill">
                            {{ $order->payment_type == 'dp' ? 'Down Payment (DP)' : 'Full Payment' }}
                        </span>
                    </div>

                    {{-- Tombol Kontrak --}}
                    <div class="border-top pt-3 text-center">
                        <a href="{{ route('orders.contract', $order) }}" class="btn btn-outline-dark w-100"
                            target="_blank">
                            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> Unduh Kontrak (MoU)
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
