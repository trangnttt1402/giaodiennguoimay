@extends('admin.layout')

@section('title', 'Chi tiết Đợt đăng ký')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('registration-waves.index') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1px solid #ddd; border-radius: 8px; color: #666; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='#6B4B9D'; this.style.color='#6B4B9D'" onmouseout="this.style.borderColor='#ddd'; this.style.color='#666'">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 style="margin: 0; font-size: 24px; color: #333;">⏰ Chi tiết Đợt đăng ký</h2>
                    <p style="margin: 4px 0 0 0; color: #888; font-size: 14px;">Thông tin đầy đủ về đợt đăng ký</p>
                </div>
            </div>
        </div>

        @php
        $now = now();
        $starts = \Carbon\Carbon::parse($wave->starts_at);
        $ends = \Carbon\Carbon::parse($wave->ends_at);
        $isActive = $now >= $starts && $now <= $ends;
        $isUpcoming = $now < $starts;
        $isEnded = $now > $ends;
        @endphp

        <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <!-- Trạng thái -->
            <div style="margin-bottom: 24px; padding: 16px; border-radius: 8px; 
                @if($isActive) background: #dcfce7; border-left: 4px solid #16a34a;
                @elseif($isUpcoming) background: #fef3c7; border-left: 4px solid #f59e0b;
                @else background: #f3f4f6; border-left: 4px solid #6b7280;
                @endif">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 24px;">
                        @if($isActive) ✅
                        @elseif($isUpcoming) ⏰
                        @else 🔒
                        @endif
                    </span>
                    <div>
                        <strong style="font-size: 16px; 
                            @if($isActive) color: #166534;
                            @elseif($isUpcoming) color: #92400e;
                            @else color: #374151;
                            @endif">
                            @if($isActive) Đang mở đăng ký
                            @elseif($isUpcoming) Sắp diễn ra
                            @else Đã kết thúc
                            @endif
                        </strong>
                        <div style="font-size: 13px; color: #64748b; margin-top: 4px;">
                            @if($isActive)
                                Còn lại {{ $ends->diffForHumans($now, true) }}
                            @elseif($isUpcoming)
                                Bắt đầu {{ $starts->diffForHumans($now) }}
                            @else
                                Kết thúc {{ $ends->diffForHumans($now) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin chính -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; font-size: 13px; margin-bottom: 8px;">TÊN ĐỢT</label>
                    <div style="font-size: 16px; color: #1e293b; font-weight: 500;">{{ $wave->name }}</div>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; font-size: 13px; margin-bottom: 8px;">NĂM HỌC / HỌC KỲ</label>
                    <div style="display: flex; gap: 8px;">
                        <span style="background: #eef2ff; color: #5a6ac8; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 600;">{{ $wave->academic_year }}</span>
                        <span style="background: #f3f0ff; color: #6B4B9D; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 600;">{{ $wave->term }}</span>
                    </div>
                </div>
            </div>

            <!-- Thời gian -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; font-size: 13px; margin-bottom: 8px;">THỜI GIAN BẮT ĐẦU</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-check" style="color: #10b981;"></i>
                        <span style="font-size: 15px; color: #1e293b;">{{ $starts->format('d/m/Y H:i') }}</span>
                    </div>
                    <div style="font-size: 12px; color: #94a3b8; margin-top: 4px; margin-left: 24px;">{{ $starts->diffForHumans() }}</div>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; color: #64748b; font-size: 13px; margin-bottom: 8px;">THỜI GIAN KẾT THÚC</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-times" style="color: #ef4444;"></i>
                        <span style="font-size: 15px; color: #1e293b;">{{ $ends->format('d/m/Y H:i') }}</span>
                    </div>
                    <div style="font-size: 12px; color: #94a3b8; margin-top: 4px; margin-left: 24px;">{{ $ends->diffForHumans() }}</div>
                </div>
            </div>

            <!-- Đối tượng được phép đăng ký -->
            <div style="border-top: 1px solid #e2e8f0; padding-top: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 16px;">
                    <i class="fas fa-users" style="color: #6B4B9D; margin-right: 8px;"></i>
                    Đối tượng được phép đăng ký
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                    <div>
                        <label style="display: block; font-weight: 600; color: #64748b; font-size: 13px; margin-bottom: 12px;">KHOA</label>
                        @php
                        $selectedFaculties = collect($audience['faculties'] ?? [])->map(function($id) use ($faculties) {
                            return $faculties->firstWhere('id', $id);
                        })->filter();
                        @endphp

                        @if($selectedFaculties->isEmpty())
                            <div style="background: #f0fdf4; border: 1px solid #86efac; padding: 12px; border-radius: 8px; color: #166534; font-size: 14px;">
                                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                                <strong>Tất cả các khoa</strong>
                            </div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                @foreach($selectedFaculties as $faculty)
                                <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 10px 12px; border-radius: 6px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-graduation-cap" style="color: #6B4B9D; font-size: 14px;"></i>
                                    <span style="color: #334155; font-size: 14px;">{{ $faculty->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; color: #64748b; font-size: 13px; margin-bottom: 12px;">KHÓA</label>
                        @php
                        $cohorts = $audience['cohorts'] ?? [];
                        @endphp

                        @if(empty($cohorts))
                            <div style="background: #f0fdf4; border: 1px solid #86efac; padding: 12px; border-radius: 8px; color: #166534; font-size: 14px;">
                                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                                <strong>Tất cả các khóa</strong>
                            </div>
                        @else
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @foreach($cohorts as $cohort)
                                <span style="background: #eef2ff; color: #5a6ac8; padding: 8px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    {{ $cohort }}
                                </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thống kê (nếu có) -->
            <div style="border-top: 1px solid #e2e8f0; padding-top: 24px; margin-top: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #334155; margin-bottom: 16px;">
                    <i class="fas fa-chart-bar" style="color: #6B4B9D; margin-right: 8px;"></i>
                    Thống kê
                </h3>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    @php
                    $duration = $starts->diffInDays($ends);
                    $durationHours = $starts->diffInHours($ends);
                    @endphp
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 700; color: #6B4B9D;">{{ $duration }}</div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 4px;">Ngày diễn ra</div>
                    </div>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 700; color: #10b981;">{{ $durationHours }}</div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 4px;">Giờ</div>
                    </div>
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 28px; font-weight: 700; color: #f59e0b;">
                            {{ $selectedFaculties->isEmpty() ? count($faculties) : $selectedFaculties->count() }}
                        </div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 4px;">Khoa tham gia</div>
                    </div>
                </div>
            </div>

            <!-- Nút thao tác -->
            <div style="border-top: 1px solid #e2e8f0; padding-top: 24px; margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('registration-waves.index') }}" style="background: #f1f5f9; color: #475569; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('registration-waves.edit', $wave) }}" style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.filter='brightness(0.95)'; this.style.transform='translateY(-1px)'" onmouseout="this.style.filter='none'; this.style.transform='none'">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <form action="{{ route('registration-waves.destroy', $wave) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đợt đăng ký này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #dc2626; color: white; padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
