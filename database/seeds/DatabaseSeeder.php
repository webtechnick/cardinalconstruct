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
    	/*factory(App\User::class, 1)->create()->each(function($u) {
	        $u->galleries()->save(factory(App\Gallery::class)->make());
	    });*/

        $user = factory(App\User::class)->create([
            'name' => 'Nick',
            'email' => 'nick@example.com',
            'password' => bcrypt('secret'),
            'role' => 'admin'
        ]);

        $user->galleries()->save(factory(App\Gallery::class)->make());

        // $this->call(UserTableSeeder::class);
        // $this->call(GalleryTableSeeder::class);
    }
}
