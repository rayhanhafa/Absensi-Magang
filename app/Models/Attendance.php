<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'status',
        'keterlambatan',
        'catatan',
        'latitude',
        'longitude',
        'foto_check_in',
        'foto_check_out',
        'accuracy_check_in',
        'accuracy_check_out',
        'distance_check_in',
        'distance_check_out',
        'location_status_check_in',
        'location_status_check_out',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'waktu_masuk' => 'datetime:H:i:s',
            'waktu_pulang' => 'datetime:H:i:s',
            'keterlambatan' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'accuracy_check_in' => 'integer',
            'accuracy_check_out' => 'integer',
            'distance_check_in' => 'decimal:2',
            'distance_check_out' => 'decimal:2',
        ];
    }

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    public function scopeForIntern($query, int $internId)
    {
        return $query->where('intern_id', $internId);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}