@include('header')

<div style="padding: 20px; max-width: 1100px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #333;">Khóa học của tôi ({{ $courses->count() }})</h2>
        <a href="{{ route('courses.index') }}" style="padding: 8px 14px; border: 1px solid #007bff; color: #007bff; text-decoration: none; border-radius: 4px; font-size: 14px;">
            + Khám phá thêm khóa học
        </a>
    </div>

    

    @if($courses->isEmpty())
        <div style="background: #fff; padding: 40px 20px; border: 1px solid #ddd; border-radius: 8px; text-align: center;">
            <p style="color: #666; font-size: 16px; margin-bottom: 16px;">Bạn chưa ghi danh khóa học nào.</p>
            <a href="{{ route('courses.index') }}" style="display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">
                Xem danh sách khóa học
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 20px;">
            @foreach($courses as $course)
                @php
                    $enrolledAt = $enrollments[$course->id]->created_at ?? null;
                    $progress = $course->getProgressStats(Auth::id());
                    $percent = $progress['percentage'];
                    $barColor = $percent === 100 ? '#28a745' : '#007bff';
                @endphp
                <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="display: inline-block; padding: 3px 10px; background: #e3f2fd; color: #1565c0; border-radius: 4px; font-size: 12px;">
                                {{ $course->category->name ?? 'Chưa phân loại' }}
                            </span>
                            @if($percent === 100)
                                <span style="padding: 3px 8px; background: #d4edda; color: #155724; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    Hoàn thành
                                </span>
                            @endif
                        </div>

                        <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #222;">
                            {{ $course->title }}
                        </h3>

                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #555;">
                            <strong>Giảng viên:</strong> {{ $course->teacher->name ?? 'Đang cập nhật' }}
                        </p>
                        <p style="margin: 0 0 6px 0; font-size: 14px; color: #555;">
                            <strong>Tiến độ bài học:</strong> {{ $progress['completed'] }}/{{ $progress['total'] }} bài
                        </p>
                        @if($enrolledAt)
                            <p style="margin: 0 0 12px 0; font-size: 13px; color: #888;">
                                Ghi danh ngày: {{ \Carbon\Carbon::parse($enrolledAt)->format('d/m/Y') }}
                            </p>
                        @endif

                        {{-- Thanh phần trăm tiến độ (HT-04) --}}
                        <div style="margin: 12px 0 16px 0;">
                            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 5px; color: #333;">
                                <span>Tiến độ hoàn thành</span>
                                <strong style="color: {{ $barColor }};">{{ $percent }}%</strong>
                            </div>
                            <div style="width: 100%; height: 8px; background: #e9ecef; border-radius: 999px; overflow: hidden;">
                                <div style="width: {{ $percent }}%; height: 100%; background: {{ $barColor }}; transition: width 0.3s ease;"></div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; padding-top: 12px; border-top: 1px solid #eee;">
                        <a href="{{ Route::has('courses.learn') ? route('courses.learn', $course->id) : route('courses.show', $course->id) }}"
                           style="flex: 1; text-align: center; padding: 9px 14px; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 14px;">
                            {{ $percent === 100 ? 'Ôn tập lại' : ($percent > 0 ? 'Tiếp tục học' : 'Vào học') }}
                        </a>
                        <a href="{{ route('courses.show', $course->id) }}"
                           style="padding: 9px 12px; border: 1px solid #ccc; color: #333; text-decoration: none; border-radius: 4px; font-size: 14px;">
                            Chi tiết
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@include('footer')