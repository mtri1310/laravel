<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Admin</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type = "image/x-icon">

    <!-- Bootstrap core -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/add_form.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</head>
<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @if(isset($film->id))
                    @include('fragments.sidebar', ['key' => 'film', 'subkey' => 'film_all'])
                @else
                    @include('fragments.sidebar', ['key' => 'film', 'subkey' => 'film_news'])
                @endif
            </div>
    
            <div class="d-flex flex-column wrapper">
                {{-- Header --}}
                @include('fragments.header')
    
                <div class="content">
                    <section class="form-container">
                        <div class="form-container-header d-flex align-items-center justify-content-between">
                            <h1 class="title">
                                {{ isset($film->id) ? 'Sửa Thông Tin Phim' : 'Thêm Phim Mới' }}
                            </h1>
                        </div>
                        <div class="form-container-content">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    {{-- Film Form --}}
                                    <form action="{{ isset($film->id) ? route('films.edit', $film->id) : route('films.store') }}" 
                                          method="post" 
                                          id="form-film" 
                                          enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($film->id))
                                            @method('PUT')
                                        @endif
    
                                        <input type="hidden" name="id" value="{{ old('id', $film->id ?? '') }}" />
    
                                        <div class="mb-4">
                                            <label class="form-label" for="film_name">Film Name</label>
                                            <input type="text" class="form-control" id="film_name" name="film_name" value="{{ old('film_name', $film->film_name ?? '') }}" required />
                                            @error('film_name') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="story_line">Story Line</label>
                                            <textarea class="form-control" id="story_line" name="story_line" rows="4">{{ old('story_line', $film->story_line ?? '') }}</textarea>
                                            @error('story_line') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="movie_genre">Movie Genre</label>
                                            <input type="text" class="form-control" id="movie_genre" name="movie_genre" value="{{ old('movie_genre', $film->movie_genre ?? '') }}" required />
                                            @error('movie_genre') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="director">Director</label>
                                            <input type="text" class="form-control" id="director" name="director" value="{{ old('director', $film->director ?? '') }}" required />
                                            @error('director') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="actor">Actor</label>
                                            <input type="text" class="form-control" id="actor" name="actor" value="{{ old('actor', $film->actor ?? '') }}" required />
                                            @error('actor') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="duration">Duration</label>
                                            <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration', $film->duration ?? '') }}" required />
                                            @error('duration') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="language">Language</label>
                                            <input type="text" class="form-control" id="language" name="language" value="{{ old('language', $film->language ?? '') }}" required />
                                            @error('language') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="censorship">Censorship</label>
                                            <input type="text" class="form-control" id="censorship" name="censorship" value="{{ old('censorship', $film->censorship ?? '') }}" />
                                            @error('censorship') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
    
                                        <div class="mb-4">
                                            <label class="form-label" for="review">Review</label>
                                            <input type="number" class="form-control" id="review" name="review" min="0" max="10" value="{{ old('review', $film->review ?? '') }}" />
                                            @error('review') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label" for="release">Release</label>
                                            <input type="date" class="form-control" id="release" name="release" value="{{ old('release', $film->release ?? '') }}" />
                                            @error('release') 
                                                <span class="form-valid-feedback">{{ $message }}</span> 
                                            @enderror
                                        </div>                                        

                    <!-- Direction -->
                    <div class="mb-3 row">
                        <label for="direction" class="col-md-4 col-form-label text-md-end text-start">Direction</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('direction') is-invalid @enderror" id="direction" name="direction" value="{{ old('direction') }}">
                            @if ($errors->has('direction'))
                                <span class="text-danger">{{ $errors->first('direction') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actor -->
                    <div class="mb-3 row">
                        <label for="actor" class="col-md-4 col-form-label text-md-end text-start">Actor</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('actor') is-invalid @enderror" id="actor" name="actor" value="{{ old('actor') }}">
                            @if ($errors->has('actor'))
                                <span class="text-danger">{{ $errors->first('actor') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3 row">
                        <label for="status" class="col-md-4 col-form-label text-md-end text-start">Status</label>
                        <div class="col-md-6">
                          <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                              <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Showing</option>
                              <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Removed</option>
                          </select>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Release -->
                    <div class="mb-3 row">
                        <label for="release" class="col-md-4 col-form-label text-md-end text-start">Release</label>
                        <div class="col-md-6">
                          <select class="form-control @error('release') is-invalid @enderror" id="release" name="release">
                              <option value="1" {{ old('release') == '1' ? 'selected' : '' }}>Released</option>
                              <option value="0" {{ old('release') == '0' ? 'selected' : '' }}>Not Released</option>
                          </select>
                            @if ($errors->has('release'))
                                <span class="text-danger">{{ $errors->first('release') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Film">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    
    <script>
        $(document).ready(function() {

            if($("#image").val() != '' && $("#image").val() != null) {
                let image_output = document.getElementById('image-output');
                $('#image-output').css('display', 'block')
                image_output.src = $("#image").val();
                $('.upload-zone-content').css('display', 'none');
            }

            if($('#release').val() != '' && $('#release').val() != null) {
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
            let buttonClose = $('<button type="button" class="close-summernote-dialog" aria-hidden="true" tabindex="-1">&times;</button>')
            $(".note-modal-content").append(buttonClose);

            $('button.close-summernote-dialog').click(function(){
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

</body>