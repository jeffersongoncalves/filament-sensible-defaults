<?php

use JeffersonGoncalves\Filament\SensibleDefaults\FilamentSensibleDefaultsPlugin;
use JeffersonGoncalves\Filament\SensibleDefaults\FilamentSensibleDefaultsServiceProvider;
use Spatie\LaravelPackageTools\Package;

it('can be instantiated', function () {
    $provider = new FilamentSensibleDefaultsServiceProvider(app());

    expect($provider)->toBeInstanceOf(FilamentSensibleDefaultsServiceProvider::class);
});

it('has correct package name', function () {
    $provider = new FilamentSensibleDefaultsServiceProvider(app());

    $package = new Package;
    $provider->configurePackage($package);

    expect($package->name)->toBe('filament-sensible-defaults');
});

it('exposes a plugin with a stable id', function () {
    expect(FilamentSensibleDefaultsPlugin::make()->getId())->toBe('filament-sensible-defaults');
});

it('resolves the registered plugin from the panel', function () {
    expect(FilamentSensibleDefaultsPlugin::get())
        ->toBeInstanceOf(FilamentSensibleDefaultsPlugin::class);
});
