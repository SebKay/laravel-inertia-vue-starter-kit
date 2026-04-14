<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\Role;
use App\Models\User;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->tooltip(fn (User $user) => $user->hasVerifiedEmail() ? 'Email Verified' : 'Email Not Verified')
                    ->icon(fn (User $user) => match ($user->hasVerifiedEmail()) {
                        true => LucideIcon::CircleCheck,
                        false => LucideIcon::CircleX,
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

                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn (User $user): string => $user->isSuspended() ? 'Suspended' : 'Active')
                    ->color(fn (User $user): string => $user->isSuspended() ? 'warning' : 'success'),

                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->nullable()
                    ->placeholder('All')
                    ->trueLabel('Verified')
                    ->falseLabel('Unverified'),

                SelectFilter::make('roles')
                    ->label('Roles')
                    ->relationship('roles', 'name')
                    ->options(Role::values()->all())
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('toggleSuspension')
                    ->label(fn (User $record): string => $record->isSuspended() ? 'Reactivate' : 'Suspend')
                    ->icon(fn (User $record): Heroicon => $record->isSuspended() ? Heroicon::OutlinedArrowPath : Heroicon::OutlinedNoSymbol)
                    ->color(fn (User $record): string => $record->isSuspended() ? 'success' : 'warning')
                    ->requiresConfirmation(fn (User $record): bool => ! $record->isSuspended())
                    ->modalHeading(fn (User $record): string => $record->isSuspended() ? 'Reactivate user' : 'Suspend user')
                    ->modalDescription(fn (User $record): string => $record->isSuspended()
                        ? 'This user will be able to log in again.'
                        : 'This user will no longer be able to start new app sessions.')
                    ->visible(fn (User $record): bool => $record->hasRole(Role::USER))
                    ->authorize(fn (User $record): bool => auth()->user()?->can($record->isSuspended() ? 'reactivate' : 'suspend', $record) ?? false)
                    ->action(function (User $record): void {
                        $wasSuspended = $record->isSuspended();

                        if ($wasSuspended) {
                            $record->reactivate();
                        } else {
                            $record->suspend();
                        }

                        Notification::make()
                            ->title($wasSuspended ? 'User reactivated' : 'User suspended')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
