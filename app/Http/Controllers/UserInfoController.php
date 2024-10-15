<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function index()
    {
        return UserInfo::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'emp_info_id' => 'required|integer',
            'emp_id' => 'required|integer',
            'user_idline' => 'required|string|max:50',
            'user_id' => 'required|string|max:50',
            'password' => 'required|string|max:50',
            'user_role' => 'required|string|max:25',
            'user_banned' => 'required|boolean',
            'status_active' => 'required|boolean',
            'create_by' => 'required|string|max:25',
            'create_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $user = UserInfo::create($request->all());
        return response()->json($user, 201);
    }

}
