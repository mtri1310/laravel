<!DOCTYPE html>
<html lang="en">
<header>
    <div>
        <form action="{{ route($entityName . '.index') }}" method="GET" class="form-inline m-3">
            <div class="input-group search-group">
                <input type="text" class="form-control search-group-input" placeholder="Search {{ ucfirst($entityName) }}..." value="{{ request()->get('keyword') }}" name="keyword">
                <div class="input-group-append search-group-append d-flex justify-content-center align-items-center">
                    <i class="fas fa-search"></i>
                </div>
                &nbsp;&nbsp;
                @if(request()->has('keyword') && request()->get('keyword') != '')
                    <a href="{{ route($entityName . '.index') }}">
                        <button type="button" class="btn" style="background-color: transparent; border: 0; box-shadow: none !important;">
                            <i class="fas fa-eraser" style="color: black"></i>
                        </button>
                    </a>
                @endif
            </div>
        </form>
    </div>
</header>
</html>
