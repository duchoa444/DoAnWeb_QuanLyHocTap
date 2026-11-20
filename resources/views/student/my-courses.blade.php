@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Khóa học của tôi</h1>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if ($courses->isEmpty())
        <p class="text-gray-600">Bạn chưa ghi danh khóa học nào.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                <div class="border rounded-lg p-4 shadow-sm bg-white">
                    <h2 class="text-lg font-semibold">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Giảng viên: {{ $course->teacher->name ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $course->description }}</p>
                    <div class="mt-4">
                        <a href="{{ route('courses.show', $course->id) }}"
                           class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Vào học
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection