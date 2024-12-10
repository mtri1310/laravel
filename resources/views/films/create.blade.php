@extends('admin')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Film
                </div>
                <div class="float-end">
                    <a href="{{ route('films.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('films.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <!-- Film Name -->
                    <div class="mb-3 row">
                        <label for="film_name" class="col-md-4 col-form-label text-md-end text-start">Film Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('film_name') is-invalid @enderror" id="film_name" name="film_name" value="{{ old('film_name') }}">
                            @if ($errors->has('film_name'))
                                <span class="text-danger">{{ $errors->first('film_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-3 row">
                        <label for="thumbnail" class="col-md-4 col-form-label text-md-end text-start">Thumbnail</label>
                        <div class="col-md-6">
                          <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" value="{{ old('thumbnail') }}">
                            @if ($errors->has('thumbnail'))
                                <span class="text-danger">{{ $errors->first('thumbnail') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3 row">
                        <label for="duration" class="col-md-4 col-form-label text-md-end text-start">Duration</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}">
                            @if ($errors->has('duration'))
                                <span class="text-danger">{{ $errors->first('duration') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Review -->
                    <div class="mb-3 row">
                        <label for="review" class="col-md-4 col-form-label text-md-end text-start">Review</label>
                        <div class="col-md-6">
                          <input type="number" class="form-control @error('review') is-invalid @enderror" id="review" name="review" value="{{ old('review') }}" min="0" max="10">
                            @if ($errors->has('review'))
                                <span class="text-danger">{{ $errors->first('review') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Story Line -->
                    <div class="mb-3 row">
                        <label for="story_line" class="col-md-4 col-form-label text-md-end text-start">Story Line</label>
                        <div class="col-md-6">
                          <textarea class="form-control @error('story_line') is-invalid @enderror" id="story_line" name="story_line">{{ old('story_line') }}</textarea>
                            @if ($errors->has('story_line'))
                                <span class="text-danger">{{ $errors->first('story_line') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Movie Genre -->
                    <div class="mb-3 row">
                        <label for="movie_genre" class="col-md-4 col-form-label text-md-end text-start">Movie Genre</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('movie_genre') is-invalid @enderror" id="movie_genre" name="movie_genre" value="{{ old('movie_genre') }}">
                            @if ($errors->has('movie_genre'))
                                <span class="text-danger">{{ $errors->first('movie_genre') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Censorship -->
                    <div class="mb-3 row">
                        <label for="censorship" class="col-md-4 col-form-label text-md-end text-start">Censorship</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('censorship') is-invalid @enderror" id="censorship" name="censorship" value="{{ old('censorship') }}">
                            @if ($errors->has('censorship'))
                                <span class="text-danger">{{ $errors->first('censorship') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Language -->
                    <div class="mb-3 row">
                        <label for="language" class="col-md-4 col-form-label text-md-end text-start">Language</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('language') is-invalid @enderror" id="language" name="language" value="{{ old('language') }}">
                            @if ($errors->has('language'))
                                <span class="text-danger">{{ $errors->first('language') }}</span>
                            @endif
                        </div>
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
</div>
    
@endsection
