<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadBlogs(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/blogs', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload site
    public function uploadSite(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/sites', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload equipment
    public function uploadEquipment(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/equipments', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload factory
    public function uploadFactory(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/factorys', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload review
    public function uploadReview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/reviews', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload 3D
    public function uploadThree(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/three', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload solution
    public function uploadSolution(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/solution', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload attachment
    public function uploadAttach(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/attach', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Upload rental
    public function uploadRental(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4|max:2048', // Adjust mime types and size limit as necessary
        ]);

        $path = $request->file('file')->store('uploads/rental', 'public');

        return response()->json(['file_path' => $path], 200);
    }

    // Validate the request
    protected function validateRequest(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:2048', // Adjust the file types and size limit as necessary
        ]);
    }
}
