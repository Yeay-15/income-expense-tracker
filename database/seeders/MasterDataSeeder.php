<?php

namespace Database\Seeders;

use App\Models\AccountType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Default Account Types (user_id tidak ada di tabel ini karena memang selalu global)
        $types = ['Tunai', 'Rekening Bank', 'E-Wallet', 'Kartu Kredit'];
        foreach ($types as $type) {
            AccountType::firstOrCreate(['name' => $type]);
        }

        // Default Categories (user_id NULL = global, muncul untuk semua user)
        $incomeCategories = ['Gaji', 'Bonus', 'Investasi', 'Hadiah', 'Lainnya'];
        $expenseCategories = ['Makanan', 'Transportasi', 'Tagihan', 'Belanja', 'Hiburan', 'Kesehatan', 'Pendidikan', 'Lainnya'];

        foreach ($incomeCategories as $name) {
            Category::firstOrCreate(['name' => $name, 'type' => 'income', 'user_id' => null]);
        }

        foreach ($expenseCategories as $name) {
            Category::firstOrCreate(['name' => $name, 'type' => 'expense', 'user_id' => null]);
        }
    }
}
