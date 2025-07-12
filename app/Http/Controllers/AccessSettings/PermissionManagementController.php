<?php

namespace App\Http\Controllers\AccessSettings;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            $status = $request->input('status');
            $query->when(!is_null($status), function ($query) use ($status) {
                $query->where('is_active', $status);
            });
        }

        $data = $query->paginate(1)->withQueryString();
        return Inertia::render('rbac/permission/Index', [
            'data' => $data,
            'filters' => $request->only(['search', 'status']),
            'controllers' => Permission::select('group')->distinct()->pluck('group'),
        ]);
    }
}
