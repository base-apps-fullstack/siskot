<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        $user = Auth::user()->makeHidden(['password', 'remember_token', 'login_attempt', 'login_attempt_at', 'locked_at']);

        $userId = $user->id;

        $roles = Role::whereHas('users', function ($query) use ($userId) {
            $query->where('id', $userId);
        })
            ->pluck('permissions')
            ->first();

        $user->permissions = $roles;

        return $this->response($user);
    }
}
