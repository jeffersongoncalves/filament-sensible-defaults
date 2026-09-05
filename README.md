<div class="filament-hidden">

![Filament Sensible Defaults](https://raw.githubusercontent.com/jeffersongoncalves/filament-sensible-defaults/2.x/art/jeffersongoncalves-filament-sensible-defaults.png)

</div>

# Filament Sensible Defaults

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/filament-sensible-defaults.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-sensible-defaults)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-sensible-defaults/fix-php-code-style-issues.yml?branch=2.x&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/filament-sensible-defaults/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3A2.x)
[![Tests](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/filament-sensible-defaults/run-tests.yml?branch=2.x&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/filament-sensible-defaults/actions?query=workflow%3Arun-tests+branch%3A2.x)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/filament-sensible-defaults.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/filament-sensible-defaults)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/filament-sensible-defaults.svg?style=flat-square)](LICENSE.md)

A Filament plugin that applies a curated set of sensible, opinionated UI defaults across your panels — actions, forms, selects, date-time pickers, tables, pages and display formats — so every Resource, Page and Widget inherits consistent behaviour without repeating configuration. Every block is config-driven and can be opted out of individually.

## Compatibility

| Branch  | Filament |
|---------|----------|
| `1.x`   | `^3.0`   |
| `2.x`   | `^4.0`   |
| `3.x`   | `^5.0`   |

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/filament-sensible-defaults:^2.0
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag=filament-sensible-defaults-config
```

## Usage

By default the plugin **auto-registers** and applies every enabled block of defaults globally — no panel wiring required. Just install it and your panels inherit the defaults.

If you prefer explicit, per-panel control, set `auto_register` to `false` in `config/sensible-defaults.php` and register the plugin on your `PanelProvider`:

```php
use JeffersonGoncalves\Filament\SensibleDefaults\FilamentSensibleDefaultsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(
            FilamentSensibleDefaultsPlugin::make()
                ->selectDefaults(false)   // opt a block out fluently
                ->datetimeDefaults()      // or keep one explicitly on
        );
}
```

### What it sets

| Block | Default behaviour |
|-------|-------------------|
| `translate_labels` | `translateLabel()` on every Field, Infolist Entry and Table Column |
| `action_defaults` | Action `modalWidth(Medium)` + `closeModalByClickingAway(false)`; Create/Edit/Delete/View Heroicons + `hiddenLabel()`; `ActionGroup` ellipsis icon |
| `select_defaults` | `Select` → `native(false)`, auto `searchable()`/`preload()` for relationships, `selectablePlaceholder()` when not required |
| `datetime_defaults` | `DateTimePicker` → `seconds(false)` + `maxDate('9999-12-31T23:59')` |
| `fileupload_defaults` | `FileUpload` → `moveFiles()` |
| `repeater_defaults` | `Repeater` / `Builder` delete actions require confirmation |
| `form_defaults` | `ToggleButtons` inline + grouped, `TextInput` `minValue(0)`, `Textarea` `rows(4)` |
| `page_defaults` | Validation errors rendered as a danger Notification; non-sticky form actions |
| `table_defaults` | `filtersFormWidth('md')`, pagination options `[5, 10, 25, 50]`, lazy-loaded image columns, non-native select filters |
| `format_defaults` | Schema & Table currency / date / datetime / time display formats (see `formats` in the config) |

### Configuration

Each block is a boolean toggle and the display formats are fully customisable in `config/sensible-defaults.php`:

```php
return [
    'auto_register' => true,

    'translate_labels' => true,
    'action_defaults' => true,
    'select_defaults' => true,
    'datetime_defaults' => true,
    'fileupload_defaults' => true,
    'repeater_defaults' => true,
    'form_defaults' => true,
    'page_defaults' => true,
    'table_defaults' => true,
    'format_defaults' => true,

    'formats' => [
        'currency' => 'brl',
        'date_display_format' => 'M j, Y',
        'iso_date_display_format' => 'L',
        'datetime_display_format' => 'M j, Y H:i:s',
        'iso_datetime_display_format' => 'LLL',
        'number_locale' => null,
        'time_display_format' => 'H:i:s',
        'iso_time_display_format' => 'LT',
    ],
];
```

### Fine-grained control

Blocks that bundle more than one class also expose a matching sub-array so you can disable a single item without losing the rest of the block. The block switch is a master gate: turning it off disables every sub-item regardless of their own setting.

```php
return [
    // ...

    'actions' => [
        'action_group' => true,
        'action' => true,
        'create_action' => true,
        'edit_action' => true,
        'delete_action' => false, // opt DeleteAction out, keep everything else in action_defaults
        'view_action' => true,
    ],

    'select' => [
        'native' => true,
        'selectable_placeholder' => true,
        'searchable' => true,
        'preload' => true,
    ],

    'datetime' => [
        'seconds' => true,
        'max_date' => true,
    ],

    'repeater' => [
        'repeater' => true,
        'builder' => true,
    ],

    'form' => [
        'toggle_buttons' => true,
        'text_input' => true,
        'textarea' => true,
    ],

    'page' => [
        'validation_notification' => true,
        'sticky_form_actions' => true,
    ],

    'table' => [
        'table' => true,
        'image_column' => true,
        'select_filter' => true,
    ],
];
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Goncalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
