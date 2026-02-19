<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('Admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Simple update logic for text fields
        $data = $request->except(['_token', 'logo', 'favicon']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Handle Checkboxes (boolean switches)
        // If not sent in request, it means unchecked (set to 0 or null)
        $checkboxes = ['registration_enabled', 'maintenance_mode', 'show_downloads', 'audio_force_site_cover', 'audio_remove_id3v1'];
        foreach ($checkboxes as $chk) {
            if (!$request->has($chk)) {
                Setting::updateOrCreate(['key' => $chk], ['value' => '0']);
            }
        }

        // Handle File Uploads (Logo, Favicon)
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => $path]);
        }

        if ($request->hasFile('audio_default_cover')) {
            $path = $request->file('audio_default_cover')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'audio_default_cover'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
