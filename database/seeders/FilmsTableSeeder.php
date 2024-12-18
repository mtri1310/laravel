<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;

class FilmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create 50 films
        // Film::factory()->count(20)->create();
        Film::insert([
            [
                'id' => 1,
                'film_name' => 'Linh Miêu: Quỷ Nhập Tràng',
                'thumbnail' => 'https://phimimg.com/upload/vod/20241216-1/87b3bf4f9465892275ade60894320551.jpg',
                'duration' => '109 phút',
                'review' => 4.50,
                'story_line' => 'Nửa đêm, đoàn kiệu rước thây xuất hiện trong không khí ma mị, u ám...',
                'movie_genre' => 'Kinh Dị',
                'censorship' => '18+',
                'language' => 'Tiếng Việt',
                'director' => 'Lưu Thành Luân',
                'actor' => 'Hồng Đào, Samuel An, Nguyễn Thúc Thùy Tiên, Thiên An, Văn Anh',
                'status' => 0,
                'release' => '2024-12-16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'film_name' => 'Black Panther: Chiến Binh Báo Đen',
                'thumbnail' => 'https://phimimg.com/upload/vod/20231018-1/ebe8e68bf6e7d8d3995ab266c1fcf3df.jpg',
                'duration' => '135 phút',
                'review' => 4.80,
                'story_line' => 'Vương quốc Wakanda, quê hương của Black Panther...',
                'movie_genre' => 'Hành Động',
                'censorship' => '15+',
                'language' => 'Tiếng Anh',
                'director' => 'Ryan Coogler',
                'actor' => 'Chadwick Boseman, Michael B. Jordan, Lupita Nyong, Danai Gurira, Martin Freeman',
                'status' => 0,
                'release' => '2023-10-18',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'film_name' => 'Hắc Báo Thiên Hạ',
                'thumbnail' => 'https://phimimg.com/upload/vod/20240207-1/06aa3b10a88807d52beba8e7da4cc6e5.jpg',
                'duration' => '91 phút',
                'review' => 4.50,
                'story_line' => 'Hắc Báo Thiên Hạ - The Black Panther Warriors xoay quanh...',
                'movie_genre' => 'Hành Động',
                'censorship' => '15+',
                'language' => 'Trung Quốc',
                'director' => 'Clarence Fok',
                'actor' => 'Lương Gia Huy, Nhậm Đạt Hoa, Trương Vệ Kiện, Lâm Thanh Hà',
                'status' => 0,
                'release' => '2024-02-07',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'film_name' => 'Captain America: Kẻ Báo Thù Đầu Tiên',
                'thumbnail' => 'https://phimimg.com/upload/vod/20231023-1/c57522ab9758306b8318fc880db5d43d.jpg',
                'duration' => '124 phút',
                'review' => 4.58,
                'story_line' => 'Lấy bổi cách từ đầu năm 1942, khi Mỹ đang tham gia Thế chiến thứ II...',
                'movie_genre' => 'Hành Động',
                'censorship' => '15+',
                'language' => 'Tiếng Anh',
                'director' => 'Joe Johnston',
                'actor' => 'Chris Evans, Tommy Lee Jones, Hugo Weaving, Hayley Atwell, Sebastian Stan',
                'status' => 0,
                'release' => '2023-10-23',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'film_name' => 'Kỳ Nghỉ Lễ Thảm Họa',
                'thumbnail' => 'https://phimimg.com/upload/vod/20241216-1/1784b879fbe179018bb15b58ab0bd28e.jpg',
                'duration' => '93 phút',
                'review' => 4.50,
                'story_line' => 'Đang cố gắng lấy lòng các con, ông bố nghiện công việc...',
                'movie_genre' => 'Hài Hước, Gia Đình',
                'censorship' => '15+',
                'language' => 'Tiếng Anh',
                'director' => 'Rethabile Ramaphakela',
                'actor' => 'Rethabile Ramaphakela, Lunathi Mampofu, Kopano Mahlasi, Lubabalo Tala, Yeya Ralarala',
                'status' => 0,
                'release' => '2024-12-16',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
