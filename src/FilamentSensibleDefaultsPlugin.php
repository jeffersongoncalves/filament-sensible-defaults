<?php

namespace JeffersonGoncalves\Filament\SensibleDefaults;

use Closure;
use Filament\Actions;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications;
use Filament\Pages;
use Filament\Panel;
use Filament\Support\Components\ComponentManager;
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

    /**
     * Same registry `Component::configureUsing()` writes to, but keyed by a
     * class-string so the component class isn't autoloaded at boot. Callbacks
     * are still registered during boot, so later application-level
     * `configureUsing()` overrides keep winning.
     */
    protected function configureUsing(string $component, Closure $modifyUsing): void
    {
        ComponentManager::resolve()->configureUsing($component, $modifyUsing);
    }

    protected function isItemEnabled(string $group, string $item): bool
    {
        return (bool) config("sensible-defaults.{$group}.{$item}", true);
    }

    protected function applyTranslateLabels(): void
    {
        $this->configureUsing(Forms\Components\Field::class, function (Forms\Components\Field $field) {
            return $field->translateLabel();
        });

        $this->configureUsing(Infolists\Components\Entry::class, function (Infolists\Components\Entry $entry) {
            return $entry->translateLabel();
        });

        $this->configureUsing(Tables\Columns\Column::class, function (Tables\Columns\Column $column) {
            return $column->translateLabel();
        });
    }

    protected function applyActionDefaults(): void
    {
        if ($this->isItemEnabled('actions', 'action_group')) {
            $this->configureUsing(Actions\ActionGroup::class, function (Actions\ActionGroup $action) {
                return $action->icon('heroicon-m-ellipsis-vertical');
            });
        }

        if ($this->isItemEnabled('actions', 'action')) {
            $this->configureUsing(Actions\Action::class, function (Actions\Action $action) {
                return $action
                    ->translateLabel()
                    ->modalWidth('md')
                    ->closeModalByClickingAway(false);
            });
        }

        if ($this->isItemEnabled('actions', 'create_action')) {
            $this->configureUsing(Actions\CreateAction::class, function (Actions\CreateAction $action) {
                return $action
                    ->icon('heroicon-m-plus')
                    ->hiddenLabel()
                    ->createAnother(false);
            });
        }

        if ($this->isItemEnabled('actions', 'edit_action')) {
            $this->configureUsing(Actions\EditAction::class, function (Actions\EditAction $action) {
                return $action
                    ->icon('heroicon-m-pencil-square')
                    ->hiddenLabel()
                    ->button();
            });
        }

        if ($this->isItemEnabled('actions', 'delete_action')) {
            $this->configureUsing(Actions\DeleteAction::class, function (Actions\DeleteAction $action) {
                return $action
                    ->icon('heroicon-m-trash')
                    ->hiddenLabel()
                    ->button();
            });
        }

        if ($this->isItemEnabled('actions', 'view_action')) {
            $this->configureUsing(Actions\ViewAction::class, function (Actions\ViewAction $action) {
                return $action
                    ->icon('heroicon-m-eye')
                    ->hiddenLabel()
                    ->button();
            });
        }
    }

    protected function applySelectDefaults(): void
    {
        $this->configureUsing(Forms\Components\Select::class, function (Forms\Components\Select $component) {
            if ($this->isItemEnabled('select', 'native')) {
                $component->native(false);
            }

            if ($this->isItemEnabled('select', 'selectable_placeholder')) {
                $component->selectablePlaceholder(function (Forms\Components\Select $component) {
                    return ! $component->isRequired();
                });
            }

            if ($this->isItemEnabled('select', 'searchable')) {
                $component->searchable(function (Forms\Components\Select $component) {
                    return $component->hasRelationship();
                });
            }

            if ($this->isItemEnabled('select', 'preload')) {
                $component->preload(function (Forms\Components\Select $component) {
                    return $component->isSearchable();
                });
            }

            return $component;
        });
    }

    protected function applyDateTimeDefaults(): void
    {
        $this->configureUsing(Forms\Components\DateTimePicker::class, function (Forms\Components\DateTimePicker $component) {
            if ($this->isItemEnabled('datetime', 'seconds')) {
                $component->seconds(false);
            }

            if ($this->isItemEnabled('datetime', 'max_date')) {
                $component->maxDate('9999-12-31T23:59');
            }

            return $component;
        });
    }

    protected function applyFileUploadDefaults(): void
    {
        $this->configureUsing(Forms\Components\FileUpload::class, function (Forms\Components\FileUpload $component) {
            return $component->moveFiles();
        });
    }

    protected function applyRepeaterDefaults(): void
    {
        if ($this->isItemEnabled('repeater', 'repeater')) {
            $this->configureUsing(Forms\Components\Repeater::class, function (Forms\Components\Repeater $component) {
                return $component->deleteAction(function (Actions\Action $action) {
                    return $action->requiresConfirmation();
                });
            });
        }

        if ($this->isItemEnabled('repeater', 'builder')) {
            $this->configureUsing(Forms\Components\Builder::class, function (Forms\Components\Builder $component) {
                return $component->deleteAction(function (Actions\Action $action) {
                    return $action->requiresConfirmation();
                });
            });
        }
    }

    protected function applyFormDefaults(): void
    {
        if ($this->isItemEnabled('form', 'toggle_buttons')) {
            $this->configureUsing(Forms\Components\ToggleButtons::class, function (Forms\Components\ToggleButtons $component) {
                return $component
                    ->inline()
                    ->grouped();
            });
        }

        if ($this->isItemEnabled('form', 'text_input')) {
            $this->configureUsing(Forms\Components\TextInput::class, function (Forms\Components\TextInput $component) {
                return $component->minValue(0);
            });
        }

        if ($this->isItemEnabled('form', 'textarea')) {
            $this->configureUsing(Forms\Components\Textarea::class, function (Forms\Components\Textarea $component) {
                return $component->rows(4);
            });
        }
    }

    protected function applyPageDefaults(): void
    {
        // Pages only exist inside a panel, so these statics are set once
        // Filament is serving, without loading the Page class at boot.
        // `??=` keeps a renderer the application registered during boot.
        // `formActionsAreSticky` is left alone: Filament already defaults it
        // to false, and re-setting it here would clobber app overrides.
        if ($this->isItemEnabled('page', 'validation_notification')) {
            Filament::serving(function () {
                Pages\Page::$reportValidationErrorUsing ??= function (ValidationException $exception) {
                    Notifications\Notification::make()
                        ->title($exception->getMessage())
                        ->danger()
                        ->send();
                };
            });
        }
    }

    protected function applyTableDefaults(): void
    {
        if ($this->isItemEnabled('table', 'table')) {
            $this->configureUsing(Tables\Table::class, function (Tables\Table $table) {
                return $table
                    ->filtersFormWidth('md')
                    ->paginationPageOptions([5, 10, 25, 50]);
            });
        }

        if ($this->isItemEnabled('table', 'image_column')) {
            $this->configureUsing(Tables\Columns\ImageColumn::class, function (Tables\Columns\ImageColumn $column) {
                return $column->extraImgAttributes(['loading' => 'lazy']);
            });
        }

        if ($this->isItemEnabled('table', 'select_filter')) {
            $this->configureUsing(Tables\Filters\SelectFilter::class, function (Tables\Filters\SelectFilter $filter) {
                return $filter->native(false);
            });
        }
    }

    protected function applyFormatDefaults(): void
    {
        $formats = config('sensible-defaults.formats', []);

        // Filament 3 has no unified Schema class and exposes display-format
        // defaults through static properties on the Infolist and the Table.
        // The ISO display formats only exist from Filament 4 onwards, so they
        // are intentionally omitted here.
        // ponytail: statics autoload Infolist/Table at boot; Filament 4+ moves
        // these to configureUsing() and avoids that.
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
