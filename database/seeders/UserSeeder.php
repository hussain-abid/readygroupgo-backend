<?php

namespace Database\Seeders;

use App\Models\CategoriesData;
use App\Models\EquipmentDataImage;
use App\Models\User;
use App\Models\UserClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'first_name'=>'hussain',
            'last_name'=>'abid',
            'email'=>'hussain@readygroupgo.com',
            'password'=>bcrypt('password'),
        ]);
    }
}
