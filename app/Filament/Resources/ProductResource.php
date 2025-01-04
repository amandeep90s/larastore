<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnums;
use App\Enums\RolesEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-s-queue-list';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->schema([
                    TextInput::make('title')
                        ->live(onBlur: true)
                        ->required()
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            $set('slug', Str::slug($state));
                        }),
                    TextInput::make('slug')->required(),
                    Select::make('department_id')
                        ->relationship('department', 'name')
                        ->label(__('Department'))
                        ->preload()
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                            $set('category_id', null);
                        }),
                    Select::make('category_id')
                        ->relationship(
                            name: 'category',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query, callable $get) {
                                $department = $get('department_id');
                                if ($department) {
                                    $query->where('department_id', $department);
                                }
                            })
                        ->label(__('Category'))
                        ->preload()
                        ->searchable()
                        ->required(),
                ]),
                RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                    'blockquote',
                    'bold',
                    'bulletList',
                    'h2',
                    'h3',
                    'italic',
                    'link',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                    'table',
                    'clean',
                ])
                    ->columnSpan(2),
                TextInput::make('price')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->integer(),
                Select::make('status')
                    ->options(ProductStatusEnums::labels())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->limit(1)
                    ->conversion('thumb')
                    ->label(__('Image')),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->words(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors(ProductStatusEnums::colors()),
                Tables\Columns\TextColumn::make('department.name'),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
            'images' => Pages\ProductImages::route('/{record}/images'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditProduct::class,
            Pages\ProductImages::class,
        ]);
    }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();

        return $user && $user->hasRole(RolesEnum::Vendor);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
