@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 fw-bold" style="color: #2b3674; font-size: 1.5rem;">Halo, {{ Auth::user()->name }}!</h3>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Pesanan Baru
    </a>
</div>

@php
    $overdueCount = 0;
    $allOrders = \App\Models\Order::with('payments')->whereIn('status', ['pending'])->get();
    foreach($allOrders as $o) {
        foreach($o->payments as $payment) {
            if ($payment->status === 'pending' && $payment->due_date && $payment->due_date->endOfDay()->isPast()) {
                $overdueCount++;
                break;
            }
        }
    }
    
    // Upcoming Events: Orders that are paid or completed and event is within 7 days
    $upcomingEvents = \App\Models\Order::whereIn('status', ['paid', 'completed'])
        ->whereBetween('wedding_date', [now(), now()->addDays(7)])
        ->count();
@endphp

@if($upcomingEvents > 0)
    <div class="alert alert-info d-flex align-items-center border-0 mb-3 shadow-sm" role="alert" style="border-radius: 12px; border-left: 6px solid #0dcaf0 !important; background-color: #f0f9ff; color: #0077b6;">
        <i class="bi bi-calendar-event-fill fs-3 me-3"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-1">Acara Mendatang: {{ $upcomingEvents }} Pesanan minggu ini!</h6>
            <p class="mb-0 small">Silakan periksa bagian Pesanan untuk memastikan seluruh persiapan acara ini sudah selesai.</p>
        </div>
    </div>
@endif

@if($overdueCount > 0)
    <div class="alert alert-danger d-flex align-items-center border-0 mb-4 shadow-sm" role="alert" style="border-radius: 12px; border-left: 6px solid #dc3545 !important;">
        <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-1">Perhatian: {{ $overdueCount }} Pembayaran Jatuh Tempo</h6>
            <p class="mb-0 small">Silakan periksa bagian Manajemen Pesanan dan hubungi klien terkait tunggakan pembayaran mereka.</p>
        </div>
    </div>
@endif

