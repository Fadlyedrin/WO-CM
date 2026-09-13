<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>MoU Kontrak Layanan - #WO-{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #a38c6d; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #a38c6d; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #777; font-size: 12px; }
        .section-title { font-weight: bold; background: #f8f9fa; padding: 8px 12px; border-left: 4px solid #a38c6d; margin: 20px 0 10px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { width: 30%; color: #555; }
        .tnc { font-size: 12px; color: #555; text-align: justify; }
        .tnc li { margin-bottom: 5px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
        .signatures { width: 100%; margin-top: 50px; table-layout: fixed; }
        .signatures td { text-align: center; vertical-align: bottom; height: 100px; }
        .signatures .name { font-weight: bold; text-decoration: underline; margin-top: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cahaya Minang</h1>
        <p>Premium Wedding Organizer Services<br>Jakarta, Indonesia | info@cahayaminang.com</p>
    </div>

    <h2 style="text-align: center; font-size: 18px; margin-bottom: 30px;">MEMORANDUM OF UNDERSTANDING (MoU)<br><small style="font-weight: normal; color: #777;">No. Kontrak: CAHAYA-{{ date('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</small></h2>

    <p>Perjanjian kerja sama layanan Wedding Organizer ini disepakati pada tanggal <strong>{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y') }}</strong> antara pihak Cahaya Minang dan Klien:</p>

    <div class="section-title">Detail Klien</div>
    <table class="table">
        <tr><th>Nama Lengkap</th><td>{{ $order->user->name }}</td></tr>
        <tr><th>Email</th><td>{{ $order->user->email }}</td></tr>
        <tr><th>Nomor Telepon</th><td>{{ $order->user->phone ?? '-' }}</td></tr>
    </table>

    <div class="section-title">Detail Acara & Paket</div>
    <table class="table">
        <tr><th>Paket Layanan</th><td><strong>{{ $order->package->name }}</strong></td></tr>
        <tr><th>Tanggal Acara</th><td>{{ \Carbon\Carbon::parse($order->wedding_date)->format('d F Y') }}</td></tr>
        <tr><th>Waktu Pelaksanaan</th><td>{{ \Carbon\Carbon::parse($order->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->end_time)->format('H:i') }}</td></tr>
        <tr><th>Lokasi (Venue)</th><td>{{ $order->venue_name }}<br><small>{{ $order->venue_address }}</small></td></tr>
        <tr><th>Harga Paket Dasar</th><td>Rp {{ number_format($order->package->price, 0, ',', '.') }}</td></tr>
        @if($order->component_discount > 0)
        <tr><th style="color:#d97706;">Potongan Vendor Sendiri</th><td style="color:#d97706;"><strong>- Rp {{ number_format($order->component_discount, 0, ',', '.') }}</strong></td></tr>
        @endif
        @if($order->discount_amount > 0)
        <tr><th style="color:#16a34a;">Diskon Promo</th><td style="color:#16a34a;"><strong>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</strong></td></tr>
        @endif
        <tr style="background:#f0f9ff;"><th><strong>Total Biaya Disepakati</strong></th><td><strong style="font-size:1.1em;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td></tr>
    </table>

    @if($order->excluded_components && count($order->excluded_components) > 0)
    @php $excludedComps = \App\Models\PackageComponent::whereIn('id', $order->excluded_components)->get(); @endphp
    <div class="section-title" style="border-left-color:#d97706;">Layanan Disediakan Klien Sendiri (Bawa Vendor)</div>
    <table class="table">
        <tr>
            <th style="width:40%;">Layanan</th>
            <th style="width:25%;">Potongan</th>
            <th style="width:35%;">Vendor Klien</th>
        </tr>
        @foreach($excludedComps as $comp)
        <tr>
            <td>{{ $comp->name }}</td>
            <td>- Rp {{ number_format($comp->price, 0, ',', '.') }}</td>
            <td>{{ ($order->vendor_notes && isset($order->vendor_notes[$comp->id])) ? $order->vendor_notes[$comp->id] : '-' }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <div class="section-title">Syarat dan Ketentuan (Terms & Conditions)</div>
    <div class="tnc">
        <ol>
            <li><strong>Sistem Pembayaran:</strong> Pembayaran dilakukan secara bertahap sesuai jadwal yang tertera di sistem. Pelunasan paling lambat harus diselesaikan pada H-14 sebelum tanggal acara.</li>
            <li><strong>Kebijakan Pembatalan (Cancellation Policy):</strong> Apabila terjadi pembatalan sepihak oleh klien, maka <em>Down Payment (DP)</em> yang telah dibayarkan akan dikembalikan maksimal sebesar <strong>10% (sepuluh persen)</strong> dari nominal DP, sebagai kompensasi biaya administrasi dan penalti pembatalan vendor awal.</li>
            <li><strong>Perubahan Tanggal (Reschedule):</strong> Perubahan tanggal acara dapat dilakukan maksimal 1 (satu) kali dengan pemberitahuan selambat-lambatnya 30 hari sebelum acara, bergantung pada ketersediaan jadwal tim Cahaya Minang.</li>
            <li><strong>Tanggung Jawab Venue:</strong> Klien bertanggung jawab atas perizinan dan regulasi yang berlaku di lokasi acara (venue) kecuali disepakati lain secara tertulis.</li>
            <li><strong>Keadaan Memaksa (Force Majeure):</strong> Dalam hal terjadi bencana alam, pandemi massal, atau kebijakan pemerintah yang melarang acara, kedua belah pihak sepakat untuk menjadwalkan ulang acara tanpa dikenakan biaya penalti.</li>
        </ol>
    </div>

    <p style="margin-top: 30px; text-align: justify; font-size: 13px;">Dengan diterbitkannya dokumen ini, Klien menyatakan setuju dengan seluruh rincian layanan dan Syarat & Ketentuan yang telah ditetapkan oleh Cahaya Minang.</p>

    <table class="signatures">
        <tr>
            <td>
                Pihak Wedding Organizer<br><strong>Cahaya Minang</strong>
                <div class="name">Manajemen Cahaya Minang</div>
            </td>
            <td>
                Klien / Pemesan<br><strong>Pihak Mempelai</strong>
                <div class="name">{{ $order->user->name }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dibuat dan disahkan secara otomatis oleh sistem Cahaya Minang.<br>
        Dicetak pada: {{ now()->format('d M Y, H:i') }}
    </div>

</body>
</html>
