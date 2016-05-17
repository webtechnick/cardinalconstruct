<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(App\User::class, 1)->create()->each(function($u) {
	        $u->galleries()->save(factory(App\Gallery::class)->make());
	    });
        // $this->call(UserTableSeeder::class);
        // $this->call(GalleryTableSeeder::class);
    }
}
