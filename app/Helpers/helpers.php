<?php

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

if (!function_exists('convertVideoFormat')) {
    function convertVideoFormat($inputPath, $outputPath, $width = 1280, $height = 720, $bitrate = '2M')
    {
        try {
            Log::info('Converting video from ' . $inputPath . ' to ' . $outputPath);
            // Convert video using FFmpeg
            FFMpeg::fromDisk('local')
                ->open($inputPath)
                ->export()
                ->toDisk('local')
                ->inFormat(new X264)
                ->resize($width, $height) // Resize to 1280x720 (adjust as needed)
                ->save($outputPath);
            
            Log::info('Converted video from ' . $inputPath . ' to ' . $outputPath);

            return url('storage' . str_replace('/public', '', $outputPath)); // Return public URL of the converted file
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}

if (!function_exists('uploadVideoOrImage')) {
    function uploadVideoOrImage($file, $section = 'factory')
    {
        $storedUrl = '';
        if (in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'])) {
            // Video format
            $videoName = time() . '.mp4';
            Log::info('videoName: ' . $videoName);
            $videoPath = $file->storeAs('public/uploads/' . $section, $videoName);
            $convertedPath = '/public/uploads/'. $section .'/converted_' . $videoName;
            Log::info('videoPath: ' . $videoPath);
            Log::info('convertedPath: ' . $convertedPath);

            $storedUrl = convertVideoFormat($videoPath, $convertedPath);
            Log::info('storedUrl: ' . $storedUrl);
        } else {
            // Image format
            $storedUrl = url('storage/' . $file->store('uploads/' . $section, 'public'));
        }
        return $storedUrl;
    }
}