<!-- Metric Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">Total Pendapatan (Bersih)</div>
            <div class="d-flex align-items-baseline mb-2">
                <h3 class="fw-bold mb-0 me-2" style="color: #2b3674;">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <span class="text-success small fw-bold"><i class="bi bi-arrow-up-short"></i>+5%</span>
            </div>
            <div class="small text-muted mt-1" style="font-size: 0.73rem;">
                <i class="bi bi-info-circle-fill text-primary me-1"></i>Termasuk dana ditahan (90% DP) dari pesanan batal.
            </div>
            <div class="progress mt-2 bg-light" style="height: 6px; border-radius: 10px;">
                <div class="progress-bar" role="progressbar" style="width: 70%; background-color: #4318ff; border-radius: 10px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">Total Pesanan</div>
            <div class="d-flex align-items-baseline mb-2">
                <h3 class="fw-bold mb-0 me-2" style="color: #2b3674;">{{ $stats['orders'] }}</h3>
                <span class="text-success small fw-bold"><i class="bi bi-arrow-up-short"></i>+12%</span>
            </div>
            <div class="progress mt-3 bg-light" style="height: 6px; border-radius: 10px;">
                <div class="progress-bar" role="progressbar" style="width: 50%; background-color: #f1c40f; border-radius: 10px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">Paket Aktif</div>
            <div class="d-flex align-items-baseline mb-2">
                <h3 class="fw-bold mb-0 me-2" style="color: #2b3674;">{{ $stats['packages'] }}</h3>
                <span class="text-danger small fw-bold"><i class="bi bi-arrow-down-short"></i>-2%</span>
            </div>
            <div class="progress mt-3 bg-light" style="height: 6px; border-radius: 10px;">
                <div class="progress-bar" role="progressbar" style="width: 85%; background-color: #2ecc71; border-radius: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Main Chart -->
    <div class="col-lg-8">
        <div class="card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bar-chart-fill text-primary"></i>
                    <h6 class="fw-bold mb-0" style="color: #2b3674;">Pendapatan vs Pesanan</h6>
                </div>
                <div class="btn-group">
                    <button class="btn btn-sm btn-light active fw-medium">Bulan</button>
                    <button class="btn btn-sm btn-light fw-medium text-muted">Tahun</button>
                </div>
            </div>
            <div id="barChart" style="min-height: 300px;"></div>
        </div>
    </div>
    
    <!-- Side Panel: Recent Inquiries -->
    <div class="col-lg-4">
        <div class="card h-100 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-envelope-fill text-primary"></i>
                    <h6 class="fw-bold mb-0" style="color: #2b3674;">Pesan Masuk Terbaru</h6>
                </div>
                <a href="{{ route('admin.inquiries.index') }}" class="text-muted"><i class="bi bi-three-dots"></i></a>
            </div>
            
            <div class="d-flex flex-column gap-3">
                @forelse($recentInquiries as $inquiry)
                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-decoration-none d-block pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark">{{ $inquiry->name }}</span>
                        <span class="text-muted small">{{ $inquiry->created_at->format('d M') }}</span>
                    </div>
                    <div class="text-muted small text-truncate" style="max-width: 200px;">{{ $inquiry->message }}</div>
                    @if(!$inquiry->is_read)
                        <span class="badge bg-primary rounded-pill mt-2" style="font-size: 0.6rem;">BARU</span>
                    @endif
                </a>
                @empty
                <div class="text-center text-muted py-4">Belum ada pesan masuk.</div>
                @endforelse
            </div>
            <div class="mt-auto pt-3 text-center">
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-light btn-sm w-100 fw-bold text-primary">Lihat Semua Pesan</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-briefcase-fill text-primary"></i>
            <h6 class="fw-bold mb-0" style="color: #2b3674;">Pesanan Terbaru</h6>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light fw-medium">Lihat Semua</a>
    </div>
    
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th class="border-0 pb-3 text-muted fw-semibold" style="font-size: 0.75rem;">ID</th>
                    <th class="border-0 pb-3 text-muted fw-semibold" style="font-size: 0.75rem;">KLIEN</th>
                    <th class="border-0 pb-3 text-muted fw-semibold" style="font-size: 0.75rem;">TANGGAL</th>
                    <th class="border-0 pb-3 text-muted fw-semibold" style="font-size: 0.75rem;">PAKET</th>
                    <th class="border-0 pb-3 text-muted fw-semibold" style="font-size: 0.75rem;">STATUS</th>
                    <th class="border-0 pb-3 text-muted fw-semibold text-end" style="font-size: 0.75rem;">JUMLAH</th>
                </tr>
            </thead>
            <tbody style="border-top: 1px solid #f4f7fe;">
                @forelse(\App\Models\Order::with('user', 'package')->latest()->take(5)->get() as $order)
                <tr>
                    <td class="py-3 border-bottom-0"><span class="fw-bold text-muted">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</span></td>
                    <td class="py-3 border-bottom-0">
                        <div class="fw-bold" style="color: #2b3674;">{{ $order->user->name }}</div>
                    </td>
                    <td class="py-3 border-bottom-0">
                        <div class="fw-medium text-muted">{{ $order->wedding_date->format('d.m.Y') }}</div>
                    </td>
                    <td class="py-3 border-bottom-0">
                        <div class="fw-medium text-muted">{{ $order->package->name }}</div>
                    </td>
                    <td class="py-3 border-bottom-0">
                        @if($order->computed_status == 'pending')
                            <span class="badge" style="background-color: rgba(241, 196, 15, 0.1); color: #f39c12;">Baru</span>
                        @elseif($order->computed_status == 'paid')
                            <span class="badge" style="background-color: rgba(46, 204, 113, 0.1); color: #27ae60;">Lunas</span>
                        @elseif($order->computed_status == 'completed')
                            <span class="badge" style="background-color: rgba(52, 152, 219, 0.1); color: #2980b9;">Selesai</span>
                        @else
                            <span class="badge" style="background-color: rgba(231, 76, 60, 0.1); color: #c0392b;">Batal</span>
                        @endif
                    </td>
                    <td class="py-3 border-bottom-0 text-end">
                        <div class="fw-bold" style="color: #2b3674;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada pesanan terbaru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chartData = @json($chartData);
        
        var options = {
            series: [{
                name: 'Revenue',
                type: 'column',
                data: chartData.revenue
            }, {
                name: 'Orders',
                type: 'column',
                data: chartData.orders
            }],
            chart: {
                height: 350,
                type: 'bar',
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false },
                background: 'transparent'
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 4
                },
            },
            colors: ['#4318ff', '#f1c40f'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 4,
                colors: ['transparent']
            },
            labels: chartData.labels,
            xaxis: {
                type: 'category',
                labels: {
                    style: { colors: '#a3aed1', fontWeight: 600 }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: [{
                labels: {
                    style: { colors: '#a3aed1', fontWeight: 600 },
                    formatter: function (value) {
                        if (value >= 1000000) {
                            return (value / 1000000).toFixed(1) + 'M';
                        }
                        return value;
                    }
                }
            }, {
                opposite: true,
                labels: {
                    style: { colors: '#a3aed1', fontWeight: 600 },
                    formatter: function (value) {
                        return Math.round(value);
                    }
                }
            }],
            grid: {
                borderColor: '#f4f7fe',
                strokeDashArray: 0,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            legend: {
                show: false
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y, { seriesIndex }) {
                        if (typeof y !== "undefined") {
                            if (seriesIndex === 0) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(y);
                            } else {
                                return y + " Orders";
                            }
                        }
                        return y;
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#barChart"), options);
        chart.render();
    });
</script>