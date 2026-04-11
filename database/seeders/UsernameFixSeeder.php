<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsernameFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Models\User::all() as $user) {
            $baseSlug = \Illuminate\Support\Str::slug($user->name);
            if (!$baseSlug) {
                $baseSlug = 'user-' . $user->id;
            }
            
            $slug = $baseSlug;
            $counter = 1;
            
            while (\App\Models\User::where('username', $slug)->where('id', '!=', $user->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $user->username = $slug;
            $user->save();
        }
    }
}
