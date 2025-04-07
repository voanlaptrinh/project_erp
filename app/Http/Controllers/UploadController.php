<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Xác thực yêu cầu
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif', // Giới hạn kích thước file tối đa 2MB
        ]);
    
        $file = $request->file('file');
        
        // Tạo tên file duy nhất
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Đường dẫn lưu trữ
        $destinationPath = public_path('source/tyniimage');
    
        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
    
        // Di chuyển file vào thư mục public/source/tyniimage
        $file->move($destinationPath, $filename);
    
        // Tạo URL để trả về
        $location = url('source/tyniimage/' . $filename);
    
        // Trả về đường dẫn file
        return response()->json(['location' => $location]);
    }
    public function deleteImage(Request $request)
    {
        // Lấy đường dẫn ảnh từ request
        $imageUrl = $request->input('image');
        if (!$imageUrl) {
            return response()->json(['message' => 'Không có đường dẫn ảnh'], 400);
        }
    
        // Chuyển đổi URL thành đường dẫn thực trên máy chủ
        $imagePath = public_path(parse_url($imageUrl, PHP_URL_PATH));
    
        // Kiểm tra và xóa file
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
            return response()->json(['message' => 'Ảnh đã được xóa']);
        }
    
        return response()->json(['message' => 'Ảnh không tồn tại hoặc không hợp lệ'], 404);
    }
}
