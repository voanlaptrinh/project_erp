<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    // Kiểm tra quyền xem dự án
    public function view(User $user, Project $project)
    {
        if ($user->hasRole('Super Admin')) {
            return true; // Super Admin có quyền xem tất cả dự án
        }
        return $user->projects->contains($project); // Người dùng chỉ có thể xem các dự án đã được phân công
    }

    // Kiểm tra quyền sửa dự án
    public function update(User $user, Project $project)
    {
        if ($user->hasRole('Super Admin')) {
            return true; // Super Admin có quyền sửa tất cả dự án
        }
        return $user->projects->contains($project); // Người dùng chỉ có thể sửa các dự án đã được phân công
    }

    // Kiểm tra quyền xóa dự án
    public function delete(User $user, Project $project)
    {
        if ($user->hasRole('Super Admin')) {
            return true; // Super Admin có quyền xóa tất cả dự án
        }
        return $user->projects->contains($project); // Người dùng chỉ có thể xóa các dự án đã được phân công
    }
    public function __construct()
    {
        //
    }
}
