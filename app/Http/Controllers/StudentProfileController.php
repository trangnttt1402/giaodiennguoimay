<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function show()
    {
        return view('student.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|numeric',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Nam,Nữ,Khác',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:2048',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa số.',
            'dob.date' => 'Ngày sinh không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'avatar.image' => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar.mimes' => 'Ảnh đại diện chỉ hỗ trợ định dạng: png, jpg, jpeg, gif.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',
        ]);

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('public/avatars', $filename);
            $validated['avatar_url'] = asset('storage/avatars/' . $filename);
        }

        // Remove avatar from validated data as we handle it separately
        unset($validated['avatar']);

        $user->update($validated);

        return back()->with('status', 'Cập nhật liên hệ thành công');
    }
}
