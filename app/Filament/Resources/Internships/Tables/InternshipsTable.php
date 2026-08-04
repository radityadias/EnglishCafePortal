<?php

namespace App\Filament\Resources\Internships\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class InternshipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('position', 'Internship'))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('No. Telp')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Cabang')
                    ->options(Branch::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable(),
                SelectFilter::make('user_id')
                    ->label('Divisi')
                    ->options(Division::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading();
    }
}
