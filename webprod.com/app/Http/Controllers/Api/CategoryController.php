<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): JsonResponse
    {
        $articles = DB::table('categories')->get();

        return response()->json($articles);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->find($id));
    }

    public function update(int $id, Request $request): JsonResponse
    {
        /** @var Category $article */
        $category = $this->find($id);
        $category->update(['name' => $request->get('name')]);

        return response()->json($category);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Category::create(['name' => $request->get('name')]));
    }

    private function find($id)
    {
        try {
            $category = Category::findOrFail($id);
        }catch (ModelNotFoundException $exception){
            return response()->json($exception->getMessage());
        }

        return $category;
    }
}
