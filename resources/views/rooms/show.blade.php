@extends('admin')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Room Information
                </div>
                <div class="float-end">
                    <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label for="room_name" class="col-md-4 col-form-label text-md-end text-start"><strong>Room Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $room->room_name }}
                    </div>
                </div>

            </div>
        </div>
    </div>    
</div>

@endsection
