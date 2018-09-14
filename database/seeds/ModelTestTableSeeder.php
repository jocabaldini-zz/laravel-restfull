<?php

use Illuminate\Database\Seeder;

class ModelTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ModelTest::class, 50)->create();
    }
}
