<?php

namespace App\Repositories;

use DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;

class RoleRepository
{
    public function getRoles()
    {
        return Role::orderBy('id','DESC')->paginate(10);
    }
    public function getPermissions()
    {
        return Permission::get();
    }

    public function permissionsByModule()
    {
        $modules = Config::get('role.modules'); // List of modules
        $permissionsByModule = [];
        foreach ($modules as $module) {
            $permissionsByModule[$module] = Permission::where('name', 'like', "{$module}.%")->get();
        }
        return $permissionsByModule;
    }

    public function store($request)
    {
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions'));
        if($role)
        {
            return true;
        } else {
            return false;
        }
    }

    public function getRole($id)
    {
        return Role::find($id);
    }

    public function update($request, $id)
    {

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        // if (!$role->syncPermissions($request->input('permissions'))){
        //     return false;
        // };
        return true;
    }

    public function delete($id)
    {
        return DB::table("roles")->where('id',$id)->delete();
    }
}
