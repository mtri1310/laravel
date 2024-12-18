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
    // Ensure $room is always defined
    if (!isset($room)) {
        $room = new \App\Models\Room();
    }
@endphp

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @include('fragments.sidebar', ['key' => 'room', 'subkey' => isset($room->id) ? 'room_all' : 'room_news'])
            </div>

            <div class="d-flex flex-column wrapper">
                {{-- Header --}}
                @include('fragments.header')

                <div class="content">
                    <section class="form-container">
                        <div class="form-container-header d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3">
                                {{ isset($room->id) ? 'Edit room Information' : 'Add New room' }}
                            </h1>
                        </div>
                        <div class="form-container-content">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    {{-- room Form --}}
                                    <form
                                        action="{{ isset($room->id) ? route('rooms.update', $room->id) : route('rooms.store') }}"
                                        method="POST" id="form-room" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($room->id))
                                            @method('PUT')
                                        @endif

                                        {{-- Hidden ID Field --}}
                                        <input type="hidden" name="id" value="{{ old('id', $room->id ?? '') }}" />

                                        {{-- room Name --}}
                                        <div class="mb-3">
                                            <label for="room_name" class="form-label">Room Name</label>
                                            <input type="text" class="form-control @error('room_name') is-invalid @enderror" id="room_name" name="room_name"
                                                value="{{ old('room_name', $room->room_name ?? '') }}" required>
                                            @error('room_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        
                                        {{-- Capacity --}}
                                        <div class="mb-3">
                                            <label for="capacity" class="form-label">Capacity</label>
                                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity"
                                                value="{{ old('capacity', $room->capacity ?? '') }}" required>
                                            @error('capacity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Room Type --}}
                                        <div class="mb-3">
                                            <label for="room_type" class="form-label">Room Type</label>
                                            <input type="text" class="form-control @error('room_type') is-invalid @enderror" id="room_type"
                                                name="room_type"
                                                value="{{ old('room_type', $room->room_type ?? '') }}" required>
                                            @error('room_type')
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
