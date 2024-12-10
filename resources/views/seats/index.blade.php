@extends('admin')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">Seat List</div>
            <div class="card-body">
                <a href="{{ route('seats.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Seat</a>
                <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">S#</th>
                        <th scope="col">Seat Number</th>
                        <th scope="col">Seat Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($seats as $seat)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $seat->seat_number }}</td>
                            <td>{{ $seat->seat_status ? 'Occupied' : 'Available' }}</td>
                            <td>
                                <form action="{{ route('seats.destroy', $seat->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('seats.show', $seat->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                                    <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>   

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this seat?');"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <td colspan="4">
                                <span class="text-danger">
                                    <strong>No Seats Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                  </table>

                  {{ $seats->links() }}

            </div>
        </div>
    </div>    
</div>
    
@endsection
