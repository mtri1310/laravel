<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ isset($film->id) ? 'Edit Film' : 'Add New Film' }}</title>
    <link href="{{ asset('assets/images/icon.png') }}" rel="icon" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/add_form.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
</head>

@php
    // Ensure $film is always defined
    if (!isset($film)) {
        $film = new Film();
    }
@endphp

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @include('fragments.sidebar', ['key' => 'film', 'subkey' => isset($film->id) ? 'film_all' : 'film_news'])
            </div>

            <div class="d-flex flex-column wrapper">
                {{-- Header --}}
                @include('fragments.header')

                <div class="content">
                    <section class="form-container">
                        <div class="form-container-header d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3">
                                {{ isset($film->id) ? 'Edit Film Information' : 'Add New Film' }}
                            </h1>
                        </div>
                        <div class="form-container-content">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    {{-- Film Form --}}
                                    <form
                                        action="{{ isset($film->id) ? route('films.update', $film->id) : route('films.store') }}"
                                        method="POST" id="form-film" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($film->id))
                                            @method('PUT')
                                        @endif

                                        {{-- Hidden ID Field --}}
                                        <input type="hidden" name="id" value="{{ old('id', $film->id ?? '') }}" />

                                        {{-- Film Name --}}
                                        <div class="mb-3">
                                            <label for="film_name" class="form-label">Film Name</label>
                                            <input type="text" class="form-control @error('film_name') is-invalid @enderror" id="film_name" name="film_name"
                                                value="{{ old('film_name', $film->film_name ?? '') }}" required>
                                            @error('film_name')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Story Line --}}
                                        <div class="mb-3">
                                            <label for="story_line" class="form-label">Story Line</label>
                                            <textarea class="form-control @error('story_line') is-invalid @enderror" id="story_line" name="story_line" rows="4">{{ old('story_line', $film->story_line ?? '') }}</textarea>
                                            @error('story_line')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Movie Genre --}}
                                        <div class="mb-3">
                                            <label for="movie_genre" class="form-label">Movie Genre</label>
                                            <input type="text" class="form-control @error('movie_genre') is-invalid @enderror" id="movie_genre"
                                                name="movie_genre"
                                                value="{{ old('movie_genre', $film->movie_genre ?? '') }}" required>
                                            @error('movie_genre')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Director --}}
                                        <div class="mb-3">
                                            <label for="director" class="form-label">Director</label>
                                            <input type="text" class="form-control @error('director') is-invalid @enderror" id="director" name="director"
                                                value="{{ old('director', $film->director ?? '') }}" required>
                                            @error('director')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Actor --}}
                                        <div class="mb-3">
                                            <label for="actor" class="form-label">Actor</label>
                                            <input type="text" class="form-control @error('actor') is-invalid @enderror" id="actor" name="actor"
                                                value="{{ old('actor', $film->actor ?? '') }}" required>
                                            @error('actor')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Duration --}}
                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Duration</label>
                                            <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration"
                                                value="{{ old('duration', $film->duration ?? '') }}" required>
                                            @error('duration')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Language --}}
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <input type="text" class="form-control @error('language') is-invalid @enderror" id="language"
                                                name="language" value="{{ old('language', $film->language ?? '') }}"
                                                required>
                                            @error('language')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Censorship --}}
                                        <div class="mb-3">
                                            <label for="censorship" class="form-label">Censorship</label>
                                            <input type="text" class="form-control @error('censorship') is-invalid @enderror" id="censorship"
                                                name="censorship"
                                                value="{{ old('censorship', $film->censorship ?? '') }}">
                                            @error('censorship')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Review --}}
                                        <div class="mb-3">
                                            <label for="review" class="form-label">Review (0-5)</label>
                                            <input type="number" class="form-control @error('review') is-invalid @enderror" id="review" name="review"
                                                min="0" max="5" step="any"
                                                value="{{ old('review', $film->review ?? '') }}">
                                            @error('review')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Release Date --}}
                                        <div class="mb-3">
                                            <label for="release" class="form-label">Release Date</label>
                                            <input type="date" class="form-control @error('release') is-invalid @enderror" id="release" name="release"
                                                value="{{ old('release', isset($film->release) ? $film->release->format('Y-m-d') : '') }}">
                                            @error('release')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Thumbnail Upload --}}
                                        <div class="mb-3">
                                            <label class="form-label">Thumbnail</label>
                                            <div class="upload-zone d-flex justify-content-center align-items-center position-relative border rounded p-3">
                                                <!-- File Input for Uploading New Thumbnail -->
                                                <input type="file" name="thumbnail"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    onchange="loadFile(event)" class="upload-zone-input"
                                                    id="input-image" {{ isset($film->id) ? '' : 'required' }} />

                                                <!-- Hidden Input to Store Existing Thumbnail URL (Only in Edit) -->
                                                @if(isset($film->thumbnail))
                                                    <input type="hidden" id="existing_thumbnail" name="existing_thumbnail"
                                                        value="{{ old('existing_thumbnail', $film->thumbnail) }}" />
                                                @endif

                                                <!-- Image Preview -->
                                                <img id="image-output"
                                                    src="{{ isset($film->thumbnail) ? $film->thumbnail : '' }}"
                                                    alt="Image preview"
                                                    class="img-thumbnail" style="width: 100%; height: 100%; object-fit: contain; display: {{ isset($film->thumbnail) ? 'block' : 'none' }}" />

                                                <!-- Upload Zone Content -->
                                                <div class="upload-zone-content text-center">
                                                    <i class="fas fa-upload fa-2x mb-2 text-muted"></i>
                                                    <div class="upload-zone-title text-muted">Select file</div>
                                                    <div class="upload-zone-desc text-muted">Click to browse your machine</div>
                                                </div>
                                            </div>

                                            {{-- Error Messages --}}
                                            @error('thumbnail')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('existing_thumbnail')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <div class="d-flex flex-column justify-content-center">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="status" name="status" value="1"
                                                        {{ old('status', $film->status ?? 1) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status">Is
                                                        Published?</label>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- jQuery -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap JS -->
        <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Custom JS -->
        <script src="{{ asset('assets/js/index.js') }}"></script>
        <!-- Summernote JS -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

        <script>

            // Initialize Summernote and handle SweetAlert messages
            $(document).ready(function() {
                // Initialize Summernote
                $('#story_line').summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['forecolor']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    tooltip: false,
                    dialogsInBody: true
                });

                // Close Summernote dialogs with custom button
                $('body').on('click', '.close-summernote-dialog', function() {
                    $('.note-modal').removeClass('open');
                    $('.note-modal-backdrop').hide();
                });

                // Display existing thumbnail if editing
                @if(isset($film->thumbnail))
                    $('#image-output').show();
                    $('.upload-zone-content').hide();
                @endif
            });
        </script>
    </body>

</html>
