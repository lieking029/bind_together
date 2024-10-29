<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ActivateUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(int $id)
    {
        $user = User::find($id);
        $user->update(['is_active' => 1]);

        alert()->success('User activated successfully');
        return redirect()->route('users.index', ['role' => $user->getRoleNames()->first()]);
    }
}
