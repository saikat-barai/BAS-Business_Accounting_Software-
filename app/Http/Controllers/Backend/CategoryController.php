<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function category()
    {
        return view('backend.pages.category');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Category::create(['name' => $request->name]);

        return response()->json(['message' => 'Category created successfully!']);
    }

    public function categoryList()
    {
        try {
            $categories = Category::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Category list fetched successfully.',
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category list.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function categoryById(Request $request)
    {
        $data = Category::where('id', $request->id)->first();
        if ($data !== null) {
            return response()->json([
                'status' => 'success',
                'message' => 'Category Found Successfully.',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Category Not Found.',
            ], 200);
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($request->id),
            ],
        ]);

        $category = Category::find($request->id);

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $category->update([
            'name' => $validated['name'],
        ]);

        return response()->json(['message' => 'Category updated successfully.'], 200);
    }

    public function destroy($id)
    {
        $account = Category::find($id);

        if (!$account) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $account->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
