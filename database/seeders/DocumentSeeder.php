<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'title' => 'Undang-Undang Dasar Negara Republik Indonesia Tahun 1945',
                'document_number' => 'UUD 1945',
                'document_type' => 'UUD 1945',
                'theme' => 'Konstitusi',
                'description' => 'Undang-Undang Dasar Negara Republik Indonesia Tahun 1945',
                'enacted_date' => '1945-08-18',
                'published_date' => '1945-08-18',
                'file_path' => 'documents/sample.pdf',
                'is_active' => true,
                'download_count' => 18290,
            ],
            [
                'title' => 'Undang-undang Nomor 61 Tahun 2024',
                'document_number' => 'UU No. 61 Tahun 2024',
                'document_type' => 'Undang-undang',
                'theme' => 'Kementerian Negara',
                'description' => 'Perubahan Atas Undang-Undang Nomor 39 Tahun 2008 tentang Kementerian Negara',
                'enacted_date' => '2024-10-15',
                'published_date' => '2024-10-15',
                'file_path' => 'documents/sample.pdf',
                'is_active' => true,
                'download_count' => 1678,
            ],
            [
                'title' => 'Undang-undang Nomor 13 Tahun 2003',
                'document_number' => 'UU No. 13 Tahun 2003',
                'document_type' => 'Undang-undang',
                'theme' => 'Ketenagakerjaan',
                'description' => 'Undang-undang tentang Ketenagakerjaan',
                'enacted_date' => '2003-03-25',
                'published_date' => '2003-03-25',
                'file_path' => 'documents/sample.pdf',
                'is_active' => true,
                'download_count' => 5432,
            ],
            [
                'title' => 'Peraturan Pemerintah Nomor 35 Tahun 2021',
                'document_number' => 'PP No. 35 Tahun 2021',
                'document_type' => 'Peraturan Pemerintah',
                'theme' => 'Perjanjian Kerja Waktu Tertentu',
                'description' => 'Peraturan Pemerintah tentang Perjanjian Kerja Waktu Tertentu, Alih Daya, Waktu Kerja dan Waktu Istirahat, dan Pemutusan Hubungan Kerja',
                'enacted_date' => '2021-02-02',
                'published_date' => '2021-02-02',
                'file_path' => 'documents/sample.pdf',
                'is_active' => true,
                'download_count' => 3245,
            ],
            [
                'title' => 'Peraturan Presiden Nomor 68 Tahun 2022',
                'document_number' => 'Perpres No. 68 Tahun 2022',
                'document_type' => 'Peraturan Presiden',
                'theme' => 'Kelembagaan Pelatihan',
                'description' => 'Peraturan Presiden tentang Revitalisasi Pendidikan dan Pelatihan Vokasi',
                'enacted_date' => '2022-06-15',
                'published_date' => '2022-06-15',
                'file_path' => 'documents/sample.pdf',
                'is_active' => true,
                'download_count' => 987,
            ],
            [
                'title' => 'Perppu Nomor 2 Tahun 2022',
                'document_number' => 'Perppu No. 2 Tahun 2022',
                'document_type' => 'Perppu',
                'theme' => 'Cipta Kerja',
                'description' => 'Peraturan Pemerintah Pengganti Undang-Undang tentang Cipta Kerja',
                'enacted_date' => '2022-12-30',
                'published_date' => '2022-12-30',
                'file_path' => 'documents/sample.pdf',
                'is_active' => true,
                'download_count' => 2156,
            ],
        ];

        foreach ($documents as $doc) {
            Document::create($doc);
        }
    }
}
