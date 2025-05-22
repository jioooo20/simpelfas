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
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'array', 'max:3'], // max 3 files
            'foto.*' => ['file', 'mimetypes:image/jpeg,image/png', 'max:10240'], // per file
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
            'foto.array' => 'Foto harus berupa array gambar.',
            'foto.max' => 'Maksimal 3 foto yang dapat diunggah.',
            'foto.*.file' => 'Setiap foto harus berupa file.',
            'foto.*.mimetypes' => 'Setiap foto harus berformat JPEG atau PNG.',
            'foto.*.max' => 'Ukuran setiap foto maksimal 10MB.',
        ];
    }
}
