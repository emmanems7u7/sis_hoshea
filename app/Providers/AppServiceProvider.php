<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserInterface;
use App\Repositories\UserRepository;

use App\Interfaces\RoleInterface;
use App\Repositories\RoleRepository;
use App\Interfaces\PermisoInterface;
use App\Repositories\PermisoRepository;
use App\Interfaces\MenuInterface;
use App\Repositories\MenuRepository;
use App\Interfaces\CorreoInterface;
use App\Repositories\CorreoRepository;
use App\Interfaces\CatalogoInterface;
use App\Repositories\CatalogoRepository;
use App\Models\ConfCorreo;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CatalogoInterface::class, CatalogoRepository::class);
        $this->app->bind(MenuInterface::class, MenuRepository::class);
        $this->app->bind(CorreoInterface::class, CorreoRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(PermisoInterface::class, PermisoRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $dbName = env('DB_DATABASE');
        $tableName = 'conf_correos';
        if (Schema::connection('mysql')->hasTable($dbName . '.' . $tableName)) {
            $config = ConfCorreo::first();

            if ($config) {
                config([
                    'mail.mailers.smtp.host' => $config->host,
                    'mail.mailers.smtp.port' => $config->port,
                    'mail.mailers.smtp.encryption' => $config->encryption ?: null,
                    'mail.mailers.smtp.username' => $config->username,
                    'mail.mailers.smtp.password' => $config->password,
                    'mail.from.address' => $config->from_address,
                    'mail.from.name' => $config->from_name,
                ]);
            }
        }
    }
}
