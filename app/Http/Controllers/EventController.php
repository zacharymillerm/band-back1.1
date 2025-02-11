<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        try {
            $events = Event::all();
            return response()->json($events, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching events: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        try {
            $event = Event::create($validated);
            return response()->json(['message' => 'Event created successfully!', 'event' => $event], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating event: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        try {
            $event = Event::findOrFail($id);
            return response()->json($event, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Event not found or error fetching event: ' . $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
        ]);

        try {
            $event = Event::findOrFail($id);
            $event->update($validated);
            return response()->json(['message' => 'Event updated successfully!', 'event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating event: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting event: ' . $e->getMessage()], 500);
        }
    }
}
