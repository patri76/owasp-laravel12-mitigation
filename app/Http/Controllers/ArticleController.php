<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//esercitazione framework security
class ArticleController extends Controller
{
    public function index(Request $request)
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
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $search = $validated['search'] ?? '';

        $articles = Article::where('published', true)
            ->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('content', 'LIKE', '%' . $search . '%');
            })
            ->latest()
            ->get();

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article, Request $request)
    {
        if (!$article->published && $article->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->wantsJson()) {
            return response()->json($article);
        }

        return view('articles.show', compact('article'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $articleData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'published' => 'nullable|boolean',
        ]);

        $articleData['user_id'] = Auth::id();
        $articleData['published'] = $request->boolean('published');

        $article = Article::create($articleData);

        if ($request->wantsJson()) {
            return response()->json($article, 201);
        }

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $articleData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'published' => 'nullable|boolean',
        ]);

        $articleData['published'] = $request->boolean('published');

        $article->update($articleData);

        if ($request->wantsJson()) {
            return response()->json($article, 200);
        }

        return redirect()->route('articles.show', $article);
    }

    public function destroy(Article $article, Request $request)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        $article->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('articles.index')
            ->with('message', 'Article deleted successfully');
    }
}
