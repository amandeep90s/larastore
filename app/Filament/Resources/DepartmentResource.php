<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Str;

class DepartmentResource extends Resource
{
  protected static ?string $model = Department::class;

  protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('name')
          ->live(onBlur: true)
          ->required()
          ->afterStateUpdated(function (string $operation, $state, callable $set) {
            $set('slug', Str::slug($state));
          }),
        TextInput::make('slug')->required(),
        Checkbox::make('active'),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
        Tables\Columns\TextColumn::make('slug'),
        Tables\Columns\IconColumn::make('active')->boolean(),
      ])
      ->defaultSort('created_at', 'desc')
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
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
      RelationManagers\CategoriesRelationManager::class
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListDepartments::route('/'),
      'create' => Pages\CreateDepartment::route('/create'),
      'edit' => Pages\EditDepartment::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    $user = Filament::auth()->user();

    return $user && $user->hasRole(RolesEnum::Admin);
  }
}