@extends('layout')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Seat Information
                </div>
                <div class="float-end">
                    <a href="{{ route('seats.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label for="seat_number" class="col-md-4 col-form-label text-md-end text-start"><strong>Seat Number:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $seat->seat_number }}
                    </div>
                </div>

                <div class="row">
                    <label for="seat_status" class="col-md-4 col-form-label text-md-end text-start"><strong>Seat Status:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        @if ($seat->seat_status == 0)
                            Available
                        @else
                            Occupied
                        @endif
                    </div>
                </div>
        
            </div>
        </div>
    </div>    
</div>
    
@endsection
