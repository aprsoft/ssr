<?php

namespace App\Livewire\Central\User;

use App\Events\Central\UserCreated;
use App\Models\Central\User;
use App\Services\Central\ErrorLog\ErrorLogger;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Throwable;

class UserEdit extends Component
{
    
   

    public function render()
    {
   

        return view('livewire.central.user.user-edit');
    }
}
