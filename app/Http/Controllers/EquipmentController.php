<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Blog; // Assuming Blog is the model for your 'cases'
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function getEquipments()
    {
        try {
            $data = Equipment::orderBy('queue', 'desc')->with('blogs')->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error fetching data'], 400);
        }
    }

    public function getEquipmentsByType(Request $request)
    {
        $equipmentType = $request->query('equipmentType');
        try {
            $data = Equipment::getByType($equipmentType, 3);
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data'], 400);
        }
    }

    public function insertEquipment(Request $request)
    {
        $data = $request->all();
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filePath[] = url('storage/' . $file->store('uploads/equipment', 'public'));
            }
            $data['images'] = $filePath;
        } else {
            $data['images'] = [];
        }

        if ($request->hasFile('files')) {
            $storedPath = $request->file('files')->store('uploads/equipment', 'public'); 
            $data['file'] = url('storage/' . $storedPath);
        }
        
        try {
            Equipment::create($data);
            return response()->json(['message' => 'Successfully saved!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error saving data'], 400);
        }
    }

    public function updateEquipment(Request $request, $id)
    {
        try {
            $existingEquipment = Equipment::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Equipment not found'], 404);
        }

        $data = $request->all();
        $filePaths = [];
        if ($request->hasFile('images')) {
            if ($existingEquipment&&$existingEquipment->images) {
                foreach (($existingEquipment->images) as $oldImage) {
                    \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $oldImage));
                }
            }   
            foreach ($request->file('images') as $file) {
                $filePath[] = url('storage/' . $file->store('uploads/equipment', 'public'));
                $data['images']=$filePath;
            }
        } else {
            $data['images'] = $existingEquipment->images;
        }
        
        if ($request->hasFile('files')) {
            if ($existingEquipment->file) {
                \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $existingEquipment->file));
            }
            $data['file'] =  url('storage/' . $request->file('files')->store('uploads/equipment', 'public')); 
        }else {
            $data['file'] = $existingEquipment->file;
        }

        try {
            $existingEquipment->update($data);
            return response()->json(['message' => 'Equipment successfully updated!', 'updatedEquipment' => $existingEquipment], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating equipment'], 400);
        }
    }

    public function deleteEquipment($id)
    {
        try {
            $equipmentToDelete = Equipment::findOrFail($id);
            if ($equipmentToDelete) {
                if ($equipmentToDelete->images) {
                    foreach (($equipmentToDelete->images) as $oldFile) {
                        \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $oldFile));
                    }
                }
                if ($equipmentToDelete->file) {
                    \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $equipmentToDelete->file));
                }
                $equipmentToDelete->delete();
                return response()->json(Equipment::all(), 200);
            }
            $equipmentToDelete->delete();

            // Remove equipment reference from related blogs
            // Blog::where('equipment', $id)->update(['equipment' => null]); // Adjust this logic based on your relationship

            $remainingEquipment = Equipment::all();
            return response()->json($remainingEquipment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting equipment'], 400);
        }
    }

    public function getEquipmentByID($id)
    {
        try {
            $result = Equipment::with('blogs')->findOrFail($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'error fetching data'], 400);
        }
    }
}
