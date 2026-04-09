<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\Role;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role as SpatieRole;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(6)
            ->components([
                Section::make('Settings')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->maxLength(255),

                        TextInput::make('password')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->disabled(function (?User $record): bool {
                                $user = Auth::user();

                                return $record !== null
                                    && $user instanceof User
                                    && (string) $record->getKey() === (string) $user->getKey();
                            })
                            ->password()
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                $component->state('');
                            })
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),

                        DateTimePicker::make('email_verified_at'),

                        Select::make('roles')
                            ->preload()
                            ->searchable()
                            ->relationship(
                                'roles',
                                'name',
                                modifyQueryUsing: fn (Builder $query) => Auth::user()?->hasRole(Role::SUPER)
                                    ? $query
                                    : $query->where('name', '!=', Role::SUPER->value),
                            )
                            ->getOptionLabelFromRecordUsing(fn (SpatieRole $role): string => Role::tryFrom($role->name)?->getLabel() ?? $role->name),
                    ]),
            ]);
    }
}
