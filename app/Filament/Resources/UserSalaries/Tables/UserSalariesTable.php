<?php

namespace App\Filament\Resources\UserSalaries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UserSalariesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('month')
                    ->label('Salary Month')
                    ->date('F Y')
                    ->sortable(),
                TextColumn::make('base_salary')
                    ->label('Base Salary')
                    ->money('PKR')
                    ->sortable(),
                TextColumn::make('hospitals_count')
                    ->label('Hospitals')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('commission_per_hospital')
                    ->label('Commission/Hospital')
                    ->money('PKR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('commission_amount')
                    ->label('Total Commission')
                    ->money('PKR')
                    ->sortable(),
                TextColumn::make('bonus')
                    ->label('Bonus')
                    ->money('PKR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('deductions')
                    ->label('Deductions')
                    ->money('PKR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('total_salary')
                    ->label('Total Salary')
                    ->money('PKR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('paid_date')
                    ->label('Payment Date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Employee')
                    ->relationship('user', 'name')
                    ->multiple()
                    ->preload(),
                SelectFilter::make('is_paid')
                    ->label('Payment Status')
                    ->options([
                        '0' => 'Unpaid',
                        '1' => 'Paid',
                    ]),
                TrashedFilter::make(),
            ])
            ->defaultSort('month', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
