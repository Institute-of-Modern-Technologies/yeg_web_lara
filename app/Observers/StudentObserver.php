<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\Stage;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        // If no stage is assigned, automatically assign the first stage (by order)
        if (!$student->stage_id) {
            $firstStage = Stage::where('status', 'active')
                ->orderBy('order')
                ->first();
                
            if ($firstStage) {
                $student->stage_id = $firstStage->id;
                $student->save();
            }
        }
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
