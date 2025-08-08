<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChallengeCategoryController extends Controller
{
    /**
     * Display a listing of the challenge categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = \App\Models\ChallengeCategory::orderBy('display_order')->get();
        return view('admin.challenges.categories.index', compact('categories'));
    }

    /**
     * Store a newly created challenge category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);
        
        // Generate a slug from the name
        $validatedData['slug'] = \Illuminate\Support\Str::slug($validatedData['name']);
        
        // Set display order to be the highest + 1
        $maxOrder = \App\Models\ChallengeCategory::max('display_order') ?? 0;
        $validatedData['display_order'] = $maxOrder + 1;
        
        // Set is_active to true if not present
        $validatedData['is_active'] = $validatedData['is_active'] ?? true;
        
        $category = \App\Models\ChallengeCategory::create($validatedData);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'category' => $category
            ]);
        }
        
        return redirect()->route('admin.challenges.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified challenge category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = \App\Models\ChallengeCategory::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);
        
        // Update the slug only if name has changed
        if ($category->name !== $validatedData['name']) {
            $validatedData['slug'] = \Illuminate\Support\Str::slug($validatedData['name']);
        }
        
        // Set is_active to false if not present
        $validatedData['is_active'] = $validatedData['is_active'] ?? false;
        
        $category->update($validatedData);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'category' => $category
            ]);
        }
        
        return redirect()->route('admin.challenges.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified challenge category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = \App\Models\ChallengeCategory::findOrFail($id);
        
        // Check if category has questions
        if ($category->questions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with questions. Please remove all questions first.'
            ], 422);
        }
        
        $category->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }
    
    /**
     * Update the order of categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:challenge_categories,id',
            'items.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($validatedData['items'] as $item) {
            \App\Models\ChallengeCategory::where('id', $item['id'])
                ->update(['display_order' => $item['order']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Category order updated successfully!'
        ]);
    }
}
