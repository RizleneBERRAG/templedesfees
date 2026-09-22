<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Resources\Articles\Schemas\ArticleForm;
use App\Filament\Resources\Articles\Tables\ArticlesTable;
use App\Models\Article;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $modelLabel = 'article';

    protected static ?string $pluralModelLabel = 'articles';

    protected static ?string $navigationLabel = 'Articles';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'titre';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    /** Les brouillons et les articles programmes, qui ne sont pas encore en ligne. */
    public static function getNavigationBadge(): ?string
    {
        $enAttente = Article::query()
            ->where(fn ($q) => $q->where('est_publie', false)
                ->orWhereDate('date_publication', '>', now()))
            ->count();

        return $enAttente > 0 ? (string) $enAttente : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Brouillons et articles programmés, pas encore visibles sur le site';
    }

    public static function form(Schema $schema): Schema
    {
        return ArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticlesTable::configure($table);
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
            'index'  => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit'   => EditArticle::route('/{record}/edit'),
        ];
    }
}
