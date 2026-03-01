@extends('admin.layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="card" style="max-width:720px;">
    <h2 style="margin-top:0;">Hồ sơ cá nhân</h2>
    <p style="color:#94a3b8; margin-top:4px;">Cập nhật thông tin tài khoản quản trị của bạn.</p>

    @if(session('success'))
    <div class="flash">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="flash error">
        <ul style="margin:0; padding-left:18px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" style="display:grid; gap:12px; margin-top:12px;">
        @csrf
        <label>
            <span style="font-size:12px;color:#94a3b8;display:block;">Họ tên</span>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </label>
        <label>
            <span style="font-size:12px;color:#94a3b8;display:block;">Email</span>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </label>
        <div style="display:flex; gap:12px; align-items:center;">
            <button type="submit">Lưu thay đổi</button>
            <a href="{{ route('password.change') }}" style="color:#8bd3ff;">Đổi mật khẩu</a>
        </div>
    </form>
</div>
@endsection
