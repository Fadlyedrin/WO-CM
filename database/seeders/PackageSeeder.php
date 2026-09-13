<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Package;
use App\Models\PackageComponent;
use App\Models\PackageImage;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Seed categories, packages, package components, and package images
     * based on existing production data (wedd_cm.sql).
     */
    public function run(): void
    {
        // =====================
        // Categories
        // =====================
        $riasBusana = Category::updateOrCreate(
            ['slug' => 'rias-busana'],
            [
                'name' => 'Rias & Busana',
                'description' => 'Pilihan Paket Rias & Busana',
            ]
        );

        $adatMinang = Category::updateOrCreate(
            ['slug' => 'adat-minang'],
            [
                'name' => 'Adat Minang',
                'description' => 'Pilihan paket adat minang',
            ]
        );

        // =====================
        // Packages
        // =====================
        $paketSilver = Package::updateOrCreate(
            ['slug' => 'paket-silver'],
            [
                'category_id' => $riasBusana->id,
                'name' => 'PAKET SILVER',
                'description' => '<ul><li>Mua Akad &amp; Resepsi</li><li>Baju akad nikah sepasang + accessories (Baju kebaya)</li><li>Baju Resepsi sepasang + accessories &nbsp;(Baju kebaya/adat)</li><li>Hijabdo akad</li><li>Hijabdo resepsi</li><li>Rias hijabdo 2 Ibu</li><li>Rias hijabdo 2 Penerima Tamu</li><li>Busana 2 Ibu + Kain + Selendang</li><li>Busana 2 Bapak + Kain sampang + Peci/saluak</li><li>Busana 2 Penerima Tamu + tanduk/suntiang</li><li>Melati pengalungan</li></ul>',
                'price' => 11000000.00,
                'image_url' => 'packages/Sk11yvQpJ1CDipfDoK3iWx54Ytg2HuXVgyjrkMv0.png',
                'is_available' => true,
            ]
        );

        $paketGold = Package::updateOrCreate(
            ['slug' => 'paket-gold'],
            [
                'category_id' => $riasBusana->id,
                'name' => 'PAKET GOLD',
                'description' => '<ul><li>Mua Akad &amp; Resepsi</li><li>Baju akad nikah sepasang + accessories (Baju kebaya)</li><li>Baju Resepsi sepasang + accessories &nbsp;(Baju kebaya/adat)</li><li>Hijabdo akad</li><li>Hijabdo resepsi/suntiang/siger</li><li>Rias hijabdo 2 Ibu</li><li>Rias hijabdo 2 Penerima Tamu</li><li>Busana 2 Ibu + Kain + Selendang</li><li>Busana 2 Bapak + Kain sampang + Peci/saluak</li><li>Busana 2 Penerima Tamu + tanduk/suntiang</li><li>Melati pengalungan</li></ul>',
                'price' => 12000000.00,
                'image_url' => 'packages/1MhdZasbWD2NhxbKNrL9AZKwlzKvzyKDAone420i.png',
                'is_available' => true,
            ]
        );

        $paketAdatMinang = Package::updateOrCreate(
            ['slug' => 'all-in-package-adat-minang'],
            [
                'category_id' => $adatMinang->id,
                'name' => 'ALL IN PACKAGE ADAT MINANG',
                'description' => '<p>PAKET UNTUK 600 PAX</p><p><strong>HARGA SUDAH TERMASUK</strong></p><ul><li>Catering</li><li>Dekorasi</li><li>Rias Busana</li><li>Tari Adat Minang</li><li>Foto &amp; Video</li><li>Entertainment</li><li>MC</li><li>WO</li><li>Buku Tamu</li></ul><p><strong>HARGA BELUM TERMASUK</strong></p><ul><li>Sewa Gedung &amp; Charge</li></ul>',
                'price' => 121000000.00,
                'image_url' => 'packages/7oGXWbBO3L4AAXrFiFCUjuke8yeWx2JQ4UTkubIL.png',
                'is_available' => true,
            ]
        );

        // =====================
        // Package Components (only ALL IN PACKAGE ADAT MINANG has components)
        // =====================
        $components = [
            [
                'name' => 'CATERING',
                'icon' => 'bi-star',
                'description' => '<p><span class="text-small">300 Undangan = 600 orang</span></p><p><span class="text-small">Buffet Utama 500 Porsi</span></p><ul><li>Nasi Putih</li><li>Aneka Nasi Goreng</li><li>Aneka Daging</li><li>Aneka Ayam / Aneka Ikan</li><li>Aneka Sup</li><li>Asinan pengantin</li><li>Kerupuk udang</li><li>Air mineral</li><li>Dessert (Buah potong,puding,aneka snack,soft drink)</li></ul><p>GUBUGAN</p><ul><li>Sate padang 100 porsi</li><li>Bakwan malang 100 porsi</li><li>Bakso 100 porsi</li><li>Dimsum 100 porsi</li><li>Es cream 2 Galon</li></ul><p>&nbsp;</p>',
                'price' => 74250000,
                'is_optional' => true,
                'sort_order' => 0,
            ],
            [
                'name' => 'Dekorasi',
                'icon' => 'bi-star',
                'description' => '<ul><li>Pelaminan Minang Bagonjong</li><li>Layar Bintang (kain hitam) di belakang pelaminan</li><li>Bantagadang, Payung, Carano, Lumbung padi</li><li>Kursi Pengantin</li><li>Lampu LED secukupnya</li><li>Standing Flower 4 buah</li><li>Lampu Kristal</li><li>Mini garden didepan pelaminan</li><li>Sepasang Lampu Sorot di depan Pelaminan</li><li>2 buah marawa di Pelaminan</li></ul>',
                'price' => 45000000,
                'is_optional' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Rias Busana',
                'icon' => 'bi-star',
                'description' => '<p>rias busana</p>',
                'price' => 14600000,
                'is_optional' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Foto & Video',
                'icon' => 'bi-star',
                'description' => '<p>foto</p>',
                'price' => 4000000,
                'is_optional' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tari Adat Minang',
                'icon' => 'bi-star',
                'description' => '<p>tari adat</p>',
                'price' => 2500000,
                'is_optional' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Entertainment',
                'icon' => 'bi-star',
                'description' => '<p>entertainment</p>',
                'price' => 4000000,
                'is_optional' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'MC',
                'icon' => 'bi-star',
                'description' => '<p>MC</p>',
                'price' => 1300000,
                'is_optional' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'WO',
                'icon' => 'bi-star',
                'description' => '<p>4 WO</p>',
                'price' => 2700000,
                'is_optional' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'Lain-Lain',
                'icon' => 'bi-star',
                'description' => '<p>buku tamu</p>',
                'price' => 400000,
                'is_optional' => false,
                'sort_order' => 8,
            ],
        ];

        foreach ($components as $component) {
            PackageComponent::updateOrCreate(
                [
                    'package_id' => $paketAdatMinang->id,
                    'name' => $component['name'],
                ],
                $component
            );
        }

        // =====================
        // Package Images
        // =====================
        $images = [
            $paketSilver->id => 'packages/Sk11yvQpJ1CDipfDoK3iWx54Ytg2HuXVgyjrkMv0.png',
            $paketGold->id => 'packages/1MhdZasbWD2NhxbKNrL9AZKwlzKvzyKDAone420i.png',
            $paketAdatMinang->id => 'packages/7oGXWbBO3L4AAXrFiFCUjuke8yeWx2JQ4UTkubIL.png',
        ];

        foreach ($images as $packageId => $imagePath) {
            PackageImage::updateOrCreate(
                [
                    'package_id' => $packageId,
                    'image_path' => $imagePath,
                ]
            );
        }
    }
}
