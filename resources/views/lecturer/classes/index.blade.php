@extends('lecturer.layout')

@section('title', 'Lớp giảng dạy')

@section('content')
<div style="padding: 24px; color: #64748b; font-size: 14px; font-weight: 500; background: white; border-radius: 8px 8px 0 0; margin: 0 20px 0 20px;">
    <span style="color: #1e293b;">👨‍🏫 Lớp giảng dạy</span> > <span style="color: #6B4B9D; font-weight: 600;">Danh sách lớp</span>
</div>

<div style="margin: 0 20px 20px; padding: 24px; background: white; border-radius: 0 0 8px 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h3 style="margin: 0 0 4px; color: #1e293b; font-size: 20px; font-weight: 600;">📚 Danh sách Lớp giảng dạy</h3>
            <p style="margin: 0; color: #94a3b8; font-size: 13px;">Năm học {{ $academicYear }} - {{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</p>
        </div>
        <div style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color: white; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600;">
            🎓 {{ $classSections->count() }} lớp
        </div>
    </div>

    @if($classSections->isEmpty())
        <!-- Empty State -->
        <div style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; border: 2px dashed #cbd5e0;">
            <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
            <h4 style="color: #64748b; margin: 0 0 8px; font-size: 16px; font-weight: 600;">Chưa có lớp được phân công</h4>
            <p style="color: #94a3b8; margin: 0; font-size: 14px;">Liên hệ bộ phận đào tạo để nhận được phân công giảng dạy</p>
        </div>
    @else
        <!-- Classes Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            @foreach($classSections as $section)
                @php
                    $percentage = $section->max_capacity > 0 ? ($section->current_enrollment / $section->max_capacity) * 100 : 0;
                    $progressColor = $percentage >= 90 ? '#ef4444' : ($percentage >= 70 ? '#f59e0b' : '#10b981');
                @endphp
                <a href="{{ route('lecturer.classes.detail', $section->id) }}" style="text-decoration: none; color: inherit;">
                    <div style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: all 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(107, 75, 157, 0.15)'; this.style.borderColor='#6B4B9D';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08)'; this.style.borderColor='#e2e8f0';" style="box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                        <!-- Header -->
                        <div style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color: white; padding: 16px; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 4px;">{{ $section->section_code }}</div>
                                <h4 style="margin: 0; font-size: 16px; font-weight: 700;">{{ $section->course->code }}</h4>
                            </div>
                            <div style="background: rgba(255, 255, 255, 0.2); padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                CA {{ $section->shift ? $section->shift->start_period : '?' }}
                            </div>
                        </div>

                        <!-- Body -->
                        <div style="padding: 16px;">
                            <!-- Course Name -->
                            <p style="margin: 0 0 12px; color: #2d2d2d; font-size: 14px; font-weight: 500;">{{ $section->course->name }}</p>

                            <!-- Info Grid -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                                <div>
                                    <p style="margin: 0 0 4px; color: #94a3b8; font-size: 11px; font-weight: 600; text-transform: uppercase;">📅 Thứ</p>
                                    <p style="margin: 0; color: #1e293b; font-size: 14px; font-weight: 600;">Thứ {{ $section->day_of_week }}</p>
                                </div>
                                <div>
                                    <p style="margin: 0 0 4px; color: #94a3b8; font-size: 11px; font-weight: 600; text-transform: uppercase;">⏰ Tiết</p>
                                    <p style="margin: 0; color: #1e293b; font-size: 14px; font-weight: 600;">
                                        @if($section->shift)
                                            {{ $section->shift->start_period }}-{{ $section->shift->end_period }}
                                        @else
                                            TBA
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p style="margin: 0 0 4px; color: #94a3b8; font-size: 11px; font-weight: 600; text-transform: uppercase;">🚪 Phòng</p>
                                    <p style="margin: 0; color: #1e293b; font-size: 14px; font-weight: 600;">
                                        {{ $section->room ? $section->room->code : 'Chưa xếp' }}
                                    </p>
                                </div>
                                <div>
                                    <p style="margin: 0 0 4px; color: #94a3b8; font-size: 11px; font-weight: 600; text-transform: uppercase;">👥 Sĩ số</p>
                                    <p style="margin: 0; color: #1e293b; font-size: 14px; font-weight: 600;">
                                        {{ $section->current_enrollment }}/{{ $section->max_capacity }}
                                    </p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div style="margin-bottom: 12px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <span style="font-size: 12px; color: #64748b; font-weight: 500;">Ghi danh</span>
                                    <span style="font-size: 12px; color: #64748b; font-weight: 600;">{{ number_format($percentage, 0) }}%</span>
                                </div>
                                <div style="height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;">
                                    <div style="height: 100%; background: {{ $progressColor }}; width: {{ $percentage }}%; transition: width 0.3s;"></div>
                                </div>
                            </div>

                            <!-- Action -->
                            <div style="padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                <button style="width: 100%; background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color: white; border: none; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(107, 75, 157, 0.3)';" onmouseout="this.style.boxShadow='none';">
                                    👁️ Xem chi tiết & Danh sách SV
                                </button>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Info Box -->
        <div style="margin-top: 24px; padding: 16px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #6B4B9D;">
            <p style="margin: 0 0 8px; color: #1e293b; font-weight: 600; font-size: 13px;">💡 Gợi ý:</p>
            <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 12px;">
                <li style="margin-bottom: 4px;">Nhấn vào lớp để xem chi tiết sinh viên đã đăng ký</li>
                <li style="margin-bottom: 4px;">Quản lý sĩ số và xem danh sách tham dự</li>
                <li>Theo dõi tỷ lệ ghi danh cho mỗi lớp</li>
            </ul>
        </div>
    @endif
</div>
@endsection