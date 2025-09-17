<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Widgets\Widget;

class NewOrderModal extends Widget implements HasForms, HasActions, HasInfolists
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithInfolists;

    protected static string $view = 'filament.widgets.new-order-modal';

    public ?Transaction $newOrder = null;

    public function mount(): void
    {
        if (session()->has('new_order_id')) {
            $this->newOrder = Transaction::find(session('new_order_id'));
        }
    }

    public function newOrderInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->newOrder)
            ->schema([
                TextEntry::make('trx_id')->label('ID Pesanan'),
                TextEntry::make('name')->label('Nama Pelanggan'),
                TextEntry::make('grand_total')->money('IDR'),
            ]);
    }

    public function viewOrderAction(): Action
    {
        return Action::make('viewOrder')
            ->infolist(fn (Infolist $infolist) => $this->newOrderInfolist($infolist))
            ->modalHeading('Pesanan Baru Diterima!')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup');
    }
}
