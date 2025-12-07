<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->getStateUsing(fn ($record) => $record->fullName)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->tooltip(fn (User $user) => $user->hasVerifiedEmail() ? 'Email Verified' : 'Email Not Verified')
                    ->icon(fn (User $user) => match ($user->hasVerifiedEmail()) {
                        true => Heroicon::OutlinedCheckCircle,
                        false => Heroicon::OutlinedXCircle,
                    })
                    ->iconColor(fn (User $user) => match ($user->hasVerifiedEmail()) {
                        true => 'success',
                        false => 'danger',
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->roles->pluck('name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
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
}
