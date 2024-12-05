@extends('layout')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Film Information
                </div>
                <div class="float-end">
                    <a href="{{ route('films.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                    <div class="row">
                        <label for="film_name" class="col-md-4 col-form-label text-md-end text-start"><strong>Film Name:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->film_name }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="thumbnail" class="col-md-4 col-form-label text-md-end text-start"><strong>Thumbnail:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            @if($film->thumbnail)
                                <img src="{{ asset('storage/' . $film->thumbnail) }}" alt="Film Thumbnail" width="100">
                            @else
                                No Thumbnail
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <label for="duration" class="col-md-4 col-form-label text-md-end text-start"><strong>Duration:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->duration }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="review" class="col-md-4 col-form-label text-md-end text-start"><strong>Review:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->review ?? 'No Review' }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="story_line" class="col-md-4 col-form-label text-md-end text-start"><strong>Story Line:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->story_line ?? 'No Story Line' }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="movie_genre" class="col-md-4 col-form-label text-md-end text-start"><strong>Genre:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->movie_genre }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="censorship" class="col-md-4 col-form-label text-md-end text-start"><strong>Censorship:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->censorship ?? 'Not Specified' }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="language" class="col-md-4 col-form-label text-md-end text-start"><strong>Language:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->language }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="direction" class="col-md-4 col-form-label text-md-end text-start"><strong>Direction:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->direction }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="actor" class="col-md-4 col-form-label text-md-end text-start"><strong>Actors:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->actor }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->status ? 'Active' : 'Inactive' }}
                        </div>
                    </div>

                    <div class="row">
                        <label for="release" class="col-md-4 col-form-label text-md-end text-start"><strong>Released:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $film->release ? 'Yes' : 'No' }}
                        </div>
                    </div>

            </div>
        </div>
    </div>    
</div>
    
@endsection
