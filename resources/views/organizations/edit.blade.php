@extends("layouts.app")

@section("content")
<div class="container">

  @if(Session::has('success'))
  <div class="row">
    <div class="col">
      <div class="alert alert-success d-flex align-items-center" role="alert">
        <i class="fa-solid fa-check"></i>&nbsp;&nbsp;&nbsp;
        <div>
          {{ Session::get('success'); }}
        </div>
      </div>
    </div>
  </div>
  @endif
  @if(Session::has('errors'))
  <div class="row">
    <div class="col">
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="fa-solid fa-times"></i>&nbsp;&nbsp;&nbsp;
        <div>
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="row mb-4">
    <div class="col-4">
      <h4 class="ps-2 pt-3">Organization Info</h4>
      <hr>
    </div>
    <div class="col-8">
      <div class="card shadow">
        <div class="card-body">
          <form action="{{ route('organizations.update', ['organization' => $organization]) }}" method="POST">
            @method("PUT")
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $organization->name }}">
            </div>
            <div class="mb-3">
              <label for="default_sender" class="form-label">Default Sender</label>
              <input type="text" class="form-control" id="default_sender" name="default_sender" value="{{ $organization->default_sender }}">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
