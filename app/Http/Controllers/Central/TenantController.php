<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Central\Controller;
use App\Models\Central\Tenant;
use App\Services\Error\ErrorLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TenantController extends Controller
{
    public function __construct(
        protected ErrorLogger $errorLogger
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->status;

        return view('central.tenants.index', compact('status'));
    }

    public function create()
    {
        return view('central.tenants.create');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('domains');

        return view('central.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load('domains');

        return view('central.tenants.edit', compact('tenant'));
    }

    public function update(Tenant $tenant)
    {
        return view('central.tenants.show');
    }

    
}