<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - {{ isset($user->id) ? 'Edit User' : 'Add New User' }}</title>
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
    // Ensure $user is always defined
    if (!isset($user)) {
        $user = new \App\Models\User();
    }
@endphp

<body>
    <div id="page-container" class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <div>
                {{-- Sidebar --}}
                @include('fragments.sidebar', ['key' => 'user', 'subkey' => isset($user->id) ? 'user_all' : 'user_news'])
            </div>

            <div class="d-flex flex-column wrapper">
                {{-- Header --}}
                @include('fragments.header')

                <div class="content">
                    <section class="form-container"> 
                        <div class="form-container-header d-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3">
                                {{ isset($user->id) ? 'Edit User Information' : 'Add New User' }}
                            </h1>
                        </div>
                        <div class="form-container-content">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-8">
                                    {{-- User Form --}}
                                    <form
                                        action="{{ isset($user->id) ? route('users.update', $user->id) : route('users.store') }}"
                                        method="POST" id="form-user" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($user->id))
                                            @method('PUT')
                                        @endif
                    
                                        {{-- Hidden ID Field --}}
                                        <input type="hidden" name="id" value="{{ old('id', $user->id ?? '') }}" />
                    
                                        {{-- Username --}}
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username"
                                                value="{{ old('username', $user->username ?? '') }}" required>
                                            @error('username')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                    
                                        {{-- Password --}}
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" 
                                                   placeholder="Leave blank to keep current password">
                                            @error('password')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                    
                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                                value="{{ old('email', $user->email ?? '') }}" required>
                                            @error('email')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                    
                                        {{-- Full Name --}}
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name"
                                                value="{{ old('full_name', $user->full_name ?? '') }}" required>
                                            @error('full_name')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                    
                                        {{-- Phone --}}
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                                value="{{ old('phone', $user->phone ?? '') }}" required>
                                            @error('phone')
                                                <span class="form-valid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                    
                                        {{-- Picture --}}
                                        <div class="mb-3">
                                            <label class="form-label">Picture</label>
                                            <div class="upload-zone d-flex justify-content-center align-items-center position-relative border rounded p-3">
                                                <!-- File Input for Uploading New Picture -->
                                                <input type="file" name="picture"
                                                    accept="image/png, image/jpg, image/jpeg"
                                                    onchange="loadFile(event)" class="upload-zone-input"
                                                    id="input-image" {{ isset($user->id) ? '' : 'required' }} />
                                        
                                                <!-- Hidden Input to Store Existing Picture URL (Only in Edit) -->
                                                @if(isset($user->picture))
                                                    <input type="hidden" id="existing_picture" name="existing_picture"
                                                        value="{{ old('existing_picture', $user->picture) }}" />
                                                @endif
                                        
                                                <!-- Image Preview -->
                                                <img id="image-output"
                                                    src="{{ isset($user->picture) ? $user->picture : '' }}"
                                                    alt="Image preview"
                                                    class="img-thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: contain; display: {{ isset($user->picture) ? 'block' : 'none' }}" />

                                                <!-- Upload Zone Content -->
                                                <div class="upload-zone-content text-center">
                                                    <i class="fas fa-upload fa-2x mb-2 text-muted"></i>
                                                    <div class="upload-zone-title text-muted">Select file</div>
                                                    <div class="upload-zone-desc text-muted">Click to browse your machine</div>
                                                </div>
                                            </div>
                                        
                                            {{-- Error Messages --}}
                                            @error('picture')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('existing_picture')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                    
                                        <!-- Vai trò người dùng -->
                                        <div class="mb-4">
                                            <label class="form-label" for="role">Role</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="role" id="role_admin" value="1" {{ old('role', $user->role ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_admin">
                                                    Admin
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="role" id="role_user" value="0" {{ old('role', $user->role ?? 0) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_user">
                                                    User
                                                </label>
                                            </div>
                                            @error('role')
                                                <span class="form-valid-feedback">{{ $message }}</span>
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
                @if(isset($user->picture))
                    $('#image-output').show();
                    $('.upload-zone-content').hide();
                @endif
            });

            
        </script>
    </body>

</html>
