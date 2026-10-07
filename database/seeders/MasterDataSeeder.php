<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Dealer;
use App\Models\Department;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['DLP-001', 'Astra Motor Jakarta'],
            ['DLP-002', 'Astra Motor Bandung'],
            ['DLP-003', 'Astra Motor Surabaya'],
        ] as [$code, $name]) {
            Dealer::updateOrCreate(['code' => $code], ['name' => $name]);
        }

        foreach ([
            ['DEP-SAL', 'Sales'],
            ['DEP-SRV', 'Service'],
            ['DEP-PRT', 'Spare Parts'],
        ] as [$code, $name]) {
            Department::updateOrCreate(['code' => $code], ['name' => $name]);
        }

        foreach ([
            ['ARA-JKT', 'Jakarta'],
            ['ARA-JBR', 'Jawa Barat'],
            ['ARA-JTM', 'Jawa Timur'],
        ] as [$code, $name]) {
            Area::updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }
}
