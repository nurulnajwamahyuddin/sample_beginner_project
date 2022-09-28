<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;
use DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $states = array(
        array(
            'id' => 1,
            'state_name' => 'Kedah'
        ),
        array(
            'id' => 2,
            'state_name' => 'Kuala Lumpur'

        ),
        array(
            'id' => 3,
            'state_name' => 'Perak'

        )
    );

    public function run()
    {

        foreach ($this->states as $state) {
            State::create($state);
        }
    }
}
