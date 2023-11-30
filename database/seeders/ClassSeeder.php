<?php

namespace Database\Seeders;

use App\Models\CategoriesData;
use App\Models\EquipmentDataImage;
use App\Models\UserClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        UserClass::create([
            'name'=>'Class 1',
            'user_id'=>1,
            'shareable_id'=>generateUniqueKey()
        ]);

        UserClass::create([
            'name'=>'Class 2',
            'user_id'=>1,
            'shareable_id'=>generateUniqueKey()
        ]);

        UserClass::create([
            'name'=>'Class 3',
            'user_id'=>1,
            'shareable_id'=>generateUniqueKey()
        ]);

    }
}
