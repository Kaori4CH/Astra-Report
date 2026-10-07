<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Menunggu = 'MENUNGGU';
    case Revisi = 'REVISI';
    case Disetujui = 'DISETUJUI';
    case Ditolak = 'DITOLAK';

    /**
     * Dealer hanya boleh mengumpulkan ulang selama status masih REVISI.
     */
    public function allowsResubmission(): bool
    {
        return $this === self::Revisi;
    }

    /**
     * Status yang boleh dipilih supervisor saat memeriksa tugas.
     *
     * @return array<int, self>
     */
    public static function reviewOptions(): array
    {
        return [self::Disetujui, self::Revisi, self::Ditolak];
    }

    /**
     * Teks aktivitas yang dicatat di log pemeriksaan.
     */
    public function reviewActivity(): string
    {
        return match ($this) {
            self::Revisi => 'Supervisor Minta Revisi',
            self::Disetujui => 'Supervisor Menyetujui Pengumpulan',
            self::Ditolak => 'Supervisor Menolak Hasil Pekerjaan',
            self::Menunggu => 'Menunggu Pemeriksaan',
        };
    }
}
