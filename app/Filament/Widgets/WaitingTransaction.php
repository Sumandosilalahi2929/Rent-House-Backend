<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Filament\Widgets\TableWidget as BaseWidget;

class WaitingTransaction extends BaseWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->whereStatus('waiting')
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable()
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn(string $state): string => match($state){
                    'waiting' => 'gray',
                    'approved' => 'info',
                    'canceled' => 'danger',
                }),

                
            ])->actions([
                Action::make('approve')
                ->button()
                ->color('success')
                ->requiresConfirmation()
                ->action(function(Transaction $transaction){
                     Transaction::find($transaction->id)->update([
                        'status' =>'approved',
                     ]);
                     Notification::make()->success()->title('Transaction Approved!')->body('Transaction has been approved successfully')->icon('heroicon-o-check')->send();
                })
                ->hidden(fn(Transaction $transaction) => $transaction->status !== 'waiting')
            ]);
    }
}
