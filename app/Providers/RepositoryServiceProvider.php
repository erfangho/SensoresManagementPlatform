<?php

namespace App\Providers;

use App\Interfaces\DeviceRepositoryInterface;
use App\Interfaces\SubZoneRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\ZoneRepositoryInterface;
use App\Repositories\DeviceRepository;
use App\Repositories\SubZoneRepository;
use App\Repositories\UserRepository;
use App\Repositories\ZoneRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ZoneRepositoryInterface::class, ZoneRepository::class);
        $this->app->bind(SubZoneRepositoryInterface::class, SubZoneRepository::class);
        $this->app->bind(DeviceRepositoryInterface::class, DeviceRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
