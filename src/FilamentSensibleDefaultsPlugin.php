<?php

namespace JeffersonGoncalves\Filament\SensibleDefaults;

use Filament\Actions;
use Filament\Contracts\Plugin;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications;
use Filament\Pages;
use Filament\Panel;
use Filament\Tables;
use Illuminate\Validation\ValidationException;

class FilamentSensibleDefaultsPlugin implements Plugin
{
    protected ?bool $translateLabels = null;

    protected ?bool $actionDefaults = null;

    protected ?bool $selectDefaults = null;

    protected ?bool $datetimeDefaults = null;

    protected ?bool $fileUploadDefaults = null;

    protected ?bool $repeaterDefaults = null;

    protected ?bool $formDefaults = null;

    protected ?bool $pageDefaults = null;

    protected ?bool $tableDefaults = null;

    protected ?bool $formatDefaults = null;

    public function getId(): string
    {
        return 'filament-sensible-defaults';
    }

    public function register(Panel $panel): void
    {
        $this->apply();
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function translateLabels(bool $condition = true): static
    {
        $this->translateLabels = $condition;

        return $this;
    }

    public function actionDefaults(bool $condition = true): static
    {
        $this->actionDefaults = $condition;

        return $this;
    }

    public function selectDefaults(bool $condition = true): static
    {
        $this->selectDefaults = $condition;

        return $this;
    }

    public function datetimeDefaults(bool $condition = true): static
    {
        $this->datetimeDefaults = $condition;

        return $this;
    }

    public function fileUploadDefaults(bool $condition = true): static
    {
        $this->fileUploadDefaults = $condition;

        return $this;
    }

    public function repeaterDefaults(bool $condition = true): static
    {
        $this->repeaterDefaults = $condition;

        return $this;
    }

    public function formDefaults(bool $condition = true): static
    {
        $this->formDefaults = $condition;

        return $this;
    }

    public function pageDefaults(bool $condition = true): static
    {
        $this->pageDefaults = $condition;

        return $this;
    }

    public function tableDefaults(bool $condition = true): static
    {
        $this->tableDefaults = $condition;

        return $this;
    }

    public function formatDefaults(bool $condition = true): static
    {
        $this->formatDefaults = $condition;

        return $this;
    }

    /**
     * Apply every enabled block of sensible defaults via Filament's
     * `::configureUsing()` registries. These are panel-agnostic (global) by
     * design, mirroring how the source application set them in its
     * AppServiceProvider.
     */
    public function apply(): void
    {
        if ($this->isEnabled($this->translateLabels, 'translate_labels')) {
            $this->applyTranslateLabels();
        }

        if ($this->isEnabled($this->actionDefaults, 'action_defaults')) {
            $this->applyActionDefaults();
        }

        if ($this->isEnabled($this->selectDefaults, 'select_defaults')) {
            $this->applySelectDefaults();
        }

        if ($this->isEnabled($this->datetimeDefaults, 'datetime_defaults')) {
            $this->applyDateTimeDefaults();
        }

        if ($this->isEnabled($this->fileUploadDefaults, 'fileupload_defaults')) {
            $this->applyFileUploadDefaults();
        }

        if ($this->isEnabled($this->repeaterDefaults, 'repeater_defaults')) {
            $this->applyRepeaterDefaults();
        }

        if ($this->isEnabled($this->formDefaults, 'form_defaults')) {
            $this->applyFormDefaults();
        }

        if ($this->isEnabled($this->pageDefaults, 'page_defaults')) {
            $this->applyPageDefaults();
        }

        if ($this->isEnabled($this->tableDefaults, 'table_defaults')) {
            $this->applyTableDefaults();
        }

        if ($this->isEnabled($this->formatDefaults, 'format_defaults')) {
            $this->applyFormatDefaults();
        }
    }

    protected function isEnabled(?bool $override, string $key): bool
    {
        return $override ?? (bool) config("sensible-defaults.{$key}", true);
    }

    protected function applyTranslateLabels(): void
    {
        Forms\Components\Field::configureUsing(function (Forms\Components\Field $field) {
            return $field->translateLabel();
        });

        Infolists\Components\Entry::configureUsing(function (Infolists\Components\Entry $entry) {
            return $entry->translateLabel();
        });

        Tables\Columns\Column::configureUsing(function (Tables\Columns\Column $column) {
            return $column->translateLabel();
        });
    }

    protected function applyActionDefaults(): void
    {
        Actions\ActionGroup::configureUsing(function (Actions\ActionGroup $action) {
            return $action->icon('heroicon-m-ellipsis-vertical');
        });

        Actions\Action::configureUsing(function (Actions\Action $action) {
            return $action
                ->translateLabel()
                ->modalWidth('md')
                ->closeModalByClickingAway(false);
        });

        Actions\CreateAction::configureUsing(function (Actions\CreateAction $action) {
            return $action
                ->icon('heroicon-m-plus')
                ->hiddenLabel()
                ->createAnother(false);
        });

        Actions\EditAction::configureUsing(function (Actions\EditAction $action) {
            return $action
                ->icon('heroicon-m-pencil-square')
                ->hiddenLabel()
                ->button();
        });

        Actions\DeleteAction::configureUsing(function (Actions\DeleteAction $action) {
            return $action
                ->icon('heroicon-m-trash')
                ->hiddenLabel()
                ->button();
        });

        Actions\ViewAction::configureUsing(function (Actions\ViewAction $action) {
            return $action
                ->icon('heroicon-m-eye')
                ->hiddenLabel()
                ->button();
        });
    }

    protected function applySelectDefaults(): void
    {
        Forms\Components\Select::configureUsing(function (Forms\Components\Select $component) {
            return $component
                ->native(false)
                ->selectablePlaceholder(function (Forms\Components\Select $component) {
                    return ! $component->isRequired();
                })
                ->searchable(function (Forms\Components\Select $component) {
                    return $component->hasRelationship();
                })
                ->preload(function (Forms\Components\Select $component) {
                    return $component->isSearchable();
                });
        });
    }

    protected function applyDateTimeDefaults(): void
    {
        Forms\Components\DateTimePicker::configureUsing(function (Forms\Components\DateTimePicker $component) {
            return $component
                ->seconds(false)
                ->maxDate('9999-12-31T23:59');
        });
    }

    protected function applyFileUploadDefaults(): void
    {
        Forms\Components\FileUpload::configureUsing(function (Forms\Components\FileUpload $component) {
            return $component->moveFiles();
        });
    }

    protected function applyRepeaterDefaults(): void
    {
        Forms\Components\Repeater::configureUsing(function (Forms\Components\Repeater $component) {
            return $component->deleteAction(function (Actions\Action $action) {
                return $action->requiresConfirmation();
            });
        });

        Forms\Components\Builder::configureUsing(function (Forms\Components\Builder $component) {
            return $component->deleteAction(function (Actions\Action $action) {
                return $action->requiresConfirmation();
            });
        });
    }

    protected function applyFormDefaults(): void
    {
        Forms\Components\ToggleButtons::configureUsing(function (Forms\Components\ToggleButtons $component) {
            return $component
                ->inline()
                ->grouped();
        });

        Forms\Components\TextInput::configureUsing(function (Forms\Components\TextInput $component) {
            return $component->minValue(0);
        });

        Forms\Components\Textarea::configureUsing(function (Forms\Components\Textarea $component) {
            return $component->rows(4);
        });
    }

    protected function applyPageDefaults(): void
    {
        Pages\Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notifications\Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };

        Pages\Page::$formActionsAreSticky = false;
    }

