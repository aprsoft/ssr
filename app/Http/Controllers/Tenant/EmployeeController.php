<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use App\Models\Tenant\Employee;


class EmployeeController extends Controller
{
   
    public function index()
    {
        return view('tenant.employees.index',['title'=>'Empleados']);
    }

    public function create()
    {
        return view('tenant.employees.create',['title'=>'Crear Empleado']);
    }

    public function show()
    {
        return view('tenant.employees.shos',['title'=>'Mostrar Empleado']);
    }
  
    public function edit(Employee $employee)
    
    {
        $roles = Role::all();
        return  view('tenant.employees.edit',['title'=>'Editar Empleado', 'roles'=>$roles]);
    }
   
}
