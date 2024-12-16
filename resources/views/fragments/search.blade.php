<!DOCTYPE html>
<html lang="en">
<header>
    <div>
        <form action="{{ route('films.index') }}" method="GET" class="form-inline m-3">
            <div class="input-group search-group">
                <input type="text" class="form-control search-group-input" placeholder="Search films..." value="{{ request()->get('keyword') }}" name="keyword">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
                @if(request()->has('keyword') && request()->get('keyword') != '')
                    <a href="{{ route('films.index') }}" class="btn btn-secondary">
                        <i class="fas fa-eraser"></i>
                    </a>
                @endif
            </div>
        </form>
        
    </div>
</header>