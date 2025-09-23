<?php

namespace App\Http\Controllers;

use App\Models\Timer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = now();
        
        $timers = Timer::query()
            ->orderByRaw('
                CASE 
                    WHEN spawn_at IS NULL THEN 2
                    WHEN spawn_at <= ? THEN 1
                    ELSE 0
                END
            ', [$now])
            ->orderByRaw('
                CASE 
                    WHEN spawn_at <= ? THEN -UNIX_TIMESTAMP(spawn_at)
                    ELSE UNIX_TIMESTAMP(spawn_at)
                END
            ', [$now])
            ->get();

        return Inertia::render('Timers/Index', [
            'timers' => $timers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Timers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|integer|min:1|max:100',
            'delay_minutes' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
        ]);

        $timer = Timer::create($validated);

        return redirect()->route('timers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Timer $timer)
    {
        return Inertia::render('Timers/Show', [
            'timer' => $timer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timer $timer)
    {
        return Inertia::render('Timers/Edit', [
            'timer' => $timer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timer $timer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|integer|min:1|max:100',
            'delay_minutes' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'died_at' => 'nullable|date',
            'note' => 'nullable|string'
        ]);


        // Convert died_at string to Carbon instance in Jakarta timezone if it exists
        if (isset($validated['died_at'])) {
            $validated['died_at'] = \Carbon\Carbon::parse($validated['died_at']);
        }

        $timer->update($validated);

        // If death time is set, update spawn time
        if ($timer->died_at) {
            $diedAt = $timer->died_at;
            $timer->spawn_at = $diedAt->copy()->addMinutes($timer->delay_minutes);
            $timer->notified_two_min = false;
            $timer->save();
        } else {
            // If death time is cleared, clear spawn time too
            $timer->spawn_at = null;
            $timer->notified_two_min = false;
            $timer->save();
        }

        return redirect()->route('timers.index');
    }

    public function updateSpawnTime(Timer $timer)
    {
        // Get current time in Jakarta timezone
        $now = now()->setTimezone('Asia/Jakarta');
        $timer->died_at = $now;
        $timer->spawn_at = $now->copy()->addMinutes($timer->delay_minutes);
        $timer->notified_two_min = false;
        $timer->save();

        return redirect()->back();
    }

    public function resetSpawnTime(Timer $timer)
    {
        $timer->died_at = null;
        $timer->spawn_at = null;
        $timer->notified_two_min = false;
        $timer->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timer $timer)
    {
        $timer->delete();
        return redirect()->route('timers.index');
    }
}
