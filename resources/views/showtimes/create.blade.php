<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ isset($room->id) ? 'Edit room' : 'Add New room' }}</title>
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
    // Ensure $showtime is always defined
    if (!isset($showtime)) {
        $showtime = new Showtime();
    }
@endphp

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @include('fragments.sidebar', ['key' => 'showtime', 'subkey' => isset($showtime->id) ? 'showtime_all' : 'showtime_news'])
            </div>

            <div class="d-flex flex-column wrapper">
                {{-- Header --}}
                @include('fragments.header')

                <div class="content">
                    <section class="form-container">
                        <div class="form-container-header d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3">
                                {{ isset($showtime->id) ? 'Edit Showtime Information' : 'Add New Showtime' }}
                            </h1>
                        </div>
                
                        <div class="form-container-content">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    {{-- Showtime Form --}}
                                    <form
                                        action="{{ isset($showtime->id) ? route('showtimes.update', $showtime->id) : route('showtimes.store') }}"
                                        method="POST" id="form-showtime" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($showtime->id))
                                            @method('PUT')
                                        @endif
                
                                        {{-- Hidden ID Field --}}
                                        <input type="hidden" name="id" value="{{ old('id', $showtime->id ?? '') }}" />
                
                                        {{-- Film --}}
                                        <div class="mb-3">
                                            <label for="film_id" class="form-label">Film</label>
                                            <select class="form-control @error('film_id') is-invalid @enderror" id="film_id" name="film_id" required>
                                                <option value="" disabled selected>Select a Film</option>
                                                @foreach($films as $film)
                                                    <option value="{{ $film->id }}" {{ old('film_id', $showtime->film_id ?? '') == $film->id ? 'selected' : '' }}>
                                                        {{ $film->film_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('film_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                
                                        {{-- Room --}}
                                        <div class="mb-3">
                                            <label for="room_id" class="form-label">Room</label>
                                            <select class="form-control @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                                                <option value="" disabled selected>Select a Room</option>
                                                @foreach($rooms as $room)
                                                    <option value="{{ $room->id }}" {{ old('room_id', $showtime->room_id ?? '') == $room->id ? 'selected' : '' }}>
                                                        {{ $room->room_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('room_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                
                                        {{-- Start Time --}}
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Start Time</label>
                                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time"
                                                value="{{ old('start_time', $showtime->start_time ?? '') }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                
                                        {{-- Day --}}
                                        <div class="mb-3">
                                            <label for="day" class="form-label">Day</label>
                                            <input type="date" class="form-control @error('day') is-invalid @enderror" id="day" name="day"
                                                value="{{ old('day', $showtime->day ?? '') }}" required>
                                            @error('day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                // Close Summernote dialogs with custom button
                $('body').on('click', '.close-summernote-dialog', function() {
                    $('.note-modal').removeClass('open');
                    $('.note-modal-backdrop').hide();
                });
            });
        </script>
    </body>

</html>
