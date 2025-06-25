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
            $conf = ConfCorreo::first();

            if ($conf) {
                config([
                    'mail.mailers.smtp.host' => $conf->conf_smtp_host,
                    'mail.mailers.smtp.port' => $conf->conf_smtp_port,
                    'mail.mailers.smtp.username' => $conf->conf_smtp_user,
                    'mail.mailers.smtp.password' => $conf->conf_smtp_pass,
                    'mail.mailers.smtp.encryption' => $conf->conf_protocol,
                    'mail.default' => 'smtp',
                ]);
            }
        }
    }
}
