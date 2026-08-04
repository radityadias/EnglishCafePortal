<?php

namespace App\Filament\Resources\Employees\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use app\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Builder;
class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('position', 'Karyawan'))
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employeeProfile.phone')
                    ->label('No. Telp')
                    ->searchable()
                    ->sortable(),

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
                Action::make('whatsapp')
                    ->icon('selfhst-whatsapp')
                    ->url(function (User $record) {
                        $phone = $record->phone;
                    return "https://wa.me/{$phone}";
                    }),
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
