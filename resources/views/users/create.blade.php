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
                        <form action="{{ isset($user->id) ? route('users.edit', $user->id) : route('users.store') }}" method="post" id="form-user" enctype="multipart/form-data">
                            @csrf
                            @if (isset($user->id))
                                @method('PUT')
                            @endif
                        
                            <input type="hidden" name="id" value="{{ old('id', $user->id ?? '') }}" />
                        
                            <!-- Tên đăng nhập -->
                            <div class="mb-4">
                                <label class="form-label" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username ?? '') }}" required />
                                @error('username')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <!-- Mật khẩu -->
                            <div class="mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" value="{{ old('password', $user->password ?? '') }}" required />
                                @error('password')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <!-- Email -->
                            <div class="mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required />
                                @error('email')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <!-- Họ và tên -->
                            <div class="mb-4">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name ?? '') }}" required />
                                @error('full_name')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <!-- Số điện thoại -->
                            <div class="mb-4">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}" />
                                @error('phone')
                                    <span class="form-valid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <!-- Ảnh đại diện -->
                            <div class="mb-4" style="padding: 0;">
                                <div class="upload-zone d-flex justify-content-center align-items-center">
                                    <!-- Input file ảnh -->
                                    <input type="file" name="picture"
                                        accept="image/png, image/jpg, image/jpeg"
                                        onchange="loadFile(event)" class="upload-zone-input"
                                        id="input-image" required />
                            
                                    <!-- Input ẩn để lưu tên file ảnh -->
                                    <input type="text" id="image" name="picture"
                                        value="{{ old('picture') }}" readonly style="display: none; opacity: 0" />
                            
                                    <!-- Hiển thị ảnh (nếu có) -->
                                    <img id="image-output"
                                        src="{{ old('picture') ? asset('storage/' . old('picture')) : '' }}"
                                        alt="Image preview" />
                            
                                    <div class="upload-zone-content">
                                        <div class="upload-zone-title">Select file</div>
                                        <div class="upload-zone-desc">Click browse through your machine</div>
                                    </div>
                                </div>
                            
                                @error('picture')
                                    <span class="form-valid-feedback">{{ $message }}</span>
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
