<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {



        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $this->call(RolesPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(CatalogoSeeder::class);
        $this->call(InventarioSeeder::class);

        $this->call(PermissionSeeder::class);
        $this->call(ConfiguracionSeeder::class);
        $this->call(ConfCorreoSeeder::class);
        $this->call(SeccionesSeeder::class);
        $this->call(MenusSeeder::class);


        $this->call(ConfiguracionCredencialesSeeder::class);

        //SEEDERS DINAMICOS
        $this->call(SeederSeccion_20250625::class);
        $this->call(SeederSeccion_20250701::class);

        $this->call(SeederMenu_20250625::class);
        $this->call(SeederMenu_20250627::class);
        $this->call(SeederMenu_20250704::class);

        $this->call(SeederPermisos_20250625::class);
        $this->call(SeederPermisos_20250627::class);
        $this->call(SeederPermisos_20250704::class);







    }
}
