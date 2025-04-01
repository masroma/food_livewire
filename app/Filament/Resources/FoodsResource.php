<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodsResource\Pages;
use App\Filament\Resources\FoodsResource\RelationManagers;
use App\Models\Foods;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodsResource extends Resource
{
    protected static ?string $model = Foods::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('foods')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->columnSpanFull()
                    ->prefix('Rp')
                    ->reactive()
                    ->numeric(),
                Forms\Components\Toggle::make('is_promo')
                ->columnSpanFull()
                    ->reactive(),
              
                Forms\Components\Select::make('percent')
                    ->options([
                        10 => '10%',
                        25 => '25%',
                        35 => '35%',
                        50 => '50%',
                    ])
                    ->columnSpanFull()
                    ->reactive() // Reactive to trigger changes on other fields
                    ->hidden(fn($get) => !$get('is_promo'))
                    ->afterStateUpdated(function ($set, $get, $state) {
                        if ($get('is_promo') && $get('price') && $get('percent')) {
                            $discount = ($get('price') * (int)$get('percent')) / 100;
                            $set('price_afterdiscount', $get('price') - $discount);
                        } else {
                            $set('price_afterdiscount', $get('price'));
                        }
                    }), // Only active if is_promo is true
                
                Forms\Components\TextInput::make('price_afterdiscount')
                    ->numeric()
                    ->columnSpanFull()
                    ->prefix('Rp')
                    ->readOnly()
                    ->hidden(fn($get) => !$get('is_promo')),
                Forms\Components\Select::make('category_id')
                    ->columnSpanFull()
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('price')
                ->sortable()
                ->formatStateUsing(fn (string $state): string => 'Rp ' . number_format($state, 0, ',', '.')),
            
            Tables\Columns\TextColumn::make('price_afterdiscount')
                ->sortable()
                ->formatStateUsing(fn (string $state): string => 'Rp ' . number_format($state, 0, ',', '.')),
            
                Tables\Columns\TextColumn::make('percent')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => $state . '%'),
                
                Tables\Columns\TextColumn::make('is_promo')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => $state == '1' ? 'Ya' : ($state == '0' ? 'Tidak' : $state))
                    ->badge()
                    ->color(fn (string $state): string => $state == '1' ? 'success' : ($state == '0' ? 'danger' : 'gray')),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListFoods::route('/'),
            'create' => Pages\CreateFoods::route('/create'),
            'edit' => Pages\EditFoods::route('/{record}/edit'),
        ];
    }
}
