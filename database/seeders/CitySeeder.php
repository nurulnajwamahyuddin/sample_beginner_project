<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $cities = array(
        array(
            'id' => 1,
            'state_id' => 1,
            'cities_name' => 'Alor Setar'
        ),
        array(
            'id' => 2,
            'state_id' => 1,
            'cities_name' => 'Jitra'
        ),
        array(
            'id' => 3,
            'state_id' => 1,
            'cities_name' => 'Sungai Petani'
        ),
        array(
            'id' => 4,
            'state_id' => 1,
            'cities_name' => 'Baling'
        ),
        array(
            'id' => 5 ,
            'state_id' => 2,
            'cities_name' => 'Subang'

        ),
        array(
            'id' => 6 ,
            'state_id' => 2,
            'cities_name' => 'Petaling Jaya'

        ),
        array(
            'id' => 7,
            'state_id' => 3,
            'cities_name' => 'Bagan Serai'

        ),
        array(
            'id' => 8,
            'state_id' => 3,
            'cities_name' => 'Batu Gajah'

        ),
        array(
            'id' => 9,
            'state_id' => 3,
            'cities_name' => 'Bidor'

        )
    );

    public function run()
    {
        foreach ($this->cities as $cities) {
            City::create($cities);
        }
    }
}
