<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function email()
    {
        $config = Setting::getMailConfig();
        return view('settings.email', compact('config'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name'    => 'required|string|max:255',
            'mail_default_cc'   => 'nullable|string|max:1000',
            'mail_default_bcc'  => 'nullable|string|max:1000',
            'mail_test_address' => 'nullable|email|max:255',
        ]);

        Setting::set('mail_from_address', $request->mail_from_address);
        Setting::set('mail_from_name',    $request->mail_from_name);
        Setting::set('mail_default_cc',   $request->mail_default_cc  ?? '');
        Setting::set('mail_default_bcc',  $request->mail_default_bcc ?? '');
        Setting::set('mail_test_address', $request->mail_test_address ?? '');

        return back()->with('success', 'Email settings saved successfully.');
    }
}
