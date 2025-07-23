<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StageController extends Controller
{
    /**
     * Display a listing of the stages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stages = Stage::with('activities')->orderBy('order')->get();
        $activities = Activity::orderBy('name')->get();
        return view('admin.stages.index', compact('stages', 'activities'));
    }

    /**
     * Store a newly created stage in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255|unique:stages',
                'status' => 'required|in:active,inactive',
                'activities' => 'nullable|array',
                'activities.*' => 'exists:activities,id',
                'description' => 'nullable|string',
                'order' => 'nullable|integer',
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
            
            $stage = Stage::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status,
                'description' => $request->description,
                'order' => $request->order ?? 0,
            ]);
            
            // Sync activities if provided
            if ($request->has('activities')) {
                $stage->activities()->sync($request->activities);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stage created successfully.',
                    'stage' => $stage->load('activities')
                ]);
            }

            return redirect()->route('admin.stages.index')
                ->with('success', 'Stage created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the stage.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while creating the stage.')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified stage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stage = Stage::with('activities')->findOrFail($id);
        $activities = Activity::orderBy('name')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'stage' => $stage,
                'activities' => $activities
            ]);
        }
        
        return view('admin.stages.edit', compact('stage', 'activities'));
    }

    /**
     * Update the specified stage in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255|unique:stages,name,'.$id,
                'status' => 'required|in:active,inactive',
                'activities' => 'nullable|array',
                'activities.*' => 'exists:activities,id',
                'description' => 'nullable|string',
                'order' => 'nullable|integer',
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
            
            $stage = Stage::findOrFail($id);
            $stage->name = $request->name;
            $stage->slug = Str::slug($request->name);
            $stage->status = $request->status;
            $stage->description = $request->description;
            if ($request->has('order')) {
                $stage->order = $request->order;
            }
            $stage->save();
            
            // Sync activities if provided
            if ($request->has('activities')) {
                $stage->activities()->sync($request->activities);
            } else {
                $stage->activities()->detach();
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stage updated successfully.',
                    'stage' => $stage->load('activities')
                ]);
            }

            return redirect()->route('admin.stages.index')
                ->with('success', 'Stage updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the stage.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while updating the stage.')
                ->withInput();
        }
    }

    /**
     * Update the order of stages.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'stages' => 'required|array',
                'stages.*.id' => 'required|exists:stages,id',
                'stages.*.order' => 'required|integer|min:0',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            foreach ($request->stages as $stageData) {
                Stage::where('id', $stageData['id'])->update(['order' => $stageData['order']]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Stage order updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating stage order.'
            ], 500);
        }
    }

    /**
     * Remove the specified stage from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $stage = Stage::findOrFail($id);
            
            // Detach all activities before deleting
            $stage->activities()->detach();
            $stage->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stage deleted successfully.'
                ]);
            }

            return redirect()->route('admin.stages.index')
                ->with('success', 'Stage deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the stage.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while deleting the stage.');
        }
    }
    
    /**
     * Toggle the status of a stage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleActive($id)
    {
        try {
            $stage = Stage::findOrFail($id);
            $stage->status = ($stage->status == 'active') ? 'inactive' : 'active';
            $stage->save();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stage status updated successfully.'
                ]);
            }
            
            return back()->with('success', 'Stage status updated successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating stage status.'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while updating stage status.');
        }
    }
}