    protected function applyTableDefaults(): void
    {
        Tables\Table::configureUsing(function (Tables\Table $table) {
            return $table
                ->filtersFormWidth('md')
                ->paginationPageOptions([5, 10, 25, 50]);
        });

        Tables\Columns\ImageColumn::configureUsing(function (Tables\Columns\ImageColumn $column) {
            return $column->extraImgAttributes(['loading' => 'lazy']);
        });

        Tables\Filters\SelectFilter::configureUsing(function (Tables\Filters\SelectFilter $filter) {
            return $filter->native(false);
        });
    }

    protected function applyFormatDefaults(): void
    {
        $formats = config('sensible-defaults.formats', []);

        // Filament 3 has no unified Schema class and exposes display-format
        // defaults through static properties on the Infolist and the Table.
        // The ISO display formats only exist from Filament 4 onwards, so they
        // are intentionally omitted here.
        Infolists\Infolist::$defaultCurrency = $formats['currency'] ?? 'usd';
        Infolists\Infolist::$defaultDateDisplayFormat = $formats['date_display_format'] ?? 'M j, Y';
        Infolists\Infolist::$defaultDateTimeDisplayFormat = $formats['datetime_display_format'] ?? 'M j, Y H:i:s';
        Infolists\Infolist::$defaultTimeDisplayFormat = $formats['time_display_format'] ?? 'H:i:s';
        Infolists\Infolist::$defaultNumberLocale = $formats['number_locale'] ?? null;

        Tables\Table::$defaultCurrency = $formats['currency'] ?? 'usd';
        Tables\Table::$defaultDateDisplayFormat = $formats['date_display_format'] ?? 'M j, Y';
        Tables\Table::$defaultDateTimeDisplayFormat = $formats['datetime_display_format'] ?? 'M j, Y H:i:s';
        Tables\Table::$defaultTimeDisplayFormat = $formats['time_display_format'] ?? 'H:i:s';
        Tables\Table::$defaultNumberLocale = $formats['number_locale'] ?? null;
    }
}
