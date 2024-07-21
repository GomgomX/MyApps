<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Listing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(5)->create();

        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'jogn@gmail.com'
        ]);
        
        Listing::factory(6)->create(['user_id' => $user->id]);
        //Listing::factory(7)->create();
        // Listing::create([
        //     'title' => 'Laravel Senior Developer',
        //     'tags' => 'laravel, javascript',
        //     'company' => 'Acme Corp',
        //     'location' => 'Boston, MA',
        //     'email' => 'email1@email.com',
        //     'website' => 'https://www.acme.com',
        //     'desc' => 'Loremak lfkalwfjkla jkl2jrklafiojaiofj af '
        // ]);
        // Listing::create([
        //     'title' => 'Full-Stack Engineer',
        //     'tags' => 'laravel, backend, api',
        //     'company' => 'Stark Industries',
        //     'location' => 'Newyork, NY',
        //     'email' => 'email2@email.com',
        //     'website' => 'https://www.strind.com',
        //     'desc' => 'Loremak lfkalwfjkla jawgagjmawk kljaklfwljfkl2jrklafiojaiofj af '
        // ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
