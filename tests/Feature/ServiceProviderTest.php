<?php

use Filament\Events\ServingFilament;
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

it('defers auto-registered defaults until Filament is serving', function () {
    $plugin = Mockery::spy(FilamentSensibleDefaultsPlugin::class);
    app()->instance(FilamentSensibleDefaultsPlugin::class, $plugin);

    (new FilamentSensibleDefaultsServiceProvider(app()))->packageBooted();

    $plugin->shouldNotHaveReceived('apply');

    ServingFilament::dispatch();

    $plugin->shouldHaveReceived('apply');
});

it('applies auto-registered defaults at boot when lazy is disabled', function () {
    config()->set('sensible-defaults.lazy', false);

    $plugin = Mockery::spy(FilamentSensibleDefaultsPlugin::class);
    app()->instance(FilamentSensibleDefaultsPlugin::class, $plugin);

    (new FilamentSensibleDefaultsServiceProvider(app()))->packageBooted();

    $plugin->shouldHaveReceived('apply')->once();
});

it('resolves the registered plugin from the panel', function () {
    expect(FilamentSensibleDefaultsPlugin::get())
        ->toBeInstanceOf(FilamentSensibleDefaultsPlugin::class);
});
