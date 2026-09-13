<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CheckExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mengubah status pesanan yang melewati tanggal acara menjadi Dibatalkan (jika pending) atau Selesai (jika lunas)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa pesanan yang sudah melewati tanggal acara...');
        Order::autoUpdateExpiredStatuses();
        $this->info('Pembaruan status otomatis selesai dilakukan!');
    }
}
