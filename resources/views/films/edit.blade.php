@extends('layout')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Film
                </div>
                <div class="float-end">
                    <a href="{{ route('films.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('films.update', $film->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 row">
                        <label for="film_name" class="col-md-4 col-form-label text-md-end text-start">Film Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('film_name') is-invalid @enderror" id="film_name" name="film_name" value="{{ old('film_name', $film->film_name) }}">
                            @if ($errors->has('film_name'))
                                <span class="text-danger">{{ $errors->first('film_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="thumbnail" class="col-md-4 col-form-label text-md-end text-start">Thumbnail</label>
                        <div class="col-md-6">
                          <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail">
                          @if ($film->thumbnail)
                            <img src="{{ asset('storage/' . $film->thumbnail) }}" alt="Film Thumbnail" class="mt-2" width="100">
                          @endif
                            @if ($errors->has('thumbnail'))
                                <span class="text-danger">{{ $errors->first('thumbnail') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="duration" class="col-md-4 col-form-label text-md-end text-start">Duration</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $film->duration) }}">
                            @if ($errors->has('duration'))
                                <span class="text-danger">{{ $errors->first('duration') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="review" class="col-md-4 col-form-label text-md-end text-start">Review</label>
                        <div class="col-md-6">
                          <input type="number" class="form-control @error('review') is-invalid @enderror" id="review" name="review" value="{{ old('review', $film->review) }}" step="0.01" min="0" max="10">
                            @if ($errors->has('review'))
                                <span class="text-danger">{{ $errors->first('review') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="story_line" class="col-md-4 col-form-label text-md-end text-start">Story Line</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('story_line') is-invalid @enderror" id="story_line" name="story_line">{{ old('story_line', $film->story_line) }}</textarea>
                            @if ($errors->has('story_line'))
                                <span class="text-danger">{{ $errors->first('story_line') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="movie_genre" class="col-md-4 col-form-label text-md-end text-start">Movie Genre</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('movie_genre') is-invalid @enderror" id="movie_genre" name="movie_genre" value="{{ old('movie_genre', $film->movie_genre) }}">
                            @if ($errors->has('movie_genre'))
                                <span class="text-danger">{{ $errors->first('movie_genre') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="censorship" class="col-md-4 col-form-label text-md-end text-start">Censorship</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('censorship') is-invalid @enderror" id="censorship" name="censorship" value="{{ old('censorship', $film->censorship) }}">
                            @if ($errors->has('censorship'))
                                <span class="text-danger">{{ $errors->first('censorship') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="language" class="col-md-4 col-form-label text-md-end text-start">Language</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('language') is-invalid @enderror" id="language" name="language" value="{{ old('language', $film->language) }}">
                            @if ($errors->has('language'))
                                <span class="text-danger">{{ $errors->first('language') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="direction" class="col-md-4 col-form-label text-md-end text-start">Direction</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('direction') is-invalid @enderror" id="direction" name="direction" value="{{ old('direction', $film->direction) }}">
                            @if ($errors->has('direction'))
                                <span class="text-danger">{{ $errors->first('direction') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="actor" class="col-md-4 col-form-label text-md-end text-start">Actor</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('actor') is-invalid @enderror" id="actor" name="actor" value="{{ old('actor', $film->actor) }}">
                            @if ($errors->has('actor'))
                                <span class="text-danger">{{ $errors->first('actor') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="status" class="col-md-4 col-form-label text-md-end text-start">Status</label>
                        <div class="col-md-6">
                            <input type="checkbox" class="form-check-input @error('status') is-invalid @enderror" id="status" name="status" {{ old('status', $film->status) ? 'checked' : '' }}>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="release" class="col-md-4 col-form-label text-md-end text-start">Release</label>
                        <div class="col-md-6">
                            <input type="checkbox" class="form-check-input @error('release') is-invalid @enderror" id="release" name="release" {{ old('release', $film->release) ? 'checked' : '' }}>
                            @if ($errors->has('release'))
                                <span class="text-danger">{{ $errors->first('release') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update Film">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>

@endsection
