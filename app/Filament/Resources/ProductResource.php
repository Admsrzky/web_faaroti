<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?string $pluralModelLabel = 'Produk';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Details')
                    ->schema([
                        TextInput::make('nama_produk')
                            ->label('Nama Produk')
                            ->required(),
                        TextInput::make('harga')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        FileUpload::make('foto_produk')
                            ->label('Foto Produk')
                            ->directory('products')->image()
                            ->required(),
                    ]),

                Fieldset::make('Description')
                    ->schema([
                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->required(),
                        Select::make('is_popular')
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular',
                            ])
                            ->required(),

                        Select::make('category_id')
                            ->relationship('category', 'nama_kategori')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('stok')
                            ->required()
                            ->numeric()
                            ->prefix('Qty'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->searchable(),
                TextColumn::make('stok')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match (true) {
                        $state < 5 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('category.nama_kategori')
                    ->label('Kategori')
                    ->searchable(),
                ImageColumn::make('foto_produk'),
                IconColumn::make('is_popular')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Popular'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama_kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $lowStockCount = Product::where('stok', '<', 5)->count();

        return $lowStockCount > 0 ? (string) $lowStockCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        if (static::getNavigationBadge() > 0) {
            return 'danger';
        }
        return null;
    }
}
