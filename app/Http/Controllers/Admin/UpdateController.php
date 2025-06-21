<?php

namespace LoafPanel\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use LoafPanel\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UpdateController extends Controller
{
    public function index()
    {
        $currentVersion = config('app.version', '1.0.0');
        $latestVersion = 'Fetching...';
        $releaseNotes = '';
        $isUpdateAvailable = false;

        try {
            $response = Http::get('https://api.github.com/repos/LoafHost/panel/releases/latest');
            if ($response->successful()) {
                $latestData = $response->json();
                $latestVersion = $latestData['tag_name'];
                $releaseNotes = $latestData['body'];
                // Ensure version strings are comparable
                $isUpdateAvailable = version_compare(str_replace('v', '', $currentVersion), str_replace('v', '', $latestVersion), '<');
            } else {
                $latestVersion = 'Error fetching version.';
            }
        } catch (\Exception $e) {
            $latestVersion = 'Error: ' . $e->getMessage();
        }

        return view('admin.update.index', [
            'currentVersion' => $currentVersion,
            'latestVersion' => $latestVersion,
            'releaseNotes' => $releaseNotes,
            'isUpdateAvailable' => $isUpdateAvailable,
        ]);
    }

    public function update(Request $request)
    {
        try {
            Artisan::call('lp:update');
            return redirect()->route('admin.update')->with('success', 'Update process started successfully. This may take a few minutes.');
        } catch (\Exception $e) {
            return redirect()->route('admin.update')->with('error', 'Failed to start update process: ' . $e->getMessage());
        }
    }
}
