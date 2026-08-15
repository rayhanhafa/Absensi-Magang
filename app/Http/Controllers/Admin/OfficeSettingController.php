<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfficeSettingRequest;
use App\Http\Requests\UpdateOfficeSettingRequest;
use App\Models\OfficeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;

class OfficeSettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['permission:manage office settings'];
    }

    public function index(): View
    {
        $officeSettings = OfficeSetting::latest()->paginate(15);

        return view('admin.office-settings.index', ['officeSettings' => $officeSettings]);
    }

    public function create(): View
    {
        return view('admin.office-settings.create');
    }

    public function store(StoreOfficeSettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['is_active'] ?? false) {
            OfficeSetting::query()->update(['is_active' => false]);
        }

        OfficeSetting::create($validated);

        return redirect()->route('admin.office-settings.index')
            ->with('success', 'Lokasi absensi berhasil ditambahkan.');
    }

    public function edit(OfficeSetting $officeSetting): View
    {
        return view('admin.office-settings.edit', ['officeSetting' => $officeSetting]);
    }

    public function update(UpdateOfficeSettingRequest $request, OfficeSetting $officeSetting): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['is_active'] ?? false) {
            OfficeSetting::query()->where('id', '!=', $officeSetting->id)->update(['is_active' => false]);
        }

        $officeSetting->update($validated);

        return redirect()->route('admin.office-settings.index')
            ->with('success', 'Lokasi absensi berhasil diperbarui.');
    }

    public function destroy(OfficeSetting $officeSetting): RedirectResponse
    {
        if (OfficeSetting::count() <= 1) {
            return back()->withErrors([
                'office_setting' => 'Tidak dapat menghapus satu-satunya lokasi absensi yang tersisa.',
            ]);
        }

        $officeSetting->delete();

        return redirect()->route('admin.office-settings.index')
            ->with('success', 'Lokasi absensi berhasil dihapus.');
    }
}