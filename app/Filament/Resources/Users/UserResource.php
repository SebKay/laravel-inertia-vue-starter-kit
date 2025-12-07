<?php

namespace App\Filament\Resources\Users;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'full_name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
            TextInput::make('first_name')
                ->autofocus()
                ->required()
                ->maxLength(255),

            TextInput::make('last_name')
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('name')
                ->getStateUsing(fn ($record) => $record->fullName)
                ->searchable()
                ->sortable(),

            TextColumn::make('email')
                ->searchable()
                ->sortable(),

            IconColumn::make('email_verified')
                ->getStateUsing(fn ($record) => $record->email_verified_at)
                ->boolean()
                ->sortable(),

            TextColumn::make('roles')
                ->getStateUsing(fn ($record) => $record->roles->pluck('name')->join(', '))
                ->searchable()
                ->sortable(),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
