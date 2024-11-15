<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     use HasFactory;

    public function run(): void
    {
        DB::table('groups')->insert([
            'name' => '1',
           
        ]);

        DB::table('groups')->insert([
            'name' => '2',
           
        ]);

        DB::table('groups')->insert([
            'name' => '3',
           
        ]);

       
    }
}
