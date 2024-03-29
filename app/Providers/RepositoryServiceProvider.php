<?php

namespace App\Providers;

use App\Interfaces\CurrentRepositoryInterface;
use App\Interfaces\DeviceRepositoryInterface;
use App\Interfaces\HumidityRepositoryInterface;
use App\Interfaces\SubZoneRepositoryInterface;
use App\Interfaces\TemperatureRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\VoltageRepositoryInterface;
use App\Interfaces\ZoneRepositoryInterface;
use App\Repositories\CurrentRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\HumidityRepository;
use App\Repositories\SubZoneRepository;
use App\Repositories\TemperatureRepository;
use App\Repositories\UserRepository;
use App\Repositories\VoltageRepository;
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
        $this->app->bind(TemperatureRepositoryInterface::class, TemperatureRepository::class);
        $this->app->bind(HumidityRepositoryInterface::class, HumidityRepository::class);
        $this->app->bind(CurrentRepositoryInterface::class, CurrentRepository::class);
        $this->app->bind(VoltageRepositoryInterface::class, VoltageRepository::class);
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
