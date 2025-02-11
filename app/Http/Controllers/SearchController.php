<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Equipment;
use App\Models\FactoryShow;
use App\Models\Review;
use App\Models\Site;

class SearchController extends Controller
{
    public function searchData(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        try {
            $regex = "%$searchTerm%";
            
            // Searching in Case Model
            $caseData = Blog::where(function ($query) use ($regex) {
                $query->where('name', 'LIKE', $regex)
                    ->orWhere('startDate', 'LIKE', $regex)
                    ->orWhere('endDate', 'LIKE', $regex)
                    ->orWhere('guests', 'LIKE', $regex)
                    ->orWhere('venue', 'LIKE', $regex);
            })->get();

            $caseMatchedData = $caseData->map(function ($item) use ($regex) {
                $matchedField = $item->name ?? $item->blog_type ?? $item->startDate ?? $item->endDate ?? $item->guests ?? $item->venue;
                return [
                    'link' => "/case-one/{$item->id}",
                    'scrollSpy' =>'',
                    'id' => $item->id,
                    'value' => $matchedField,
                ];
            });

            // Searching in Equipment Model
            $equipmentData = Equipment::where(function ($query) use ($regex) {
                $query->where('name', 'LIKE', $regex)
                    ->orWhere('categoryType', 'LIKE', $regex)
                    ->orWhere('brand', 'LIKE', $regex)
                    ->orWhere('description', 'LIKE', $regex)
                    ->orWhere('manufacturer', 'LIKE', $regex)
                    ->orWhere('weight', 'LIKE', $regex)
                    ->orWhere('series', 'LIKE', $regex);
            })->get();

            $equipmentMatchedData = $equipmentData->map(function ($item) use ($regex) {
                $matchedField = $item->name ?? $item->categoryType ?? $item->brand ?? $item->description ?? $item->manufacturer ?? $item->weight ?? $item->series;
                return [
                    'link' => "/equipment-one/{$item->id}",
                    'scrollSpy' =>'',
                    'id' => $item->id,
                    'value' => $matchedField,
                ];
            });

            // Searching in Factory Model
            $factoryData = FactoryShow::where(function ($query) use ($regex) {
                $query->where('title', 'LIKE', $regex)
                    ->orWhere('description', 'LIKE', $regex);
            })->get();

            $factoryMatchedData = $factoryData->map(function ($item) use ($regex) {
                $matchedField = $item->title ?? $item->description;
                return [
                    'link' => '/',
                    'scrollSpy' => 'blogSection',
                    'id' => $item->id,
                    'value' => $matchedField,
                ];
            });

            // Searching in Review Model
            $reviewData = Review::where(function ($query) use ($regex) {
                $query->where('name', 'LIKE', $regex)
                    ->orWhere('content', 'LIKE', $regex);
            })->get();

            $reviewMatchedData = $reviewData->map(function ($item) use ($regex) {
                $matchedField = $item->name ?? $item->content;
                return [
                    'link' => '/services/visualization',
                    'scrollSpy' => 'customerReviewSection',
                    'id' => $item->id,
                    'value' => $matchedField,
                ];
            });

            // Searching in Site Model
            $siteData = Site::where(function ($query) use ($regex) {
                $query->where('name', 'LIKE', $regex)
                    ->orWhere('capacity', 'LIKE', $regex)
                    ->orWhere('address', 'LIKE', $regex);
            })->get();

            $siteMatchedData = $siteData->map(function ($item) use ($regex) {
                $matchedField = $item->name ?? $item->capacity ?? $item->address;
                return [
                    'link' => "/site-one/{$item->id}",
                    'scrollSpy' =>'',
                    'id' => $item->id,
                    'value' => $matchedField,
                ];
            });

            // Combining all search results
            foreach ($caseMatchedData as $item) {
                $equipmentMatchedData->push($item);
            }
            
            foreach ($factoryMatchedData as $item) {
                $equipmentMatchedData->push($item);
            }
            
            foreach ($reviewMatchedData as $item) {
                $equipmentMatchedData->push($item);
            }
            
            foreach ($siteMatchedData as $item) {
                $equipmentMatchedData->push($item);
            }
            
            $searchResult = $equipmentMatchedData;
            

            return response()->json($searchResult);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
