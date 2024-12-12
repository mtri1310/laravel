<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFilmRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'film_name' => 'required|string|max:255',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Kiểm tra tệp hình ảnh
            'duration' => 'required|string|max:50', // Giới hạn độ dài cho trường duration
            'review' => 'numeric|min:0|max:5', // Đánh giá, tối thiểu 0 và tối đa 5
            'story_line' => 'string', // Câu chuyện có thể bỏ trống
            'movie_genre' => 'required|string|max:100', // Thể loại phim bắt buộc
            'censorship' => 'string|max:50', // Độ tuổi kiểm duyệt có thể bỏ trống
            'language' => 'required|string|max:50', // Ngôn ngữ phim bắt buộc
            'director' => 'required|string|max:255', // Đạo diễn, bắt buộc
            'actor' => 'required|string|max:500', // Diễn viên, có thể chứa nhiều tên
            'status' => 'required|boolean', // Trạng thái phim (công chiếu hoặc không)
            'release' => 'required|string', // Phim đã được phát hành hay chưa
        ];
    }

}