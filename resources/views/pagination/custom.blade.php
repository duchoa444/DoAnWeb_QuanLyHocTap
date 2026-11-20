@if ($paginator->hasPages())
    <nav aria-label="Pagination" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; margin-top: 16px;">
        <div style="font-size: 14px; color: #4b5563;">
            Hiển thị <strong>{{ $paginator->firstItem() }}</strong> đến <strong>{{ $paginator->lastItem() }}</strong> trong tổng số <strong>{{ $paginator->total() }}</strong> kết quả
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
            {{-- Nút Trang trước --}}
            @if ($paginator->onFirstPage())
                <span style="padding: 6px 12px; border: 1px solid #e5e7eb; border-radius: 6px; color: #9ca3af; background: #f9fafb; font-size: 14px;">
                    &laquo; Trước
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; color: #374151; background: #fff; text-decoration: none; font-size: 14px;">
                    &laquo; Trước
                </a>
            @endif

            {{-- Danh sách số trang --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="padding: 6px 10px; color: #6b7280; font-size: 14px;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding: 6px 12px; border: 1px solid #2563eb; border-radius: 6px; background: #2563eb; color: #fff; font-weight: 600; font-size: 14px;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; color: #374151; text-decoration: none; font-size: 14px;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút Trang sau --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; color: #374151; background: #fff; text-decoration: none; font-size: 14px;">
                    Sau &raquo;
                </a>
            @else
                <span style="padding: 6px 12px; border: 1px solid #e5e7eb; border-radius: 6px; color: #9ca3af; background: #f9fafb; font-size: 14px;">
                    Sau &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif