<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{

    // แสดงข้อมูล
    public function index()
    {
        return UserInfo::all();
    }

// เพิ่ม ข้อมูล
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
        // 'create_by' => 'required|string|max:25',
        // 'update_by' => 'required|string|max:25',
    ]);

    try {
        $data = $request->all();
        $data['create_date'] = now();
        $data['update_date'] = now();
        $user = UserInfo::create($data);

        return response()->json($user, 201);
    } catch (\Exception $e) {

        return response()->json([
            'error' => 'User creation failed',
            'message' => $e->getMessage()
        ], 500);
    }
}

// ลบข้อมูล
public function destroy($id)
{
    try {

        $user = UserInfo::findOrFail($id); 


        $user->delete();


        return response()->json([
            'message' => 'User deleted successfully.'
        ], 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

        return response()->json([
            'error' => 'User not found.',
            'message' => $e->getMessage()
        ], 404);
    } catch (\Exception $e) {

        return response()->json([
            'error' => 'User deletion failed',
            'message' => $e->getMessage()
        ], 500);
    }
}

// update ข้อมูล
public function update(Request $request, $id)
{
    $request->validate([
        'emp_info_id' => 'integer',
        'emp_id' => 'integer',
        'user_idline' => 'string|max:50',
        'user_id' => 'string|max:50',
        'password' => 'string|max:50',
        'user_role' => 'string|max:25',
        'user_banned' => 'boolean',
        'status_active' => 'boolean',
        'update_by' => 'string|max:25',
        'update_date' => 'date_format:Y-m-d H:i:s',
    ]);

    $user = UserInfo::findOrFail($id);
    $user->update($request->all());
    return response()->json($user, 200);
}


}
