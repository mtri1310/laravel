@extends('layout')

@section('content')

<div class="row justify-content-center mt-3">
    <div class="col-md-12">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">Film List</div>
            <div class="card-body">
                <a href="{{ route('films.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Film</a>
                <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">S#</th>
                        <th scope="col">Film Name</th>
                        <th scope="col">Thumbnail</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Review</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($films as $film)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $film->film_name }}</td>
                            <td>
                                @if ($film->thumbnail)
                                    <img src="{{ asset('storage/' . $film->thumbnail) }}" alt="{{ $film->film_name }}" width="100">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td>{{ $film->duration }}</td>
                            <td>{{ $film->review ?? 'N/A' }}</td>
                            <td>{{ $film->movie_genre }}</td>
                            <td>
                                <form action="{{ route('films.destroy', $film->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('films.show', $film->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                                    <a href="{{ route('films.edit', $film->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>   

                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this film?');"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <span class="text-danger">
                                        <strong>No Films Found!</strong>
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                  </table>

                  {{ $films->links() }} <!-- Pagination for films -->

            </div>
        </div>
    </div>    
</div>

@endsection
