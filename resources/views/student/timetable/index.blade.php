@extends('student.layout')

@section('title','Thời khóa biểu')

@section('content')
<div style="padding: 24px; color: #64748b; font-size: 14px; font-weight: 500; background: white; border-radius: 8px 8px 0 0; margin: 0 20px 0 20px;">
    <span style="color: #1e293b;">📚 Thời khóa biểu</span> > <span style="color: #6B4B9D; font-weight: 600;">Lịch học</span>
</div>

<div style="margin: 0 20px 20px; padding: 24px; background: white; border-radius: 0 0 8px 8px; display: grid; grid-template-columns: 1fr 320px; gap: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
    <!-- Main Calendar Grid -->
    <div>
        <!-- Month and Navigation -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h3 style="margin: 0 0 4px; color: #1e293b; font-size: 20px; font-weight: 600;">Lịch cá nhân</h3>
                <p style="margin: 0; color: #94a3b8; font-size: 13px;">Năm học {{ $year }} - {{ $term }}</p>
            </div>
            <a href="{{ route('student.exportIcs') }}" style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; display: inline-block;">
                ⬇️ Tải về ICS
            </a>
        </div>

        <!-- Calendar Grid -->
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; overflow-x: auto;">
            <div style="display: grid; grid-template-columns: 80px repeat(7, 1fr); gap: 1px; background: #e2e8f0; border-radius: 8px; overflow: hidden;">
                <!-- Time column header -->
                <div style="grid-column: 1; background: white; padding: 12px 8px; text-align: center; font-size: 12px; font-weight: 600; color: #64748b; border-bottom: 2px solid #cbd5e0;"></div>
                
                <!-- Day headers -->
                @php
                    $dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
                    $today = now();
                    $weekStart = $today->copy()->startOfWeek(2); // Monday
                @endphp
                
                @for($i = 0; $i < 7; $i++)
                    @php
                        $date = $weekStart->copy()->addDays($i);
                        $isToday = $date->isToday();
                    @endphp
                    <div style="grid-column: {{ $i + 2 }}; background: white; padding: 12px 8px; text-align: center; border-bottom: 2px solid #cbd5e0; {{ $isToday ? 'background: linear-gradient(135deg, #8595F6 0%, #b99ce9 100%);' : '' }}">
                        <div style="font-size: 12px; font-weight: 600; color: {{ $isToday ? 'white' : '#2d2d2d' }};">{{ $dayNames[$i] }}</div>
                        <div style="font-size: 14px; font-weight: 700; color: {{ $isToday ? 'white' : '#1e293b' }}; margin-top: 2px;">{{ $date->format('d') }}</div>
                    </div>
                @endfor

                <!-- Time slots -->
                @php
                    $startHour = 8;
                    $endHour = 18;
                @endphp
                
                @for($hour = $startHour; $hour < $endHour; $hour++)
                    <!-- Time label -->
                    <div style="grid-column: 1; background: white; padding: 12px 8px; text-align: center; font-size: 12px; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0;">
                        {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                    </div>
                    
                    <!-- Day cells -->
                    @for($dayOffset = 0; $dayOffset < 7; $dayOffset++)
                        <div style="grid-column: {{ $dayOffset + 2 }}; background: white; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; min-height: 60px; position: relative;">
                            @php
                                $cellDate = $weekStart->copy()->addDays($dayOffset);
                                $dayOfWeek = $cellDate->dayOfWeek; // 0=Sunday, 1=Monday, etc
                                if ($dayOfWeek == 0) $dayOfWeek = 7; // Convert Sunday to 7
                                
                                // Find classes for this day and time
                                $classesInSlot = $regs->filter(function($reg) use ($dayOfWeek, $hour) {
                                    $shift = $reg->classSection->shift;
                                    if (!$shift) return false;
                                    $dayMatch = $reg->classSection->day_of_week == ($dayOfWeek == 7 ? 0 : $dayOfWeek);
                                    $timeMatch = $shift->start_period <= $hour && $shift->end_period > $hour;
                                    return $dayMatch && $timeMatch;
                                });
                            @endphp
                            
                            @forelse($classesInSlot as $idx => $reg)
                                @php
                                    $shift = $reg->classSection->shift;
                                    $course = $reg->classSection->course;
                                    $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'];
                                    $color = $colors[$idx % count($colors)];
                                @endphp
                                <div style="position: absolute; inset: 2px; background: {{ $color }}; border-radius: 6px; padding: 6px 8px; color: white; font-size: 11px; font-weight: 500; overflow: hidden; display: flex; flex-direction: column; justify-content: center; z-index: {{ 10 - $idx }}; cursor: pointer; transition: all 0.2s;" title="{{ $course->code }} ({{ $reg->classSection->section_code }})">
                                    <div style="font-weight: 700; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">{{ $course->code }}</div>
                                    <div style="font-size: 10px; opacity: 0.9; white-space: nowrap;">Tiết {{ $shift->start_period }}-{{ $shift->end_period }}</div>
                                    <div style="font-size: 10px; opacity: 0.8; white-space: nowrap;">{{ $reg->classSection->room ? $reg->classSection->room->code : 'TBA' }}</div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    @endfor
                @endfor
            </div>
        </div>

        <!-- Legend -->
        <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #6B4B9D;">
            <p style="margin: 0 0 12px; color: #1e293b; font-weight: 600; font-size: 13px;">📋 Hướng dẫn:</p>
            <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 12px;">
                <li style="margin-bottom: 6px;">Nhấn vào lớp để xem chi tiết</li>
                <li style="margin-bottom: 6px;">Các lớp được sắp xếp theo thứ tự thời gian</li>
                <li>Tải xuống ICS để thêm vào calendar của bạn</li>
            </ul>
        </div>
    </div>

    <!-- Right Sidebar: Mini Calendar -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Mini Calendar -->
        <div style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); border-radius: 12px; padding: 20px; color: white; box-shadow: 0 4px 12px rgba(107, 75, 157, 0.15);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 id="calendarTitle" style="margin: 0; font-size: 14px; font-weight: 600;">Tháng {{ now()->format('m')  }}-{{ now()->format('Y') }}</h4>
                <div style="display: flex; gap: 6px;">
                    <button style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; width: 28px; height: 28px; cursor: pointer; font-size: 14px; border-radius: 6px; transition: all 0.2s;" onclick="changeMonth(-1)">◀</button>
                    <button style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; width: 28px; height: 28px; cursor: pointer; font-size: 14px; border-radius: 6px; transition: all 0.2s;" onclick="changeMonth(1)">▶</button>
                </div>
            </div>

            <!-- Days of week headers -->
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 8px;">
                @foreach(['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'] as $day)
                    <div style="text-align: center; font-size: 11px; font-weight: 600; opacity: 0.8;">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Calendar grid -->
            <div id="calendarGrid" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;">
                @php
                    $firstDay = now()->startOfMonth()->dayOfWeek; // 0=Sunday
                    if ($firstDay == 0) $firstDay = 7; // Convert to 1=Monday, 7=Sunday
                    $firstDay = $firstDay == 7 ? 0 : $firstDay - 1; // Convert to 0=Monday
                    $daysInMonth = now()->daysInMonth;
                @endphp

                <!-- Empty cells before month starts -->
                @for($i = 0; $i < $firstDay; $i++)
                    <div style="padding: 8px 0; text-align: center; font-size: 12px;"></div>
                @endfor

                <!-- Days of month -->
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $cellDate = now()->setDay($day);
                        $isToday = $cellDate->isToday();
                    @endphp
                    <div style="padding: 8px 0; text-align: center; font-size: 12px; font-weight: {{ $isToday ? 600 : 400 }}; background: {{ $isToday ? 'rgba(255,255,255,0.2)' : 'transparent' }}; border-radius: {{ $isToday ? 6 : 0 }}px; cursor: pointer; transition: all 0.2s;" onclick="this.style.background='rgba(255,255,255,0.3)';">
                        <div style="color: white; border-radius: 50%; display: inline-block; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; {{ $isToday ? 'background: rgba(255,255,255,0.3); font-weight: 700;' : '' }}">{{ $day }}</div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Stats -->
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
            <div style="margin-bottom: 12px;">
                <p style="margin: 0 0 4px; color: #64748b; font-size: 12px; font-weight: 500;">👥 Tổng lớp</p>
                <p style="margin: 0; color: #1e293b; font-size: 20px; font-weight: 700;">{{ $regs->count() }}</p>
            </div>
            <div style="margin-bottom: 12px;">
                <p style="margin: 0 0 4px; color: #64748b; font-size: 12px; font-weight: 500;">📅 Năm học</p>
                <p style="margin: 0; color: #1e293b; font-size: 14px; font-weight: 500;">{{ $year }}</p>
            </div>
            <div>
                <p style="margin: 0 0 4px; color: #64748b; font-size: 12px; font-weight: 500;">📖 Học kỳ</p>
                <p style="margin: 0; color: #1e293b; font-size: 14px; font-weight: 500;">{{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
let currentMonth = {{ now()->format('n') }};
let currentYear = {{ now()->format('Y') }};
const today = new Date({{ now()->format('Y') }}, {{ now()->format('n') - 1 }}, {{ now()->format('j') }});

function changeMonth(delta) {
    currentMonth += delta;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    } else if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    renderCalendar();
}

function renderCalendar() {
    const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
    let firstDayIndex = firstDay === 0 ? 6 : firstDay - 1;
    
    document.getElementById('calendarTitle').textContent = `Tháng ${String(currentMonth).padStart(2, '0')}-${currentYear}`;
    
    let html = '';
    for (let i = 0; i < firstDayIndex; i++) {
        html += '<div style="padding: 8px 0; text-align: center; font-size: 12px;"></div>';
    }
    
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = (day === today.getDate() && currentMonth === today.getMonth() + 1 && currentYear === today.getFullYear());
        const bgColor = isToday ? 'rgba(255,255,255,0.2)' : 'transparent';
        const borderRadius = isToday ? '6px' : '0';
        const fontWeight = isToday ? '600' : '400';
        const innerBg = isToday ? 'background: rgba(255,255,255,0.3); font-weight: 700;' : '';
        
        html += `<div style="padding: 8px 0; text-align: center; font-size: 12px; font-weight: ${fontWeight}; background: ${bgColor}; border-radius: ${borderRadius}; cursor: pointer; transition: all 0.2s;" onclick="this.style.background='rgba(255,255,255,0.3)';">`;
        html += `<div style="color: white; border-radius: 50%; display: inline-block; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; ${innerBg}">${day}</div>`;
        html += '</div>';
    }
    
    document.getElementById('calendarGrid').innerHTML = html;
}
</script>
@endsection