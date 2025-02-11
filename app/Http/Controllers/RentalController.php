<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::all();
        return response()->json($rentals);
    }

    public function show($id)
    {
        $rental = Rental::find($id);

        if (!$rental) {
            return response()->json(['message' => 'Rental not found'], 404);
        }

        return response()->json($rental);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cost' => 'nullable|string|max:255',
            'files.*' => 'nullable|file',
        ]);

        $rental = Rental::first();
        $filePaths = [];

        if ($request->hasFile('files')) {
            if ($rental && $rental->files) {
                foreach (($rental->files) as $oldFile) {
                    \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $oldFile));
                }
            }
            foreach ($request->file('files') as $file) {
                $filePath = url('storage/' . $file->store('uploads/rental', 'public'));
                $filePaths[] = $filePath;
            }
        }
        if ($rental) {
            $rental->update([
                'cost' => $request->cost ?? $rental->cost,
                'files' => !empty($filePaths) ? ($filePaths) : $rental->files, // Ensure files are stored as JSON
            ]);

            return response()->json(['message' => 'Rental updated successfully!', 'rental' => $rental], 200);
        } else {
            if ($request->cost && !empty($filePaths)) {
                $rental = Rental::create([
                    'cost' => $request->cost,
                    'files' => ($filePaths),
                ]);

                return response()->json(['message' => 'Rental created successfully!', 'rental' => $rental], 200);
            } else {
                return response()->json(['message' => 'Both cost and files are required to create a new rental'], 422);
            }
        }
    }

   
}
