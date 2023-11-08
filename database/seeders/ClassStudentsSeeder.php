<?php

namespace Database\Seeders;

use App\Models\CategoriesData;
use App\Models\EquipmentDataImage;
use App\Models\UserClass;
use App\Models\UserClassStudents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'ABC',
                'attr_1'=>'A',
                'attr_2'=>'B',
                'attr_3'=>'C',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>2,
                'person_name'=>'ABC1',
                'attr_1'=>'A',
                'attr_2'=>'B',
                'attr_3'=>'C',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>3,
                'person_name'=>'ABC2',
                'attr_1'=>'A',
                'attr_2'=>'B',
                'attr_3'=>'C',
            ]);
    }
}
