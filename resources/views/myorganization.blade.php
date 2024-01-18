@extends('layouts.app')

@section('content')
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
    <div class="col-3">
      <h4 class="ps-2 pt-3">Organization User Management ({{ Auth::user()->organizations->name }})</h4>
      <hr>
      <button class="btn btn-primary d-block" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="fa-solid fa-plus"></i>
        New User
      </button>
    </div>
    <div class="col-9">
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>Name<br /> / Organization</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse ($orgUsers as $user)
              <tr>
                <td>
                  <strong>{{ $user->name }}</strong><br />
                  {{ $user->organizations->name }}
                  @if($user->deleted_at)
                  <span class="badge bg-danger">Disabled</span>
                  @endif
                </td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>
                  @php
                  $roleNames = $user->getRoleNames();
                  @endphp
                  @foreach ($roleNames as $role)
                  @switch($role)
                  @case('admin')
                  <span class="badge bg-info">{{ $role }}</span>
                  @break
                  @default
                  <span class="badge bg-primary">{{ $role }}</span>
                  @endswitch
                  @endforeach
                </td>
                <td>
                  @if(!$user->deleted_at)
                  <a href="{{ route('myorg.users.edit', ['user' => $user]) }}" class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-pencil"></i>
                  </a>

                  <form action="{{ route('users.destroy', ['user' => $user]) }}" method="POST" onsubmit="event.preventDefault(); deletionForm(this)" class="d-inline deletionForm">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger ms-1" data-bs-toggle="tooltip" data-bs-title="Delete category"><i class="fa-solid fa-trash"></i></button>
                  </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4">No users</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $orgUsers->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create user</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('myorg.users.store') }}" method="post" autocomplete="off">
          @csrf
          <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="name" name="name">
            </div>
          </div>

          <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="email" class="form-control" id="email" name="email">
            </div>
          </div>

          <div class="row mb-3">
            <label for="password" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
              <input type="password" class="form-control" id="password" name="password">
            </div>
          </div>

          <div class="row mb-3">
            <label for="password_confirmation" class="col-sm-3 col-form-label">Password Confirmation</label>
            <div class="col-sm-9">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
          </div>

          <div class="row mb-3">
            <label for="role" class="col-sm-3 col-form-label">Role</label>
            <div class="col-sm-9">
              <select class="form-select" id="role" name="role">
                <option value="default">Default User</option>
                <option value="orgadmin">Organization Administrator</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const deletionForm = function(formElement) {
    if (!confirm('Are you sure you want to delete this user?')) {
      return false;
    }
    return formElement.submit();
  }

</script>
@endsection
