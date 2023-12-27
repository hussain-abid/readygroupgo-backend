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
                'person_name'=>'John',
                'attr_1'=>1,
                'attr_2'=>'B',
                'attr_3'=>'C',
            ]);


        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Adam',
                'attr_1'=>1,
                'attr_2'=>'',
                'attr_3'=>'',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Jacob',
                'attr_1'=>3,
                'attr_2'=>'',
                'attr_3'=>'',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Anna',
                'attr_1'=>5,
                'attr_2'=>'',
                'attr_3'=>'',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Mathew',
                'attr_1'=>'',
                'attr_2'=>'',
                'attr_3'=>'',
            ]);
        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Wick',
                'attr_1'=>'',
                'attr_2'=>'',
                'attr_3'=>'',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Ash',
                'attr_1'=>'',
                'attr_2'=>'',
                'attr_3'=>'',
            ]);

        UserClassStudents::create(
            [
                'class_id'=>1,
                'person_name'=>'Julie',
                'attr_1'=>'',
                'attr_2'=>'',
                'attr_3'=>'',
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
