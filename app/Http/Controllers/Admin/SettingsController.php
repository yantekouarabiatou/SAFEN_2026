<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\NotificationTemplate;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('key');

        $general = $settings->get('general') ?? collect();
        $payments = $settings->get('payments') ?? collect();
        $notifications = $settings->get('notifications') ?? collect();

        $templates = NotificationTemplate::all()->keyBy('name')->map(function ($t) {
            return ['subject' => $t->subject, 'body' => $t->body];
        })->toArray();

        return view('admin.settings.index', compact('general', 'payments', 'notifications', 'templates'));
    }

    public function update(Request $request)
    {
        $section = $request->input('section', 'general');

        if ($section === 'general') {
            Setting::setValue('general.site_name', $request->input('site_name'));
            Setting::setValue('general.site_description', $request->input('site_description'));
            Setting::setValue('general.default_locale', $request->input('default_locale'));
            Setting::setValue('general.maintenance_mode', $request->input('maintenance_mode'));
            Setting::setValue('general.maintenance_message', $request->input('maintenance_message'));
        }

        if ($section === 'payments') {
            Setting::setValue('payments.enabled_methods', array_map('trim', explode(',', $request->input('enabled_methods', ''))));
            Setting::setValue('payments.kkiapay_key', $request->input('kkiapay_key'));
            Setting::setValue('payments.kkiapay_secret', $request->input('kkiapay_secret'));
            Setting::setValue('payments.currency', $request->input('currency'));
        }

        if ($section === 'notifications') {
            Setting::setValue('notifications.channels', array_map('trim', explode(',', $request->input('channels', ''))));
            Setting::setValue('notifications.from_email', $request->input('from_email'));

            $templates = $request->input('templates', []);
            foreach ($templates as $name => $tpl) {
                NotificationTemplate::updateOrCreate(['name' => $name], ['subject' => $tpl['subject'], 'body' => $tpl['body']]);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Paramètres mis à jour.');
    }
}
