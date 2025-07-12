<?php

namespace App\Http\Controllers\AccessSettings;

use Throwable;
use App\Models\Menu;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Navigation\CreateNavigationRequest;
use App\Http\Requests\Navigation\UpdateNavigationRequest;

class NavManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($search = $request->input('search')) {
            $query->where('label_name', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            $query->when(!is_null($status), function ($query) use ($status) {
                $query->where('is_active', $status);
            });
        }

        $data = $query->paginate(10)->withQueryString();
        return Inertia::render('rbac/navigation/Index', [
            'data' => $data,
            'filters' => $request->only(['search', 'status']),
            'controllers' => Menu::select('controller_name')->distinct()->pluck('controller_name'),
        ]);
    }

    public function create()
    {
        return Inertia::render('rbac/navigation/Form');
    }

    public function store(CreateNavigationRequest $request)
    {
        try {
            Menu::create($request->validated());
            foreach (User::pluck('id') as $userId) {
                Cache::forget("menus.{$userId}");
            }
            return redirect()->route('rbac.nav.index')->with('success', 'Menu created successfully.');
        } catch (Throwable $e) {
            Log::error('Menu create failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create menu. Please try again.');
        }
    }

    public function edit(Menu $sysMenu)
    {
        return Inertia::render('rbac/navigation/Form', [
            'menu' => $sysMenu,
        ]);
    }

    public function update(UpdateNavigationRequest $request, Menu $sysMenu)
    {
        try {
            $sysMenu->update($request->validated());
            foreach (User::pluck('id') as $userId) {
                Cache::forget("menus.{$userId}");
            }
            return redirect()->route('rbac.nav.index')->with('success', 'Menu updated successfully.');
        } catch (Throwable $e) {
            Log::error('Menu update failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to update menu. Please try again.');
        }
    }

    public function destroy(Menu  $sysMenu)
    {
        try {
            $sysMenu->delete();
            foreach (User::pluck('id') as $userId) {
                Cache::forget("menus.{$userId}");
            }
            return redirect()->route('rbac.nav.index')->with('success', 'Menu deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Menu delete failed', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to delete menu. Please try again.');
        }
    }
}
