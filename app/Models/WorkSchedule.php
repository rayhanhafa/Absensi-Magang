<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
    'nama_jadwal',
    'jam_masuk',
    'jam_pulang',
    'toleransi_keterlambatan',
    'is_active',
];

    protected function casts(): array
    {
        return [
            'jam_masuk' => 'datetime:H:i',
            'jam_pulang' => 'datetime:H:i',
            'toleransi_keterlambatan' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}