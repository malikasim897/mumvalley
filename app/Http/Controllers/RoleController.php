<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\PermissionsRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionsRepository;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $roleRepository;
    protected $permissionsRepository;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(RoleRepository $roleRepository, PermissionsRepository $permissionsRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionsRepository = $permissionsRepository;
        $this->middleware('permission:role.view|role.create|role.edit|role.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role.delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->roleRepository->getRoles();
        return view('roles.index', compact('roles'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissionsByModule = $this->roleRepository->permissionsByModule();
        return view('roles.create', compact('permissionsByModule'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = $this->roleRepository->store($request);
        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        } else {
            return redirect()->route('roles.index')->with('error', 'Role not created! Something went wrong.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $role = $this->roleRepository->getRole($id);
        // $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        //     ->where("role_has_permissions.role_id",$id)
        //     ->get();

        // return view('roles.show',compact('role','rolePermissions'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->roleRepository->getRole($id);
        // $permissionsByModule = $this->roleRepository->permissionsByModule();
        return view('roles.edit', compact('role'));
    }

    public function editPermissions($id)
    {
        $role = $this->roleRepository->getRole($id);
        $permissionsByModule = $this->roleRepository->permissionsByModule();
        return view('roles.permissions', compact('role', 'permissionsByModule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleRepository->update($request, $id);
        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } else {
            return redirect()->route('roles.index')->with('success', 'Role not updated! Something went wrong');
        }
    }

    public function updatePermissions(PermissionsRequest $request, $id)
    {
        $role = $this->permissionsRepository->updatePermissions($request, $id);
        if ($role) {
            return redirect()->route('roles.index')->with('success', 'Permissions updated successfully');
        } else {
            return redirect()->route('roles.index')->with('success', 'Permissions not updated! Something went wrong');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->roleRepository->delete($id);
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
