<?php

namespace App\Http\Controllers;

use App\Models\Site; // Adjust the namespace according to your model's location
use App\Models\Blog; // Adjust the namespace according to your model's location
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        try {
            $data = Site::orderBy('queue', 'desc')->with('blogs')->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data'], 400);
        }
    }

    public function getSixSites()
    {
        try {
            $data = Site::orderBy('queue', 'desc')->with('blogs')->take(6)->get();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data'], 400);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if (isset($data['siteTags'])) {
            $data['siteTags'] = json_decode($data['siteTags'], true); // Decode as associative array
        }

        $data['video'] = $request->file('video') ? uploadVideoOrImage($request->file('video'), 'site') : '';
        try {
            $site = Site::create($data);
            return response()->json(['message' => 'Successfully saved!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error saving data'], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $site = Site::findOrFail($id);
            $data = $request->all();
            if (isset($data['siteTags'])) {
                $data['siteTags'] = json_decode($data['siteTags'], true); // Decode as associative array
            }
            $data['video'] = $request->file('video')
                ? uploadVideoOrImage($request->file('video'), 'site') // Adjust path as needed
                : $site->video;

                if ($request->file('video')) {
                    \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $site->video));
                }
            $site->update($data);

            return response()->json([
                'message' => 'site successfully updated!',
                'updatedsite' => $site,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error updating site: ' . $e->getMessage());
            return response()->json(['error' => 'Error updating site data'], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $siteToDelete = Site::findOrFail($id);
            
            if ($siteToDelete->video) {
                \Storage::disk('public')->delete(str_replace(url('storage') . '/', '', $siteToDelete->video));
            }
            Blog::where('site_id', $id)->update(['site_id' => null]);
            $siteToDelete->delete();
            $remainingSites = Site::all();

            return response()->json($remainingSites);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting site: ' . $e->getMessage()], 400);
        }
    }



    public function show($id)
    {
        try {
            $site = Site::with('blogs')->findOrFail($id);
            return response()->json($site);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Publication not found'], 404);
        }
    }

    public function swapSitesQueue(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'firstBlogId' => 'required|exists:sites,id',
                'secondBlogId' => 'required|exists:sites,id'
            ]);

            DB::transaction(function () use ($request) {
                // Get the two blogs
                $firstBlog = Site::findOrFail($request->firstBlogId);
                $secondBlog = Site::findOrFail($request->secondBlogId);

                // Temporarily update the blogs table to avoid foreign key conflicts
                Blog::where('site_id', $firstBlog->id)->update(['site_id' => null]);
                Blog::where('site_id', null)->whereIn('id', [$request->firstBlogId, $request->secondBlogId])
                    ->update(['site_id' => DB::raw("CASE
                        WHEN id = {$request->firstBlogId} THEN {$request->secondBlogId}
                        WHEN id = {$request->secondBlogId} THEN {$request->firstBlogId}
                    END")]);
                Blog::where('site_id', $secondBlog->id)->update(['site_id' => null]);
                Blog::where('site_id', null)->whereIn('id', [$request->firstBlogId, $request->secondBlogId])
                    ->update(['site_id' => DB::raw("CASE
                        WHEN id = {$request->firstBlogId} THEN {$request->secondBlogId}
                        WHEN id = {$request->secondBlogId} THEN {$request->firstBlogId}
                    END")]);
                // Swap site IDs using a temporary placeholder
                $tempId = 99999999;
                $firstBlog->id = $tempId;
                $firstBlog->save();

                $secondBlog->id = $request->firstBlogId;
                $secondBlog->save();

                $firstBlog->id = $request->secondBlogId;
                $firstBlog->save();

                // Blog::whereIn('site_id', [$request->firstBlogId, $request->secondBlogId])
                // ->update([
                //     'site_id' => DB::raw("CASE
                //         WHEN site_id = {$request->firstBlogId} THEN {$request->secondBlogId}
                //         WHEN site_id = {$request->secondBlogId} THEN {$request->firstBlogId}
                //     END")
                // ]);
                // Restore the foreign keys in the blogs table
                // Blog::where('site_id', null)->whereIn('id', [$request->firstBlogId, $request->secondBlogId])
                //     ->update(['site_id' => DB::raw("CASE
                //         WHEN id = {$request->firstBlogId} THEN {$request->secondBlogId}
                //         WHEN id = {$request->secondBlogId} THEN {$request->firstBlogId}
                //     END")]);
            
            return response()->json([
                'message' => 'Site IDs swapped successfully',
                'blogs' => [
                    'first' => $firstBlog->fresh(),
                    'second' => $secondBlog->fresh()
                ]
            ]);
        });

        } catch (\Exception $error) {
            \Log::error('Error swapping blog IDs: ' . $error->getMessage());
            return response()->json([
                'error' => 'Server error',
                'message' => $error->getMessage()
            ], 500);
        }
    }



}
