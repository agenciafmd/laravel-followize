<?php

namespace Agenciafmd\Followize\Providers;

use Illuminate\Support\ServiceProvider;

class FollowizeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 
    }

    public function register()
    {
        $this->loadConfigs();
    }

    protected function loadConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-followize.php', 'laravel-followize');
    }
}
