<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Page;
use App\Support\LocalizedRoutes;
use App\Support\Seo;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $page = Page::published()->where('key', 'blog')->firstOrFail();

        Seo::set(
            title: $page->seo_title,
            description: $page->seo_description,
            alternates: LocalizedRoutes::pageAlternates('blog.index'),
            image: $page->og_image,
        );

        return view('pages.blog.index', [
            'page' => $page,
            'categories' => BlogCategory::active()->ordered()->get(),
            'posts' => BlogPost::query()
                ->with('category')
                ->published()
                ->latest('published_at')
                ->paginate(9),
        ]);
    }

    public function category(string $categorySlug): View
    {
        $locale = app()->getLocale();

        $category = BlogCategory::query()
            ->active()
            ->whereJsonContainsLocale('slug', $locale, $categorySlug)
            ->firstOrFail();

        Seo::set(
            title: $category->title,
            description: $category->description,
            alternates: LocalizedRoutes::blogCategoryAlternates($category),
        );

        return view('pages.blog.index', [
            'page' => Page::published()->where('key', 'blog')->firstOrFail(),
            'activeCategory' => $category,
            'categories' => BlogCategory::active()->ordered()->get(),
            'posts' => BlogPost::query()
                ->with('category')
                ->published()
                ->whereBelongsTo($category, 'category')
                ->latest('published_at')
                ->paginate(9),
        ]);
    }

    public function show(string $postSlug): View
    {
        $locale = app()->getLocale();

        $post = BlogPost::query()
            ->with('category')
            ->published()
            ->whereJsonContainsLocale('slug', $locale, $postSlug)
            ->firstOrFail();

        Seo::set(
            title: $post->title,
            description: $post->excerpt,
            alternates: LocalizedRoutes::blogPostAlternates($post),
            image: $post->image,
        );

        return view('pages.blog.show', [
            'post' => $post,
            'recentPosts' => BlogPost::query()
                ->with('category')
                ->published()
                ->whereKeyNot($post->id)
                ->latest('published_at')
                ->limit(3)
                ->get(),
        ]);
    }
}
