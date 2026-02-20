<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi PKL</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #3526B3;
            margin-bottom: 25px;
        }

        .logo-container {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3526B3 0%, #8615D9 100%);
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
            line-height: 60px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #3526B3;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 13px;
            color: #666;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #3526B3;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: 600;
            width: 140px;
            color: #555;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 15px;
        }

        .stat-card {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .stat-card.hadir {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #28a745;
        }

        .stat-card.izin {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
            border-color: #ffc107;
        }

        .stat-card.sakit {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #dc3545;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #3526B3;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10px;
        }

        thead {
            background: linear-gradient(135deg, #3526B3 0%, #8615D9 100%);
            color: white;
        }

        th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 10px;
        }

        th.text-center {
            text-align: center;
        }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        td {
            padding: 10px 8px;
            color: #333;
        }

        td.text-center {
            text-align: center;
        }

        .student-name {
            font-weight: 600;
            color: #3526B3;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .rate-excellent {
            color: #28a745;
            font-weight: bold;
        }

        .rate-good {
            color: #ffc107;
            font-weight: bold;
        }

        .rate-poor {
            color: #dc3545;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            font-size: 9px;
            color: #666;
            text-align: center;
        }

        .footer-info {
            margin-bottom: 5px;
        }

        .page-break {
            page-break-before: always;
        }

        .summary-row {
            font-weight: bold;
            background: #e9ecef !important;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 4px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
        }

        .progress-fill.excellent {
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        }

        .progress-fill.good {
            background: linear-gradient(90deg, #ffc107 0%, #ffb300 100%);
        }

        .progress-fill.poor {
            background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <div class="logo-text">V</div>
        </div>
        <div class="title">Laporan Absensi PKL/Magang</div>
        <div class="subtitle">Vodeco - Program Magang dan PKL</div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Periode:</div>
            <div class="info-value">{{ $startDate }} - {{ $endDate }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Generate:</div>
            <div class="info-value">{{ $generatedAt }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">DiGenerate Oleh:</div>
            <div class="info-value">{{ $generatedBy }}</div>
        </div>
        @if(!$isAdmin)
        <div class="info-row">
            <div class="info-label">Nama Siswa:</div>
            <div class="info-value">{{ $user['name'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value">{{ $user['email'] }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Divisi:</div>
            <div class="info-value">{{ $user['divisi'] }}</div>
        </div>
        @endif
    </div>

    @if($isAdmin)
    <!-- Statistics Summary - Admin -->
    @php
        $totalHadir = $students->sum('hadir');
        $totalIzin = $students->sum('izin');
        $totalSakit = $students->sum('sakit');
        $grandTotal = $totalHadir + $totalIzin + $totalSakit;
        $avgRate = $grandTotal > 0 ? round(($totalHadir / $grandTotal) * 100, 1) : 0;
    @endphp

    <div class="stats-container">
        <div class="stat-card hadir">
            <div class="stat-number">{{ $totalHadir }}</div>
            <div class="stat-label">Total Hadir</div>
        </div>
        <div class="stat-card izin">
            <div class="stat-number">{{ $totalIzin }}</div>
            <div class="stat-label">Total Izin</div>
        </div>
        <div class="stat-card sakit">
            <div class="stat-number">{{ $totalSakit }}</div>
            <div class="stat-label">Total Sakit</div>
        </div>
    </div>

    <!-- Students Table - Admin -->
    <div class="section-title">Rekapitulasi Kehadiran Siswa</div>
    @if($students->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama Siswa</th>
                <th style="width: 15%">Email</th>
                <th style="width: 12%">Divisi</th>
                <th style="width: 12%">Sekolah</th>
                <th class="text-center" style="width: 8%">Hadir</th>
                <th class="text-center" style="width: 8%">Izin</th>
                <th class="text-center" style="width: 8%">Sakit</th>
                <th class="text-center" style="width: 12%">Tingkat Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="student-name">{{ $student['name'] }}</td>
                <td>{{ $student['email'] }}</td>
                <td>{{ $student['divisi'] }}</td>
                <td>{{ $student['sekolah'] }}</td>
                <td class="text-center">
                    <span style="color: #28a745; font-weight: 600;">{{ $student['hadir'] }}</span>
                </td>
                <td class="text-center">
                    <span style="color: #ffc107; font-weight: 600;">{{ $student['izin'] }}</span>
                </td>
                <td class="text-center">
                    <span style="color: #dc3545; font-weight: 600;">{{ $student['sakit'] }}</span>
                </td>
                <td class="text-center">
                    @php
                        $rateClass = $student['attendance_rate'] >= 80 ? 'rate-excellent' :
                                    ($student['attendance_rate'] >= 60 ? 'rate-good' : 'rate-poor');
                        $progressClass = $student['attendance_rate'] >= 80 ? 'excellent' :
                                        ($student['attendance_rate'] >= 60 ? 'good' : 'poor');
                    @endphp
                    <div class="{{ $rateClass }}">{{ $student['attendance_rate'] }}%</div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $progressClass }}" style="width: {{ $student['attendance_rate'] }}%"></div>
                    </div>
                </td>
            </tr>
            @endforeach
            <tr class="summary-row">
                <td colspan="5" style="text-align: right; padding-right: 15px;">TOTAL KESELURUHAN:</td>
                <td class="text-center">{{ $totalHadir }}</td>
                <td class="text-center">{{ $totalIzin }}</td>
                <td class="text-center">{{ $totalSakit }}</td>
                <td class="text-center">{{ $avgRate }}%</td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="no-data">
        Tidak ada data kehadiran pada periode ini.
    </div>
    @endif

    @else
    <!-- Statistics Summary - Murid -->
    <div class="stats-container">
        <div class="stat-card hadir">
            <div class="stat-number">{{ $user['hadir'] }}</div>
            <div class="stat-label">Total Hadir</div>
        </div>
        <div class="stat-card izin">
            <div class="stat-number">{{ $user['izin'] }}</div>
            <div class="stat-label">Total Izin</div>
        </div>
        <div class="stat-card sakit">
            <div class="stat-number">{{ $user['sakit'] }}</div>
            <div class="stat-label">Total Sakit</div>
        </div>
    </div>

    <!-- Attendance Details - Murid -->
    <div class="section-title">Detail Kehadiran Harian</div>
    @if($absents->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 12%">Jam Masuk</th>
                <th style="width: 10%">Status</th>
                <th style="width: 23%">Alasan</th>
                <th style="width: 15%">Metode Verifikasi</th>
                <th style="width: 20%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absents as $index => $absent)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($absent->absent_date)->format('d M Y') }}</td>
                <td>{{ $absent->created_at ? $absent->created_at->format('H:i') . ' WIB' : '-' }}</td>
                <td class="text-center">
                    @if(strtolower($absent->status) === 'hadir')
                        <span class="badge badge-success">Hadir</span>
                    @elseif(strtolower($absent->status) === 'izin')
                        <span class="badge badge-warning">Izin</span>
                    @else
                        <span class="badge badge-danger">Sakit</span>
                    @endif
                </td>
                <td>{{ $absent->reason ?? '-' }}</td>
                <td>{{ $absent->verification_method === 'selfie' ? 'Selfie' : 'QR Code' }}</td>
                <td>
                    @if($absent->checkout_at)
                        Checkout: {{ $absent->checkout_at->format('H:i') }}
                        @if($absent->early_leave_reason)
                            <br>{{ $absent->early_leave_reason }}
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        Tidak ada data kehadiran pada periode ini.
    </div>
    @endif
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-info">
            Dokumen ini digenerate secara otomatis oleh Sistem Absensi PKL Vodeco
        </div>
        <div class="footer-info">
            Tanggal Generate: {{ $generatedAt }} | Oleh: {{ $generatedBy }}
        </div>
        <div class="footer-info" style="margin-top: 10px; font-weight: 600; color: #3526B3;">
            Vodeco Internship Program - Building Future Tech Leaders
        </div>
    </div>
</body>
</html>
