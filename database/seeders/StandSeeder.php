<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Stand;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $socio = Student::create([
            'full_name' => 'CLIDER ROMANI TORRES',
            'first_name'=> 'CLIDER',
            'last_name' => 'ROMANI TORRES',
            'email' => 'CLIDER@gmail.com',
            'document_type' => 1,
            'document' => '71558339',
            'phone' => '',
            'address' => '',
            'is_ative' =>true,
            'is_client' => true,
        ]);

        Customer::create([
            'full_name' => 'CLIDER ROMANI TORRES',
            'first_name'=> 'CLIDER',
            'last_name' => 'ROMANI TORRES',
            'email' => 'CLIDER@gmail.com',
            'document_type' => 1,
            'document' => '71558339',
            'phone' => '',
            'address' => '',
            'is_ative' =>true,
            'is_tutor' => true,
            'is_client' => true,
            'student_id' => $socio->id,

        ]);
    }
}
