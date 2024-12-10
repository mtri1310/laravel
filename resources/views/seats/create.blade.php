@extends('admin')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Seat
                </div>
                <div class="float-end">
                    <a href="{{ route('seats.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('seats.store') }}" method="post">
                    @csrf

                    <!-- Seat Number -->
                    <div class="mb-3 row">
                        <label for="seat_number" class="col-md-4 col-form-label text-md-end text-start">Seat Number</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('seat_number') is-invalid @enderror" id="seat_number" name="seat_number" value="{{ old('seat_number') }}">
                            @if ($errors->has('seat_number'))
                                <span class="text-danger">{{ $errors->first('seat_number') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Seat Status (Available or Occupied) -->
                    <div class="mb-3 row">
                        <label for="seat_status" class="col-md-4 col-form-label text-md-end text-start">Seat Status</label>
                        <div class="col-md-6">
                            <select class="form-control @error('seat_status') is-invalid @enderror" id="seat_status" name="seat_status">
                                <option value="0" {{ old('seat_status') == '0' ? 'selected' : '' }}>Available</option>
                                <option value="1" {{ old('seat_status') == '1' ? 'selected' : '' }}>Occupied</option>
                            </select>
                            @if ($errors->has('seat_status'))
                                <span class="text-danger">{{ $errors->first('seat_status') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Seat">
                    </div>

                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection
