<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json(['data' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        $role = Role::create(['name' => $request->name]);
        return response()->json(['data' => $role, 'message' => 'Role created successfully'], 201);
    }

    public function show(Role $role)
    {
        return response()->json(['data' => $role]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);
        $role->update(['name' => $request->name]);
        return response()->json(['data' => $role, 'message' => 'Role updated successfully']);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate(['permissions' => 'required|array']);
        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $role->syncPermissions($permissions);
        return response()->json(['message' => 'Permissions assigned successfully']);
    }

    public function assignRoles(Request $request, User $user)
    {
        $request->validate(['roles' => 'required|array']);
        $roles = Role::whereIn('name', $request->roles)->get();
        $user->syncRoles($roles);
        return response()->json(['message' => 'Roles assigned successfully']);
    }
}
