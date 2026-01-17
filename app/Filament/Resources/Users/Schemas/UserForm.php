<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                            ->password()
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                $component->state('');
                            })
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),

                        DateTimePicker::make('email_verified_at'),

                        Select::make('roles')
                            ->preload()
                            ->multiple()
                            ->relationship('roles', 'name'),
                    ]),
            ]);
    }
}
