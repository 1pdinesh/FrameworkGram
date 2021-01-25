<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //if not authenticated return unathorised error

    }
//many to many relationship
    public function store(User $user)
    {
        return auth()->user()->following()->toggle($user->profile);
        //toggle is follow or unfollow
    }
}
