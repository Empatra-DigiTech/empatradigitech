<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Menghapus berkas lama dari storage/app/public saat kolom berkas diganti atau barisnya dihapus,
 * supaya tidak menumpuk. Berlaku untuk semua jalur (controller, tinker, seeder), bukan satu controller.
 *
 * Pakai di model: `use DeletesStoredFiles;` lalu `protected array $storedFiles = ['image'];`
 *
 * Aman karena:
 * - hanya menyentuh disk "public" (tidak pernah public/assets atau berkas aplikasi),
 * - nilai berupa URL, path absolut, atau mengandung ".." diabaikan,
 * - berkas tidak dihapus bila baris lain di tabel yang sama masih memakainya,
 * - model soft delete baru menghapus berkas saat forceDelete.
 */
trait DeletesStoredFiles
{
    public static function bootDeletesStoredFiles(): void
    {
        static::updated(function ($model) {
            foreach ($model->storedFileColumns() as $column) {
                if ($model->wasChanged($column)) {
                    $model->deleteStoredFile($model->getOriginal($column), $column);
                }
            }
        });

        static::deleted(function ($model) {
            if (in_array(SoftDeletes::class, class_uses_recursive($model), true) && ! $model->isForceDeleting()) {
                return;
            }

            foreach ($model->storedFileColumns() as $column) {
                $model->deleteStoredFile($model->getAttribute($column), $column);
            }
        });
    }

    /** @return array<int, string> */
    public function storedFileColumns(): array
    {
        return $this->storedFiles ?? [];
    }

    protected function deleteStoredFile(mixed $path, string $column): void
    {
        if (! is_string($path) || $path === '') {
            return;
        }

        if (str_contains($path, '..') || str_starts_with($path, '/') || preg_match('#^[a-z][a-z0-9+.\-]*:#i', $path) === 1) {
            return;
        }

        try {
            $stillUsed = $this->newQueryWithoutScopes()
                ->where($column, $path)
                ->where($this->getKeyName(), '!=', $this->getKey())
                ->exists();

            if (! $stillUsed) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Throwable $e) {
            // Gagal membersihkan berkas tidak boleh menggagalkan simpan/hapus data.
            Log::warning('Gagal menghapus berkas lama: ' . $e->getMessage(), ['path' => $path]);
        }
    }
}
