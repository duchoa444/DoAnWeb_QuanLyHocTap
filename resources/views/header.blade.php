<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EduPlatform - Quản lý học tập')</title>
</head>
<body style="margin: 0; font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333;">

<header style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: #ffffff; border-bottom: 1px solid #e0e0e0; flex-wrap: wrap; gap: 10px;">
    {{-- Logo --}}
    <div class="logo">
        <a href="{{ url('/') }}" style="text-decoration: none; color: #007bff; font-size: 20px;">
            <strong>🎓 EduPlatform</strong>
        </a>
    </div>

    {{-- Menu điều hướng theo vai trò --}}
    <nav style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <a href="{{ url('/courses') }}" style="text-decoration: none; color: #333; font-weight: {{ request()->is('/') || request()->is('courses*') ? 'bold' : 'normal' }};">
            Khóa học
        </a>

        @auth
            {{-- Vai trò Học viên (student) --}}
            @if(auth()->user()->role === 'student')
                <a href="{{ url('/my-courses') }}" style="text-decoration: none; color: #333; font-weight: {{ request()->is('my-courses*') ? 'bold' : 'normal' }};">
                    Khóa học của tôi
                </a>
                <a href="{{ url('/exam-history') }}" style="text-decoration: none; color: #333; font-weight: {{ request()->is('exam-history*') ? 'bold' : 'normal' }};">
                    Lịch sử thi
                </a>
            @endif

            {{-- Vai trò Giảng viên (teacher) --}}
            @if(auth()->user()->role === 'teacher')
                <a href="{{ url('/teacher/courses') }}" style="text-decoration: none; color: #333;">Quản lý giảng dạy</a>
                <a href="{{ url('/teacher/exams') }}" style="text-decoration: none; color: #333;">Quản lý đề thi</a>
            @endif

            {{-- Vai trò Quản trị viên (admin) --}}
            @if(auth()->user()->role === 'admin')
                <a href="{{ url('/admin/users') }}" style="text-decoration: none; color: #333;">Quản lý tài khoản</a>
                <a href="{{ url('/admin/approve-courses') }}" style="text-decoration: none; color: #333;">Duyệt khóa học</a>
            @endif
        @endauth
    </nav>

    {{-- Khu vực Tài khoản / Đăng nhập --}}
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        @auth
            <span style="font-size: 14px; color: #555;">
                Xin chào, 
                <a href="{{ url('/profile') }}" title="Hồ sơ cá nhân" style="color: #007bff; text-decoration: none; font-weight: bold;">
                    {{ auth()->user()->name }}
                </a>
            </span>
            <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="padding: 6px 12px; background: #dc3545; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                    Đăng xuất
                </button>
            </form>
        @else
            <a href="{{ url('/login') }}" style="padding: 7px 14px; border: 1px solid #007bff; color: #007bff; text-decoration: none; border-radius: 4px;">Đăng nhập</a>
            <a href="{{ url('/register') }}" style="padding: 7px 14px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px;">Đăng ký</a>
        @endauth
    </div>
</header>

{{-- Khung nội dung chính & Thông báo dùng chung --}}
<div style="max-width: 1200px; margin: 20px auto; padding: 0 15px;">
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;">
            {{ session('error') }}
        </div>
    @endif
    </div></body></html>    