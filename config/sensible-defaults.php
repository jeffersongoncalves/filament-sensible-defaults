<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Automatic registration
    |--------------------------------------------------------------------------
    |
    | When enabled, the plugin applies the sensible defaults globally during
    | the service provider boot — no panel registration required. Set this to
    | false if you prefer to register the plugin explicitly on a panel
    | (`FilamentSensibleDefaultsPlugin::make()`) and tweak the blocks fluently.
    |
    */

    'auto_register' => true,

    /*
    |--------------------------------------------------------------------------
    | Default blocks
    |--------------------------------------------------------------------------
    |
    | Each boolean toggles a related group of `::configureUsing()` defaults.
    | Turn any of them off to opt out of that block entirely.
    |
    */

    // Field / Entry / Column → translateLabel()
    'translate_labels' => true,

    // Action / ActionGroup / Create|Edit|Delete|View action defaults
    'action_defaults' => true,

    // Select → native(false) + searchable/preload/selectablePlaceholder
    'select_defaults' => true,

    // DateTimePicker → seconds(false) + maxDate()
    'datetime_defaults' => true,

    // FileUpload → moveFiles()
    'fileupload_defaults' => true,

    // Repeater / Builder → delete action requires confirmation
    'repeater_defaults' => true,

    // ToggleButtons / TextInput / Textarea
    'form_defaults' => true,

    // Page → validation-error notification + non-sticky form actions
    'page_defaults' => true,

    // Table / ImageColumn / SelectFilter pagination & UX tweaks
    'table_defaults' => true,

    // Schema & Table display-format defaults (driven by the values below)
    'format_defaults' => true,

    /*
    |--------------------------------------------------------------------------
    | Display formats
    |--------------------------------------------------------------------------
    |
    | Applied to both Schema and Table via their `default*` helpers when the
    | `format_defaults` block is enabled.
    |
    */

    'formats' => [
        'currency' => 'usd',
        'date_display_format' => 'M j, Y',
        'iso_date_display_format' => 'L',
        'datetime_display_format' => 'M j, Y H:i:s',
        'iso_datetime_display_format' => 'LLL',
        'number_locale' => null,
        'time_display_format' => 'H:i:s',
        'iso_time_display_format' => 'LT',
    ],

];
