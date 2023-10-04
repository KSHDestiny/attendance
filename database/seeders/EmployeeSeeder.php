<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $location = ["main_office","yuzana_tower","downtown"];

        $json = File::get(public_path()."/data/employees.json");
        $data = json_decode($json);
        foreach($data as $item){
            DB::table('employees')->insert([
                'user_id'=>User::get()->random()->id,
                'name' => $item->name,
                'email' => $item->email,
                'age' => $item->age,
                'phone' => $item->phone,
                'address' => $item->address,
                'department' => "IT Department",
                'location' =>fake()->randomElement($location),
                'position' => $item->position,
                'created_at' => Carbon::now()
            ]);
        }
    }
}
