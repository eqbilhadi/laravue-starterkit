<?php

namespace App\Http\Controllers\Navigation;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    public function destroy(Menu  $sysMenu)
    {
        $sysMenu->delete();
        return redirect()->route('rbac.nav.index')->with('success', 'Menu deleted successfully.');
    }
}
