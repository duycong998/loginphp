<?php

namespace App\Http\Controllers;

use App\Models\MyTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MyTableController extends Controller
{
    //get

    // public function index()
    // {
    //     $data = MyTable::all(); // Lấy tất cả dữ liệu từ bảng k theo thứ tự
    //     return response()->json($data); // Trả về dạng JSON
    // }

    public function index()
    {
        $data = MyTable::orderBy('id', 'asc')->get(); // Sắp xếp theo ID tăng dần
        return response()->json($data);
    }


    public function indexlimit()
    {
        $data = MyTable::limit(3)->get(); // Lấy tất cả dữ liệu từ bảng
        return response()->json($data); // Trả về dạng JSON
    }

    public function indexlimitset($limit)
    {
        // Giới hạn giá trị min = 1, max = 100 để tránh truy vấn quá lớn
        $limit = is_numeric($limit) ? max(1, min($limit, 100)) : 3;

        $data = MyTable::limit($limit)->get();
        return response()->json($data);
    }


    //post
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:my_table,email',
            'password' => 'required|min:6',
            'phone' => 'required|string|max:15',
        ]);

        try {
            DB::beginTransaction(); // Bắt đầu transaction bắt nếu thêm bị lỗi thì k mất id
            // Thêm dữ liệu vào bảng
            $data = MyTable::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Mã hóa password
                'phone' => $request->phone,
                'email_verified_at' => now(), // Tự động gán giá trị thời gian hiện tại
            ]);

            DB::commit(); // Lưu vào DB nếu không có lỗi

            // Trả về JSON phản hồi
            return response()->json([
                'message' => 'User added successfully!',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack(); // Hoàn tác nếu có lỗi
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    //UpdatePut
    public function updatePut(Request $request, $id)
    {
       Log::info("🔍 Debug - Đang tìm ID: " . $id);

        // Tìm bản ghi theo ID
        $data = MyTable::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        Log::info("✅ Tìm thấy ID lan 2: " . $id);

        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:my_table,email,' . $id,
            'password' => 'nullable|min:6', // Không bắt buộc nhập password
            'phone' => 'nullable|string|max:15' // Không bắt buộc phone
        ]);

        Log::info("✅ Tìm thấy ID lan 3: " . $id);

        // Kiểm tra nếu có password mới thì mới hash
        $password = $data->password; // Giữ nguyên password cũ
        if ($request->filled('password')) {
            $password = Hash::make($request->password);
        }

        Log::info("✅ Tìm thấy ID lan 4: " . $id);

        // Cập nhật dữ liệu
        $data->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'phone' => $request->phone
        ]);

        return response()->json([
            'message' => 'Data updated successfully!',
            'data' => $data
        ], 200);
    }


    //updatePatch
    public function updatePatch(Request $request, $id)
    {
        // Tìm bản ghi theo ID
        $data = MyTable::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Kiểm tra dữ liệu đầu vào (chỉ validate nếu trường có trong request)
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:my_table,email,' . $id,
            'password' => 'sometimes|min:6',  // Không required
            'phone' => 'sometimes|string|max:15'
        ]);

        // Lấy dữ liệu từ request (chỉ lấy các trường có gửi lên)
        $updateData = $request->only(['name', 'email', 'phone']);

        // Nếu có password trong request, hash trước khi cập nhật
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Cập nhật dữ liệu
        $data->update($updateData);

        return response()->json([
            'message' => 'Data updated successfully!',
            'data' => $data
        ], 200);
    }


    //delete id
    public function delete($id)
    {
        //kiemtra id có đúng format
        if (!is_numeric($id) || $id <= 0) {
            return response()->json(['message' => 'Invalid ID'], 400);
        }


        // Tìm bản ghi theo ID
        $data = MyTable::find($id);

        // Nếu không tìm thấy, trả về lỗi
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        // Xóa bản ghi
        $data->delete();

        return response()->json(['message' => 'Data deleted successfully!'], 200);
    }
}
