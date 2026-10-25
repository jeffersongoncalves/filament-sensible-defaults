<?php

use Filament\Actions\Action;
use Filament\Events\ServingFilament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Tables\Filters\SelectFilter;

it('defaults selects to non-native', function () {
    $select = Select::make('country');

    expect($select->isNative())->toBeFalse();
});

it('disables seconds on date-time pickers', function () {
    $picker = DateTimePicker::make('published_at');

    expect($picker->hasSeconds())->toBeFalse()
        ->and($picker->getMaxDate())->toBe('9999-12-31T23:59');
});

it('sets the default action modal width to medium', function () {
    $action = Action::make('save');

    expect($action->getModalWidth())->toBe('md');
});

it('defaults textareas to four rows', function () {
    $textarea = Textarea::make('bio');

    expect($textarea->getRows())->toBe(4);
});

it('defaults select filters to non-native', function () {
    $filter = SelectFilter::make('status');

    expect($filter->isNative())->toBeFalse();
});

it('keeps application configureUsing overrides once Filament is serving', function () {
    Select::configureUsing(fn (Select $select) => $select->native());

    ServingFilament::dispatch();

    expect(Select::make('country')->isNative())->toBeTrue();
});

it('registers a validation-error notification renderer on pages once Filament is serving', function () {
    Page::$reportValidationErrorUsing = null;

    ServingFilament::dispatch();

    expect(Page::$reportValidationErrorUsing)->toBeInstanceOf(Closure::class)
        ->and(Page::$formActionsAreSticky)->toBeFalse();
});

it('keeps an application validation-error renderer once Filament is serving', function () {
    $renderer = function () {};
    Page::$reportValidationErrorUsing = $renderer;

    ServingFilament::dispatch();

    expect(Page::$reportValidationErrorUsing)->toBe($renderer);
});
