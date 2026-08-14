<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @param  array{tanggal_mulai?: string, tanggal_selesai?: string, intern_id?: int, mentor_id?: int, status?: string}  $filters
     */
    public function __construct(protected array $filters = []) {}

    public function query(): Builder
    {
        return Attendance::query()
            ->with('intern.user', 'intern.mentor.user')
            ->when(
                $this->filters['tanggal_mulai'] ?? null,
                fn ($q, $tanggalMulai) => $q->whereDate('tanggal', '>=', $tanggalMulai)
            )
            ->when(
                $this->filters['tanggal_selesai'] ?? null,
                fn ($q, $tanggalSelesai) => $q->whereDate('tanggal', '<=', $tanggalSelesai)
            )
            ->when(
                $this->filters['intern_id'] ?? null,
                fn ($q, $internId) => $q->where('intern_id', $internId)
            )
            ->when(
                $this->filters['mentor_id'] ?? null,
                fn ($q, $mentorId) => $q->whereHas('intern', fn ($iq) => $iq->where('mentor_id', $mentorId))
            )
            ->when(
                $this->filters['status'] ?? null,
                fn ($q, $status) => $q->where('status', $status)
            )
            ->orderBy('tanggal')
            ->orderBy('intern_id');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peserta',
            'Universitas',
            'Mentor',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Keterlambatan (menit)',
        ];
    }

    public function map($attendance): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $attendance->intern->user->name,
            $attendance->intern->universitas,
            $attendance->intern->mentor?->user?->name ?? '-',
            $attendance->tanggal->format('d-m-Y'),
            $attendance->waktu_masuk?->format('H:i') ?? '-',
            $attendance->waktu_pulang?->format('H:i') ?? '-',
            ucfirst($attendance->status),
            $attendance->keterlambatan,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}