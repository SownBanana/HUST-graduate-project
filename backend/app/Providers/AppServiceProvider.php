<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $models = array(
            'Course',
        );

        foreach ($models as $model) {
            $this->app->bind("App\Repositories\\{$model}\{$model}RepositoryInterface", "App\Repositories\\{$model}\{$model}Repository");
        }
        // $this->app->singleton(
        //     \App\Repositories\Course\CourseRepository::class,
        //     \App\Repositories\Course\CourseRepositoryInterface::class
        // );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
