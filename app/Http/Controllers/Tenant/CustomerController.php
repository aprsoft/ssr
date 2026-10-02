<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\UpdateUserRequest;
use Spatie\Permission\Models\Role;
use App\Models\Tenant\Customer;


class CustomerController extends Controller
{
   
    public function index()
    {
        return view('tenant.customers.index',['title'=>'Clientes']);
    }

    public function create()
    {
        return view('tenant.customers.create',['title'=>'Crear Cliente']);
    }

    public function show()
    {
        return view('tenant.customers.shos',['title'=>'Mostrar Cliente']);
    }
  
    public function edit(Employee $employee)
    
    {
        $roles = Role::all();
        return  view('tenant.customers.edit',['title'=>'Editar Cliente', 'roles'=>$roles]);
    }
   
}
