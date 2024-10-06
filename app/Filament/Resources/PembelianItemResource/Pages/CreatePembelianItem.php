<?php

namespace App\Filament\Resources\PembelianItemResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PembelianItemResource;
use App\Filament\Resources\PembelianItemResource\Widgets\PembelianItemWidget;
use App\Models\PembelianItem;

class CreatePembelianItem extends CreateRecord
{
    protected static string $resource = PembelianItemResource::class;

        // memodifikasi tombol create
        protected function getFormActions(): array
        {
            return [
                Action::make('create')
                ->label('Simpan')
                ->submit('create')
                ->keyBindings(['mod+s']),
            ];
        }
    
        // Memodifikasi redirect setelah success create
        protected function getRedirectUrl(): string
        {
            $id = $this->record->pembelian_id;
            return route(
                'filament.admin.resources.pembelian-items.create',
                [
                    'pembelian_id' => $id
                ]
                
            );
        }

        
        public function getFooterWidgetsColumns(): int | array{
            return 1;
        }


        public function getFooterWidgets(): array{
            return[
                PembelianItemWidget::make([
                    'record'=> request('pembelian_id')
                ])
            ];
        }
    
}
