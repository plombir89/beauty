<?php

namespace App\Filament\Resources\BlogPosts;

use App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks\YouTubeVideoBlock;
use App\Filament\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPosts\Pages\EditBlogPost;
use App\Filament\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Filament\Support\Fields;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'blog post';

    protected static ?string $pluralModelLabel = 'blog posts';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fields::relationshipSelect('blog_category_id', 'category', 'title', 'Category')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->seconds(false)
                    ->helperText('If this date is in the future, the post is scheduled and will not appear on the public site yet.'),
                Toggle::make('is_published')
                    ->default(false)
                    ->required(),
                Fields::imageUpload('image', 'img/uploads/blog')
                    ->columnSpan(1),
                Fields::translations([
                    ['name' => 'title', 'label' => 'Title', 'required' => true, 'slugTarget' => 'slug'],
                    ['name' => 'slug', 'label' => 'Slug', 'required' => true],
                    ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'rows' => 3, 'full' => true],
                    ['name' => 'body', 'label' => 'Body paragraphs', 'type' => 'rich-editor', 'full' => true, 'fileAttachmentsDirectory' => 'img/uploads/blog/content', 'customBlocks' => [YouTubeVideoBlock::class]],
                ]),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Fields::imageColumn(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.title')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_published'),
                IconColumn::make('is_live')
                    ->label('Live')
                    ->boolean()
                    ->getStateUsing(fn (BlogPost $record): bool => $record->is_published && $record->published_at?->lte(now()) === true),
            ])
            ->filters([
                TernaryFilter::make('is_published'),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit' => EditBlogPost::route('/{record}/edit'),
        ];
    }
}
