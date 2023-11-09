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
  <div class="row mb-4">
    <div class="col-3">
      <h4 class="ps-2 pt-3">User Management</h4>
      <hr>
      <button class="btn btn-primary d-block">
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
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
              <tr>
                <td>#{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
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
                  <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-pencil"></i>
                  </a>
                  <button class="btn btn-sm btn-danger ms-1"><i class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5">No send jobs</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
