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
  <div class="row mb-4">
    <div class="col-4">
      <h4 class="ps-2 pt-3">Personal Info</h4>
      <hr>
    </div>
    <div class="col-8">
      <div class="card shadow">
        <div class="card-body">
          <form action="{{ route('users.update', ['user' => $user]) }}" method="POST">
            @method("PUT")
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-4">
      <h4 class="ps-2 pt-3">Password Reset</h4>
      <hr>
    </div>
    <div class="col-8">
      <div class="card shadow">
        <div class="card-body">
          <form action="{{ route('users.updatepassword', ['user' => $user]) }}" method="POST" autocomplete="off">
            @method("PUT")
            @csrf
            <div class="mb-3">
              <label for="password" class="form-label">New password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password">
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirm new password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your new password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row mb-4">
    <div class="col-4">
      <h4 class="ps-2 pt-3">API Keys</h4>
      <hr>
    </div>
    <div class="col-8">
      <div class="card shadow">
        <div class="card-body">

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
