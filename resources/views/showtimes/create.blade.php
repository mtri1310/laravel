<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type = "image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/add_form.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @if (isset($room->id))
                    @include('fragments.sidebar', ['key' => 'room', 'subkey' => 'room_all'])
                @else
                    @include('fragments.sidebar', ['key' => 'room', 'subkey' => 'room_news'])
                @endif
            </div>

            <div class="d-flex flex-column wrapper">
                @include('fragments.header')
    
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">
                        <form action="{{ isset($showtime->id) ? route('showtimes.edit', $showtime->id) : route('showtimes.store') }}" method="post" id="form-showtime" enctype="multipart/form-data">
                            @csrf
                            @if (isset($showtime->id))
                                @method('PUT')
                            @endif
                        
                            <input type="hidden" name="id" value="{{ old('id', $showtime->id ?? '') }}" />
                        
                            <div class="mb-4">
                                <label class="form-label" for="film_id">Film</label>
                                <select class="form-control" id="film_id" name="film_id" required>
                                    <option value="">-- Select Film --</option>
                                    @foreach($films as $film)
                                        <option value="{{ $film->id }}" {{ old('film_id', $showtime->film_id ?? '') == $film->id ? 'selected' : '' }}>
                                            {{ $film->film_name }}  <!-- Thay "title" bằng trường thích hợp của bộ phim -->
                                        </option>
                                    @endforeach
                                </select>
                                @error('film_id')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-4">
                                <label class="form-label" for="room_id">Room</label>
                                <select class="form-control" id="room_id" name="room_id" required>
                                    <option value="">-- Select Room --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', $showtime->room_id ?? '') == $room->id ? 'selected' : '' }}>
                                            {{ $room->room_name }}  <!-- Thay "room_name" bằng trường thích hợp của phòng -->
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-4">
                                <label class="form-label" for="day">Day</label>
                                <input type="date" class="form-control" id="day" name="day" value="{{ old('day', $showtime->day ?? '') }}" required />
                                @error('day')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-4">
                                <label class="form-label" for="start_time">Start Time</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time', $showtime->start_time ?? '') }}" required />
                                @error('start_time')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-4 d-flex align-items-center justify-content-end">
                                <button type="submit" class="btn btn-danger auth-btn">Save</button>
                            </div>
                        </form>
                        
                        
                    </div>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/index.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

        <script>
            $(document).ready(function() {

                if ($("#image").val() != '' && $("#image").val() != null) {
                    let image_output = document.getElementById('image-output');
                    $('#image-output').css('display', 'block')
                    image_output.src = $("#image").val();
                    $('.upload-zone-content').css('display', 'none');
                }

                if ($('#release').val() != '' && $('#release').val() != null) {
                    $('#release').val(formatDate($('#release').val()));
                }

                $('#story_line').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['forecolor']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen']]
                    ],
                    tooltip: false,
                    dialogsInBody: true

                });
                let buttonClose = $(
                    '<button type="button" class="close-summernote-dialog" aria-hidden="true" tabindex="-1">&times;</button>'
                    )
                $(".note-modal-content").append(buttonClose);

                $('button.close-summernote-dialog').click(function() {
                    $('.note-modal').removeClass('open');
                    $('.note-modal-backdrop').css('display', 'none');
                })

            })
        </script>
        <script>
            $('#release').keyup(function(event) {
                formatDate(event, $(this))
            })
        </script>
    </div>
</body>
