<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): JsonResponse
    {
        $articles = DB::table('articles')->paginate(5);

        return response()->json($articles);
    }

    public function show(int $id): JsonResponse
    {
        /** @var Article $article */
        $article = $this->find($id);
        $article->category = $article->category->name;
        $article->tags = $article->tags()->get();

        return response()->json($article);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|min:3',
            ]);
        }catch (ValidationException $exception) {
            return response()->json($exception->getMessage());
        }

        /** @var Article $article */
        $article = $this->find($id);
        $article->update($request->all());

        return response()->json($article);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|min:3',
                'content' => 'required|min:3',
                'description' => 'required|min:3',
            ]);
        }catch (ValidationException $exception) {
            return response()->json($exception->getMessage());
        }

        $article = Article::create(array_merge(['active' => 1], $request->all()));
        return response()->json($article);
    }

    private function find($id)
    {
        try {
            $article = Article::findOrFail($id);
        }catch (ModelNotFoundException $exception){
            return response()->json($exception->getMessage());
        }

        return $article;
    }
}
