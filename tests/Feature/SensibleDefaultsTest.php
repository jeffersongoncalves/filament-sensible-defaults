<?php

use Filament\Actions\Action;
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

it('registers a validation-error notification renderer on pages', function () {
    expect(Page::$reportValidationErrorUsing)->toBeInstanceOf(Closure::class)
        ->and(Page::$formActionsAreSticky)->toBeFalse();
});
