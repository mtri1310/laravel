@extends('admin')

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
                    Edit Room
                </div>
                <div class="float-end">
                    <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('rooms.update', $room->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <!-- Room Name -->
                    <div class="mb-3 row">
                        <label for="room_name" class="col-md-4 col-form-label text-md-end text-start">Room Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('room_name') is-invalid @enderror" id="room_name" name="room_name" value="{{ old('room_name', $room->room_name) }}">
                            @if ($errors->has('room_name'))
                                <span class="text-danger">{{ $errors->first('room_name') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update Room">
                    </div>

                </form>
            </div>
        </div>
    </div>    
</div>

@endsection
