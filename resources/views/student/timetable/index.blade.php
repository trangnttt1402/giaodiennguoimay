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
                <p style="margin: 0; color: #94a3b8; font-size: 13px;">Năm học {{ $year }} - {{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</p>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <button onclick="changeWeek(-1)" style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #64748b; width: 32px; height: 32px; cursor: pointer; font-size: 14px; border-radius: 6px; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Tuần trước">◀</button>
                    <span id="weekLabel" style="color: #1e293b; font-size: 13px; font-weight: 600; min-width: 180px; text-align: center;"></span>
                    <button onclick="changeWeek(1)" style="background: #f1f5f9; border: 1px solid #e2e8f0; color: #64748b; width: 32px; height: 32px; cursor: pointer; font-size: 14px; border-radius: 6px; transition: all 0.2s; display: flex; align-items: center; justify-content: center;" title="Tuần sau">▶</button>
                    <button onclick="goToToday()" style="background: #6B4B9D; border: none; color: white; padding: 6px 12px; cursor: pointer; font-size: 12px; border-radius: 6px; font-weight: 600; transition: all 0.2s;" title="Về tuần hiện tại">Hôm nay</button>
                </div>
                <a href="{{ route('student.exportIcs') }}" style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; display: inline-block;">
                    ⬇️ Tải về ICS
                </a>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; overflow-x: auto;">
            @php
                $dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
                // DB convention: 2=Mon(Thứ 2), 3=Tue(Thứ 3), ..., 7=Sat(Thứ 7), 1=Sun(CN)
                $dbDays = [2, 3, 4, 5, 6, 7, 1];
                $today = now();
                $weekStart = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);

                // Period time labels (50 min each starting 07:00)
                $periodTimes = [];
                for ($p = 1; $p <= 12; $p++) {
                    $startMin = 7 * 60 + ($p - 1) * 50;
                    $endMin = 7 * 60 + $p * 50;
                    $periodTimes[$p] = sprintf('%02d:%02d-%02d:%02d', floor($startMin/60), $startMin%60, floor($endMin/60), $endMin%60);
                }

                // Determine used period range from registered classes
                $minPeriod = 1;
                $maxPeriod = 9;
                if ($regs->count() > 0) {
                    $minPeriod = $regs->min(fn($r) => $r->classSection->shift->start_period ?? 1);
                    $maxPeriod = $regs->max(fn($r) => $r->classSection->shift->end_period ?? 9);
                }
                // Ensure at least periods 1-9 are shown
                $minPeriod = min($minPeriod, 1);
                $maxPeriod = max($maxPeriod, 9);

                // Build a lookup: [dbDay][period] => [regs]
                $schedule = [];
                foreach ($regs as $regIdx => $reg) {
                    $cs = $reg->classSection;
                    $shift = $cs->shift;
                    if (!$shift) continue;
                    $dbDay = $cs->day_of_week;
                    for ($p = $shift->start_period; $p <= $shift->end_period; $p++) {
                        $schedule[$dbDay][$p][] = $reg;
                    }
                }

                // Assign colors per classSection id
                $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#F97316'];
                $colorMap = [];
                $cIdx = 0;
                foreach ($regs as $reg) {
                    $csId = $reg->classSection->id;
                    if (!isset($colorMap[$csId])) {
                        $colorMap[$csId] = $colors[$cIdx % count($colors)];
                        $cIdx++;
                    }
                }
            @endphp

            <div style="display: grid; grid-template-columns: 100px repeat(7, 1fr); gap: 1px; background: #e2e8f0; border-radius: 8px; overflow: hidden;">
                <!-- Header: empty corner -->
                <div style="background: white; padding: 12px 8px; text-align: center; font-size: 12px; font-weight: 600; color: #64748b; border-bottom: 2px solid #cbd5e0;">Tiết</div>

                <!-- Day headers (dynamic via JS) -->
                @for($i = 0; $i < 7; $i++)
                    @php
                        $date = $weekStart->copy()->addDays($i);
                        $isToday = $date->isToday();
                    @endphp
                    <div id="dayHeader{{ $i }}" style="background: white; padding: 12px 8px; text-align: center; border-bottom: 2px solid #cbd5e0; {{ $isToday ? 'background: linear-gradient(135deg, #8595F6 0%, #b99ce9 100%);' : '' }}">
                        <div class="day-name" style="font-size: 12px; font-weight: 600; color: {{ $isToday ? 'white' : '#2d2d2d' }};">{{ $dayNames[$i] }}</div>
                        <div class="day-date" style="font-size: 14px; font-weight: 700; color: {{ $isToday ? 'white' : '#1e293b' }}; margin-top: 2px;">{{ $date->format('d') }}</div>
                    </div>
                @endfor

                <!-- Period rows -->
                @for($period = $minPeriod; $period <= $maxPeriod; $period++)
                    <!-- Period label -->
                    <div style="background: white; padding: 10px 6px; text-align: center; font-size: 11px; font-weight: 600; color: #64748b; border-bottom: 1px solid #e2e8f0;">
                        <div>Tiết {{ $period }}</div>
                        <div style="font-size: 10px; font-weight: 400; color: #94a3b8; margin-top: 2px;">{{ $periodTimes[$period] ?? '' }}</div>
                    </div>

                    <!-- Day cells for this period -->
                    @for($dayIdx = 0; $dayIdx < 7; $dayIdx++)
                        @php
                            $dbDay = $dbDays[$dayIdx];
                            $cellRegs = $schedule[$dbDay][$period] ?? [];
                            $isToday = $weekStart->copy()->addDays($dayIdx)->isToday();
                        @endphp
                        <div class="day-cell" data-day-idx="{{ $dayIdx }}" style="background: {{ $isToday ? '#faf5ff' : 'white' }}; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; min-height: 48px; position: relative; padding: 2px;">
                            @foreach($cellRegs as $reg)
                                @php
                                    $cs = $reg->classSection;
                                    $shift = $cs->shift;
                                    $course = $cs->course;
                                    $color = $colorMap[$cs->id] ?? '#3B82F6';
                                    // Only show full card on the first period of the shift
                                    $isFirstPeriod = ($period == $shift->start_period);
                                    $spanPeriods = $shift->end_period - $shift->start_period + 1;
                                @endphp
                                @if($isFirstPeriod)
                                <div style="position: absolute; top: 2px; left: 2px; right: 2px; height: calc({{ $spanPeriods }} * 100% - 4px); background: {{ $color }}; border-radius: 6px; padding: 6px 8px; color: white; font-size: 11px; font-weight: 500; overflow: hidden; display: flex; flex-direction: column; justify-content: center; z-index: 5; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.15);" title="{{ $course->code }} - {{ $course->name }} ({{ $cs->section_code }})&#10;GV: {{ $cs->lecturer->name ?? 'TBD' }}&#10;Phòng: {{ $cs->room->code ?? 'TBD' }}&#10;Tiết {{ $shift->start_period }}-{{ $shift->end_period }}">
                                    <div style="font-weight: 700; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; font-size: 12px;">{{ $course->code }}</div>
                                    <div style="font-size: 10px; opacity: 0.95; white-space: nowrap; margin-top: 1px;">{{ $cs->section_code }}</div>
                                    <div style="font-size: 10px; opacity: 0.9; white-space: nowrap;">{{ $cs->room ? $cs->room->code : 'TBA' }}</div>
                                    @if($spanPeriods >= 3)
                                    <div style="font-size: 9px; opacity: 0.8; white-space: nowrap; margin-top: 2px;">T{{ $shift->start_period }}-{{ $shift->end_period }}</div>
                                    @endif
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endfor
                @endfor
            </div>
        </div>

        <!-- Legend -->
        <div style="margin-top: 20px; padding: 16px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #6B4B9D;">
            <p style="margin: 0 0 12px; color: #1e293b; font-weight: 600; font-size: 13px;">📋 Hướng dẫn:</p>
            <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 12px;">
                <li style="margin-bottom: 6px;">Di chuột vào lớp để xem chi tiết (mã lớp, phòng, giảng viên)</li>
                <li style="margin-bottom: 6px;">Các lớp được hiển thị theo tiết học và thứ trong tuần</li>
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
                    <div class="mini-cal-day" data-year="{{ now()->format('Y') }}" data-month="{{ now()->format('n') }}" data-day="{{ $day }}" style="padding: 8px 0; text-align: center; font-size: 12px; font-weight: {{ $isToday ? 600 : 400 }}; background: {{ $isToday ? 'rgba(255,255,255,0.2)' : 'transparent' }}; border-radius: 6px; cursor: pointer; transition: all 0.2s;" onclick="selectDay({{ now()->format('Y') }}, {{ now()->format('n') }}, {{ $day }})">
                        <div style="color: white; border-radius: 50%; display: inline-block; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; {{ $isToday ? 'background: rgba(255,255,255,0.3); font-weight: 700;' : '' }}">{{ $day }}</div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Stats -->
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
            <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <p style="margin: 0 0 4px; color: #64748b; font-size: 12px; font-weight: 500;">📅 Năm học</p>
                <p style="margin: 0; color: #1e293b; font-size: 16px; font-weight: 700;">{{ $year }}</p>
            </div>
            <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <p style="margin: 0 0 4px; color: #64748b; font-size: 12px; font-weight: 500;">📖 Học kỳ</p>
                <p style="margin: 0; color: #1e293b; font-size: 16px; font-weight: 700;">{{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</p>
            </div>
            @if($wave ?? false)
            <div style="margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;">
                <p style="margin: 0 0 4px; color: #64748b; font-size: 12px; font-weight: 500;">🔔 Đợt đăng ký</p>
                <p style="margin: 0; color: #6B4B9D; font-size: 13px; font-weight: 600;">{{ $wave->name ?? 'Đợt hiện tại' }}</p>
                <p style="margin: 4px 0 0; color: #94a3b8; font-size: 11px;">{{ \Carbon\Carbon::parse($wave->starts_at)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y') }}</p>
            </div>
            @endif
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div style="background: #f0fdf4; padding: 12px; border-radius: 8px; text-align: center;">
                    <p style="margin: 0; color: #16a34a; font-size: 22px; font-weight: 700;">{{ $regs->count() }}</p>
                    <p style="margin: 4px 0 0; color: #4ade80; font-size: 11px; font-weight: 500;">Tổng lớp</p>
                </div>
                <div style="background: #eff6ff; padding: 12px; border-radius: 8px; text-align: center;">
                    <p style="margin: 0; color: #2563eb; font-size: 22px; font-weight: 700;">{{ $totalCredits ?? 0 }}</p>
                    <p style="margin: 4px 0 0; color: #60a5fa; font-size: 11px; font-weight: 500;">Tín chỉ</p>
                </div>
            </div>
        </div>

        <!-- Registered Classes List -->
        @if($regs->count() > 0)
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
            <p style="margin: 0 0 12px; color: #1e293b; font-weight: 600; font-size: 13px;">📚 Lớp đã đăng ký</p>
            @foreach($regs as $regItem)
                @php
                    $cs = $regItem->classSection;
                    $course = $cs->course;
                    $shift = $cs->shift;
                    $regColor = $colorMap[$cs->id] ?? '#3B82F6';
                @endphp
                <div style="display: flex; gap: 10px; align-items: flex-start; padding: 10px 0; {{ !$loop->last ? 'border-bottom: 1px solid #f1f5f9;' : '' }}">
                    <div style="width: 4px; min-height: 36px; background: {{ $regColor }}; border-radius: 2px; flex-shrink: 0; margin-top: 2px;"></div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="margin: 0; color: #1e293b; font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $course->code }} - {{ $cs->section_code }}</p>
                        <p style="margin: 2px 0 0; color: #64748b; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $course->name }}</p>
                        <div style="display: flex; gap: 8px; margin-top: 4px; flex-wrap: wrap;">
                            <span style="color: #94a3b8; font-size: 10px;">{{ $cs->day_name }}</span>
                            <span style="color: #94a3b8; font-size: 10px;">T{{ $shift->start_period ?? '?' }}-{{ $shift->end_period ?? '?' }}</span>
                            <span style="color: #94a3b8; font-size: 10px;">{{ $cs->room->code ?? 'TBD' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<script>
let currentMonth = {{ now()->format('n') }};
let currentYear = {{ now()->format('Y') }};
const today = new Date({{ now()->format('Y') }}, {{ now()->format('n') - 1 }}, {{ now()->format('j') }});
const dayNames = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];

// Track current displayed week (Monday of that week)
let currentWeekStart = getMonday(today);

function getMonday(d) {
    const date = new Date(d);
    const day = date.getDay();
    const diff = date.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(date.getFullYear(), date.getMonth(), diff);
}

function formatDate(d) {
    return String(d.getDate()).padStart(2, '0');
}

function formatDateFull(d) {
    return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
}

function isSameDay(a, b) {
    return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
}

function isSameWeek(d1, weekMonday) {
    const m = getMonday(d1);
    return isSameDay(m, weekMonday);
}

function updateWeekDisplay() {
    // Update day headers
    for (let i = 0; i < 7; i++) {
        const headerEl = document.getElementById('dayHeader' + i);
        if (!headerEl) continue;
        const d = new Date(currentWeekStart);
        d.setDate(d.getDate() + i);
        const isToday = isSameDay(d, today);

        headerEl.style.background = isToday ? 'linear-gradient(135deg, #8595F6 0%, #b99ce9 100%)' : 'white';
        const nameEl = headerEl.querySelector('.day-name');
        const dateEl = headerEl.querySelector('.day-date');
        if (nameEl) nameEl.style.color = isToday ? 'white' : '#2d2d2d';
        if (dateEl) {
            dateEl.textContent = formatDate(d);
            dateEl.style.color = isToday ? 'white' : '#1e293b';
        }
    }

    // Update today highlighting on day cells
    document.querySelectorAll('.day-cell').forEach(cell => {
        const idx = parseInt(cell.dataset.dayIdx);
        const d = new Date(currentWeekStart);
        d.setDate(d.getDate() + idx);
        cell.style.background = isSameDay(d, today) ? '#faf5ff' : 'white';
    });

    // Update week label
    const weekEnd = new Date(currentWeekStart);
    weekEnd.setDate(weekEnd.getDate() + 6);
    const weekLabel = document.getElementById('weekLabel');
    if (weekLabel) {
        weekLabel.textContent = formatDateFull(currentWeekStart) + ' — ' + formatDateFull(weekEnd);
    }

    // Highlight selected week in mini calendar
    highlightWeekInMiniCal();
}

function changeWeek(delta) {
    currentWeekStart.setDate(currentWeekStart.getDate() + delta * 7);
    currentWeekStart = getMonday(currentWeekStart);
    updateWeekDisplay();
}

function goToToday() {
    currentWeekStart = getMonday(today);
    // Also switch mini calendar to today's month
    currentMonth = today.getMonth() + 1;
    currentYear = today.getFullYear();
    renderCalendar();
    updateWeekDisplay();
}

function selectDay(year, month, day) {
    const clicked = new Date(year, month - 1, day);
    currentWeekStart = getMonday(clicked);
    updateWeekDisplay();
}

function highlightWeekInMiniCal() {
    document.querySelectorAll('.mini-cal-day').forEach(el => {
        const y = parseInt(el.dataset.year);
        const m = parseInt(el.dataset.month);
        const d = parseInt(el.dataset.day);
        const cellDate = new Date(y, m - 1, d);
        const inWeek = isSameWeek(cellDate, currentWeekStart);
        const isToday = isSameDay(cellDate, today);

        if (inWeek) {
            el.style.background = 'rgba(255,255,255,0.2)';
            el.style.fontWeight = '600';
        } else {
            el.style.background = 'transparent';
            el.style.fontWeight = isToday ? '600' : '400';
        }

        // Today circle
        const inner = el.querySelector('div');
        if (inner) {
            inner.style.background = isToday ? 'rgba(255,255,255,0.3)' : 'transparent';
            inner.style.fontWeight = isToday ? '700' : 'inherit';
        }
    });
}

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
        const cellDate = new Date(currentYear, currentMonth - 1, day);
        const isToday = isSameDay(cellDate, today);
        const inWeek = isSameWeek(cellDate, currentWeekStart);
        const bgColor = inWeek ? 'rgba(255,255,255,0.2)' : 'transparent';
        const fontWeight = (isToday || inWeek) ? '600' : '400';
        const innerBg = isToday ? 'background: rgba(255,255,255,0.3); font-weight: 700;' : '';

        html += `<div class="mini-cal-day" data-year="${currentYear}" data-month="${currentMonth}" data-day="${day}" style="padding: 8px 0; text-align: center; font-size: 12px; font-weight: ${fontWeight}; background: ${bgColor}; border-radius: 6px; cursor: pointer; transition: all 0.2s;" onclick="selectDay(${currentYear}, ${currentMonth}, ${day})">`;
        html += `<div style="color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; margin: 0 auto; ${innerBg}">${day}</div>`;
        html += '</div>';
    }

    document.getElementById('calendarGrid').innerHTML = html;
}

// Initialize on page load
updateWeekDisplay();
</script>
@endsection