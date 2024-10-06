<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Supplier;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use App\Models\PembelianItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianItemResource\Pages;
use App\Filament\Resources\PembelianItemResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms\Components\Hidden;

class PembelianItemResource extends Resource
{
    protected static ?string $model = PembelianItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $pembelian = new Pembelian();
        if (request()->filled('pembelian_id')) {
            $pembelian = Pembelian::find(request('pembelian_id'));
        }

        return $form
            ->schema([
                DatePicker::make('tanggal')
                    ->label('Tanggal Pembelian')
                    ->required()
                    ->default($pembelian->tanggal)
                    ->native(false)
                    ->displayFormat('d-mm-Y')
                    ->disabled()
                    ->columnSpanFull(),
                    
                TextInput::make('supplier_nama')
                    ->label('Supplier')
                    ->required()
                    ->disabled()
                    ->default($pembelian->supplier?->nama_perusahaan),
                TextInput::make('supplier_email')
                    ->label('Email Supplier')
                    ->required()
                    ->disabled()
                    ->default($pembelian->supplier?->email),
                Select::make('barang_id')
                    ->options(
                        Barang::pluck('nama', 'id')
                    )
                    ->required()
                    ->label('Pilih Barang')
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function($state, Set $set){
                        $barang = Barang::find($state);
                        $set('harga',$barang->harga ?? null);
                    }),
                TextInput::make('harga'),
                TextInput::make('jumlah')
                    ->label('Jumlah Barang'),
                Hidden::make('pembelian_id')
                    ->default(request('pembelian_id')),
                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianItems::route('/'),
            'create' => Pages\CreatePembelianItem::route('/create'),
            'edit' => Pages\EditPembelianItem::route('/{record}/edit'),
        ];
    }
}
