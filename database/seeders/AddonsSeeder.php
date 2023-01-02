<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addons = array(
            array('branches' => '1','hrm' => '1','todo' => '1','service' => '0','manufacturing' => '0','e_commerce' => '0')
        );
        
        \DB::table("addons")->insert($addons);
    }
}
