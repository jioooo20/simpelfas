<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'skala' => ['required', 'in:Ringan,Sedang,Berat'],
            'frekuensi' => ['required', 'in:Jarang,Sedang,Sering'],
            'foto' => ['nullable', 'array', 'max:3'],
            'foto.*' => ['file', 'mimetypes:image/jpeg,image/png', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.string' => 'Lokasi harus berupa teks.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            'foto.array' => 'Foto harus berupa array gambar.',
            'foto.max' => 'Maksimal 3 foto yang dapat diunggah.',
            'foto.*.file' => 'Setiap foto harus berupa file.',
            'foto.*.mimetypes' => 'Setiap foto harus berformat JPEG atau PNG.',
            'foto.*.max' => 'Ukuran setiap foto maksimal 10MB.',
            'foto.total_size' => 'Total ukuran semua foto tidak boleh lebih dari 10MB.',
        ];
    }
}
