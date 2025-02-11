<?php

namespace App\Http\Controllers;

use App\Models\FactoryShow; // Assuming your model is named Factory
use Carbon\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Import the DB facade

class FactoryController extends Controller
{
    public function index()
    {
        $factory = FactoryShow::orderBy('id', 'desc')->get(); 
        return response()->json($factory);
    }
    public function top()
    {
        $factory = FactoryShow::orderBy('id', 'desc')->limit(2)->get();
        return response()->json($factory);
    }

    public function show($id)
    {
        $factory = FactoryShow::find($id);

        if (!$factory) {
            return response()->json(['message' => 'Factory not found'], 404);
        }
 
        return response()->json($factory);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'queue' => 'required|integer|max:255',
            'description' => 'required|string|max:255',
            'video' => 'required|file|mimes:jpg,jpeg,png,mp4,avi,mov,wmv,flv,webm|max:524288000', // Adjust max size as needed
            'links' => 'required|string|max:255',
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');

            $filePath = uploadVideoOrImage($file, 'factory');
            
            $factory = FactoryShow::create([
                'title' => $request->input('title'),
                'queue' => $request->input('queue'),
                'description' => $request->input('description'),
                'links' => $request->input('links'),
                'video' => $filePath
            ]);

            return response()->json([
                'message' => 'factory created successfully!',
                'factory' => $factory
            ], 200);
        }

        return response()->json(['error' => 'File upload failed'], 400);
    }

    public function update(Request $request, $id)
    {
        $factory = FactoryShow::find($id);

        if (!$factory) {
            return response()->json(['message' => 'factory not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'queue' => 'sometimes|required|integer|max:255',
            'description' => 'sometimes|required|string|max:255',
            'links' => 'sometimes|required|string|max:255',
            'video' => 'sometimes|file|mimes:jpg,jpeg,png,mp4,avi,mov,wmv,flv,webm|max:524288000', // Adjust max size as needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $factory->title = $request->title ?? $factory->title;
        $factory->queue = $request->queue ?? $factory->queue;
        $factory->description = $request->description ?? $factory->description;
        $factory->links = $request->links ?? $factory->links;

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filePath = uploadVideoOrImage($file, 'factory');

            if ($request->file('video')) {
                \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $factory->video));
            }

            $factory->video = $filePath;
        }

        $factory->save();

        return response()->json([
            'message' => 'factory updated successfully!',
            'factory' => $factory
        ], 200);
    }

    public function destroy($id)
    {
        try{
            $factory = FactoryShow::find($id);

        if (!$factory) {
            return response()->json(['message' => 'factory not found'], 404);
        }

        if ($factory->video) {
            \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $factory->video));
        }

        $factory->delete();
        $factorys=FactoryShow::all();

        return response()->json($factorys, 200);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting factory'], 400);
        }
    }

    public function swapFactoryQueue(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'firstBlogId' => 'required|exists:factory_shows,id',
                'secondBlogId' => 'required|exists:factory_shows,id'
            ]);

            // Get the two records
            $firstBlog = FactoryShow::findOrFail($request->firstBlogId);
            $secondBlog = FactoryShow::findOrFail($request->secondBlogId);

            // Swap the IDs using a temporary high value
            $tempId = $firstBlog->id;
            $firstBlog->id = 99999999;
            $firstBlog->save();

            $firstBlogOriginalId = $secondBlog->id;
            $secondBlog->id = $tempId;
            $secondBlog->save();

            $firstBlog->id = $firstBlogOriginalId;
            $firstBlog->save();

            // Reset the auto-increment value after swapping
            $maxId = FactoryShow::max('id');
            DB::statement("ALTER TABLE factory_shows AUTO_INCREMENT = " . ($maxId + 1));

            return response()->json([
                'message' => 'Blog IDs swapped successfully',
                'blogs' => [
                    'first' => $firstBlog->fresh(),
                    'second' => $secondBlog->fresh()
                ]
            ]);

        } catch (\Exception $error) {
            \Log::error('Error swapping blog IDs: ' . $error->getMessage());
            return response()->json([
                'error' => 'Server error',
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
