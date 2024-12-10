@extends('admin')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Showtime
                </div>
                <div class="float-end">
                    <a href="{{ route('showtimes.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('showtimes.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="film_id" class="col-md-4 col-form-label text-md-end text-start">Film Name</label>
                        <div class="col-md-6">
                          <select class="form-control @error('film_id') is-invalid @enderror" id="film_id" name="film_id">
                              <option value="">Select Film</option>
                              @foreach ($films as $film)
                                  <option value="{{ $film->id }}" {{ old('film_id') == $film->id ? 'selected' : '' }}>{{ $film->film_name }}</option>
                              @endforeach
                          </select>
                            @if ($errors->has('film_id'))
                                <span class="text-danger">{{ $errors->first('film_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="room_id" class="col-md-4 col-form-label text-md-end text-start">Room</label>
                        <div class="col-md-6">
                          <select class="form-control @error('room_id') is-invalid @enderror" id="room_id" name="room_id">
                              <option value="">Select Room</option>
                              @foreach ($rooms as $room)
                                  <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->room_name }}</option>
                              @endforeach
                          </select>
                            @if ($errors->has('room_id'))
                                <span class="text-danger">{{ $errors->first('room_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="day" class="col-md-4 col-form-label text-md-end text-start">Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('day') is-invalid @enderror" id="day" name="day" value="{{ old('day') }}">
                            @if ($errors->has('day'))
                                <span class="text-danger">{{ $errors->first('day') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="start_time" class="col-md-4 col-form-label text-md-end text-start">Start Time</label>
                        <div class="col-md-6">
                          <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @if ($errors->has('start_time'))
                                <span class="text-danger">{{ $errors->first('start_time') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="mb-3 row">
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
                    </div> --}}

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Showtime">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection
