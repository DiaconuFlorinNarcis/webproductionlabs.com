<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): JsonResponse
    {
        $tags = DB::table('tags')->get();

        return response()->json($tags);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->find($id));
    }

    public function update(int $id, Request $request): JsonResponse
    {
        /** @var Tag $article */
        $tag = $this->find($id);
        $tag->update(['name' => $request->get('name')]);

        return response()->json($tag);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json(Tag::create(['name' => $request->get('name')]));
    }

    private function find($id)
    {
        try {
            $tags = Tag::findOrFail($id);
        }catch (ModelNotFoundException $exception){
            return response()->json($exception->getMessage());
        }

        return $tags;
    }
}
