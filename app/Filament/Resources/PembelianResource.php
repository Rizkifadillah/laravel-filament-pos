<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use App\Models\Supplier;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PembelianResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianResource\RelationManagers;
use Filament\Forms\Components\DatePicker;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Data Pembelian';
    protected static ?string $pluralLabel = 'Data Pembelian'; // untuk label di sidebar


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('tanggal')
                    ->label('Tanggal Pembelian')
                    ->required()
                    ->default(now())
                    ->native(false)
                    ->displayFormat('d-mm-Y')
                    ->columnSpanFull(),
                Select::make('supplier_id')
                    ->options(
                        Supplier::pluck('nama_perusahaan', 'id')
                    )
                    ->required()
                    ->label('Pilih Supplier')
                    ->searchable()
                    ->createOptionForm(
                        SupplierResource::getForm()
                    )
                    ->createOptionUsing(function (array $data): int {
                        return Supplier::create($data)->id;
                    })
                    ->reactive()
                    ->afterStateUpdated(function($state, Set $set){
                        $supplier = Supplier::find($state);
                        $set('email',$supplier->email ?? null);
                    }),
                TextInput::make('email')->disabled()
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}
