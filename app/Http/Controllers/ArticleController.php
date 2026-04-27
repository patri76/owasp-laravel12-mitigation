<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\HtmlFilterService;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index(Request $request, HtmlFilterService $htmlFilterService)
    {
        $articles = Article::latest()
            ->where('published', true)
            ->take(6)
            ->get();

        if ($request->wantsJson()) {
            return response()->json($articles);
        }

        return view('articles.index', compact('articles'));
    }

    public function search(Request $request)
    {
        // SECURE - la mitigazione SQL Injection
        $articles = Article::where('title', 'LIKE', '%' . $request->search . '%')
            ->orWhere('content', 'LIKE', '%' . $request->search . '%')
            ->get();

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json($article);
        }

        return view('articles.show', compact('article'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request/*, HtmlFilterService $htmlFilterService */)
    {
        $articleData = $request->all();

        if (!key_exists('user_id', $articleData)) {
            $articleData['user_id'] = Auth::id();
        }

        $article = Article::create($articleData);

        if ($request->wantsJson()) {
            return response()->json($article, 201);
        }

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article/*, HtmlFilterService $htmlFilterService */)
    {
        $articleData = $request->all();

        $article->update($articleData);

        if ($request->wantsJson()) {
            return response()->json($article, 200);
        }

        return redirect()->route('articles.show', $article);
    }

    public function destroy(Article $article, Request $request)
    {
        $article->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('articles.index')->with('message', 'Article deleted successfully');
    }
}
