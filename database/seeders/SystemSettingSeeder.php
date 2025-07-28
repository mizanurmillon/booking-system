<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::insert([
            [
                'id'             => 1,
                'system_name'    => 'Laravel',
                'email'          => 'support@gmail.com',
                'logo'           => null,
                'favicon'        => null,
                'created_at'     => Carbon::now(),
            ],
        ]);
    }
}
