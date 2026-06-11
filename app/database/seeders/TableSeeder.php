<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['number'=>1,  'capacity'=>2, 'location'=>'indoor',  'status'=>'available'],
            ['number'=>2,  'capacity'=>4, 'location'=>'indoor',  'status'=>'available'],
            ['number'=>3,  'capacity'=>4, 'location'=>'indoor',  'status'=>'occupied'],
            ['number'=>4,  'capacity'=>6, 'location'=>'indoor',  'status'=>'available'],
            ['number'=>5,  'capacity'=>4, 'location'=>'indoor',  'status'=>'reserved'],
            ['number'=>6,  'capacity'=>2, 'location'=>'outdoor', 'status'=>'available'],
            ['number'=>7,  'capacity'=>4, 'location'=>'outdoor', 'status'=>'available'],
            ['number'=>8,  'capacity'=>4, 'location'=>'outdoor', 'status'=>'occupied'],
            ['number'=>9,  'capacity'=>2, 'location'=>'bar',     'status'=>'available'],
            ['number'=>10, 'capacity'=>2, 'location'=>'bar',     'status'=>'occupied'],
            ['number'=>11, 'capacity'=>8, 'location'=>'private', 'status'=>'available'],
            ['number'=>12, 'capacity'=>2, 'location'=>'indoor',  'status'=>'cleaning'],
        ];

        foreach ($tables as $t) {
            Table::firstOrCreate(['number' => $t['number']], $t);
        }
    }
}
