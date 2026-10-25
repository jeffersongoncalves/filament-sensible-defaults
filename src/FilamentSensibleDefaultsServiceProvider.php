<?php

namespace JeffersonGoncalves\Filament\SensibleDefaults;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentSensibleDefaultsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-sensible-defaults')
            ->hasConfigFile('sensible-defaults');
    }

    public function packageBooted(): void
    {
        if (config('sensible-defaults.auto_register', true)) {
            FilamentSensibleDefaultsPlugin::make()->apply();
        }
    }
}
