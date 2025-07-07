<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CambiarInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $user = \App\Models\User::find(1);
    if ($user) {
        $user->name = 'Ezequiel Actualizado';
        $user->save();
    }
}

}
