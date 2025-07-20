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
        $this->call(SeederServicios::class);
        //  $this->call(ConfiguracionCredencialesSeeder::class);
        /*
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
                $this->call(SeederServicios::class);
                $this->call(CategoriaInventarioSeeder::class);


                $this->call(ConfiguracionCredencialesSeeder::class);

                //SEEDERS DINAMICOS
                $this->call(SeederSeccion_20250625::class);


                // MENUS CARGADOS
                $this->call(SeederMenu_20250625::class);
                $this->call(SeederMenu_20250627::class);
                $this->call(SeederMenu_20250704::class);
                // MENUS CARGADOS


                $this->call(SeederPermisos_20250625::class);
                $this->call(SeederPermisos_20250627::class);
                $this->call(SeederPermisos_20250704::class);

        $this->call(SeederSeccion_20250701::class);
        $this->call(SeederCategoria_20250709::class);
        $this->call(SeederCatalogo_20250709::class);
        $this->call(SeederMenu_20250709::class);
        $this->call(SeederPermisos_20250709::class);
        $this->call(SeederCatalogo_20250710::class);

 */
        //$this->call(PacientesSeeder::class); // listo en prod


        //INICIO SEEDERS SECCION

        //FIN SEEDERS SECCION

        //INICIO SEEDERS CATEGORIA

        // $this->call(SeederCategoria_20250713::class); // listo en prod

        //FIN SEEDERS CATEGORIA

        //INICIO SEEDERS CATALOGO


        // $this->call(SeederCatalogo_20250713::class); // listo en prod

        //FIN SEEDERS CATALOGO

        //INICIO SEEDERS MENU

        //  $this->call(SeederMenu_20250718::class);

        //FIN SEEDERS MENU

        //INICIO SEEDERS PERMISOS
        //$this->call(SeederPermisos_20250718::class);

        //FIN SEEDERS PERMISOS

    }
}
