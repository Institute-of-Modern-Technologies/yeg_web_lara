<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LevelController extends Controller
{
    /**
     * Display a listing of the levels.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = Level::with('activities')->orderBy('name')->get();
        $activities = Activity::orderBy('name')->get();
        return view('admin.levels.index', compact('levels', 'activities'));
    }

    /**
     * Store a newly created level in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255|unique:levels',
                'status' => 'required|in:active,inactive',
                'activities' => 'nullable|array',
                'activities.*' => 'exists:activities,id',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return back()->withErrors($validator)->withInput();
            }
            
            $level = Level::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status,
                'description' => $request->description,
            ]);
            
            // Sync activities if provided
            if ($request->has('activities')) {
                $level->activities()->sync($request->activities);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Level created successfully.',
                    'level' => $level->load('activities')
                ]);
            }

            return redirect()->route('admin.levels.index')
                ->with('success', 'Level created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the level.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while creating the level.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified level.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $level = Level::with('activities')->findOrFail($id);
        $activities = Activity::orderBy('name')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'level' => $level,
                'activities' => $activities
            ]);
        }
        
        return view('admin.levels.edit', compact('level', 'activities'));
    }

    /**
     * Update the specified level in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255|unique:levels,name,'.$id,
                'status' => 'required|in:active,inactive',
                'activities' => 'nullable|array',
                'activities.*' => 'exists:activities,id',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return back()->withErrors($validator)->withInput();
            }
            
            $level = Level::findOrFail($id);
            $level->name = $request->name;
            $level->slug = Str::slug($request->name);
            $level->status = $request->status;
            $level->description = $request->description;
            $level->save();
            
            // Sync activities if provided
            if ($request->has('activities')) {
                $level->activities()->sync($request->activities);
            } else {
                $level->activities()->detach();
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Level updated successfully.',
                    'level' => $level->load('activities')
                ]);
            }

            return redirect()->route('admin.levels.index')
                ->with('success', 'Level updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the level.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while updating the level.')
                ->withInput();
        }
    }

    /**
     * Remove the specified level from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $level = Level::findOrFail($id);
            
            // Detach all activities before deleting
            $level->activities()->detach();
            $level->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Level deleted successfully.'
                ]);
            }

            return redirect()->route('admin.levels.index')
                ->with('success', 'Level deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the level.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while deleting the level.');
        }
    }
}
