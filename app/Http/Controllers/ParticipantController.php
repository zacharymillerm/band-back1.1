<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::all();

        return response()->json($participants);
    }

    public function indexNum(Request $request)
    {
        $participantNum = $request->query('participantNum', null);
        if ($participantNum) {
            $participants = Participant::limit($participantNum)->get();
        }

        return response()->json($participants);
    }

    public function show($id)
    {
        $participant = Participant::find($id);

        if (!$participant) {
            return response()->json(['message' => 'participant not found'], 404);
        }

        return response()->json($participant);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['image'] = $request->file('image')? url('storage/' . $request->file('image')->store('uploads/participant','public')):'';
        try{
            $newParticipant = Participant::create($data);
            return response()->json([
                'message' => 'participant created successfully!',
                'participant' => $newParticipant
            ], 200);
        }catch (\Exception $e) {
            \Log::error('Error saving data: ' . $e->getMessage());
            return response()->json(['error' => 'Error saving data'], 400);
        }
    }

    // public function store(Request $request)
    // {
    //     $data = $request->all();
    //     if ($request->hasFile('image')) {
    //         try {
    //             $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath(), [
    //                 'folder' => 'uploads/participant',
    //             ])->getSecurePath();

    //             $data['image'] = $uploadedFileUrl;
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Error uploading image'], 400);
    //         }
    //     }

    //     try {
    //         $newParticipant = Participant::create($data);
    //         return response()->json([
    //             'message' => 'Participant created successfully!',
    //             'participant' => $newParticipant
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Error saving data'], 400);
    //     }
    // }

    public function update(Request $request, $id)
    {
        try {
            $participant = Participant::findOrFail($id);
            $data = $request->all();
            $data['image'] = $request->file('image')
                ? url('storage/' . $request->file('image')->store('uploads/participant', 'public')) // Adjust path as needed
                : $participant->image;

                if ($request->file('image')) {
                    \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $participant->image));
                }
            $participant->update($data);

            return response()->json([
                'message' => 'Participant successfully updated!',
                'updatedParticipant' => $participant,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error updating participant: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating participant data'], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $participant = Participant::findOrFail($id);
            if($participant->image){
                \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $participant->image));
            }
            $participant->delete();

            $participants = Participant::all(); // Fetch all remaining participants
            return response()->json($participants, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting review'], 400);
        }
    }

    public function swapParticipantQueue(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'firstBlogId' => 'required|exists:participants,id',
                'secondBlogId' => 'required|exists:participants,id'
            ]);

            // Get the two blogs
            $firstBlog = Participant::findOrFail($request->firstBlogId);
            $secondBlog = Participant::findOrFail($request->secondBlogId);
            // Simple swap of values
            $temp1 = $firstBlog->id;
            $firstBlog->id = 99999999;
            $firstBlog->save();
            $temp2 = $secondBlog->id;
            $secondBlog->id = $temp1;
            $secondBlog->save();
            $firstBlog->id=$temp2;
            $firstBlog->save();
            
            
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
