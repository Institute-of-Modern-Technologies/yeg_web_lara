<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Stage;
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
        $levels = Level::with(['activities', 'stage'])->orderBy('stage_id')->orderBy('level_number')->get();
        $activities = Activity::orderBy('name')->get();
        $stages = Stage::orderBy('order')->get();
        return view('admin.levels.index', compact('levels', 'activities', 'stages'));
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
                'status' => 'required|in:active,inactive',
                'stage_id' => 'required|exists:stages,id',
                'level_number' => 'required|integer|min:1',
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
            
            $stage = Stage::findOrFail($request->stage_id);
            
            // Generate level name from stage and level number
            $generatedName = $stage->name . ' - Level ' . $request->level_number;
            
            $level = Level::create([
                'name' => $generatedName,
                'slug' => Str::slug($generatedName),
                'status' => $request->status,
                'description' => $request->description,
                'stage_id' => $request->stage_id,
                'level_number' => $request->level_number,
            ]);
            
            // Sync activities if provided
            if ($request->has('activities')) {
                $level->activities()->sync($request->activities);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Level created successfully.',
                    'level' => $level->load(['activities', 'stage'])
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
        $level = Level::with(['activities', 'stage'])->findOrFail($id);
        $activities = Activity::orderBy('name')->get();
        $stages = Stage::orderBy('order')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'level' => $level,
                'activities' => $activities,
                'stages' => $stages
            ]);
        }
        
        return view('admin.levels.edit', compact('level', 'activities', 'stages'));
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
                'status' => 'required|in:active,inactive',
                'stage_id' => 'required|exists:stages,id',
                'level_number' => 'required|integer|min:1',
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
            $level->status = $request->status;
            $level->description = $request->description;
            $level->stage_id = $request->stage_id;
            $level->level_number = $request->level_number;
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
                    'level' => $level->load(['activities', 'stage'])
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
