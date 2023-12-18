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
      <h4 class="ps-2 pt-3">Personal Info</h4>
      <hr>
    </div>
    <div class="col-8">
      <div class="card shadow">
        <div class="card-body">
          <form action="{{ route('myaccount.savepersonalinfo') }}" method="POST">
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
      <h4 class="ps-2 pt-3">Sender Info</h4>
      <hr>
    </div>
    <div class="col-8">
      <div class="card shadow">
        <div class="card-body">
          <form action="{{ route('myaccount.savesenderinfo') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
              <label for="default_sender" class="form-label">Default sender</label>
              <input type="text" class="form-control" id="default_sender" name="default_sender" value="{{ $user->default_sender }}" placeholder="{{ env("SMPP_DEFAULT_SENDER") }}" required>
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
          <form action="{{ route('myaccount.passwordreset') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
              <label for="current_password" class="form-label">Current password</label>
              <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter your current password">
            </div>
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
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>API Key</th>
                  <th>Token</th>
                  {{-- <th>Expiration</th> --}}
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @forelse ($tokens as $token)
                <tr>
                  <td>{{ $token->name }}</td>
                  <td>{{ $token->token }}</td>
                  {{-- <td>{{ $token->expires_at ? \Carbon\Carbon::parse($token->expires_at)->toDateTimeString() : 'No expiration.' }}</td> --}}
                  <td>
                    <form action="{{ route('myaccount.revoketoken', ['tokenId' => $token->token]) }}">
                      @csrf
                      @method('DELETE')
                      <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="copyToken(this)"><i class="fa-solid fa-copy"></i></button>
                        <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa fa-trash"></i></button>
                      </div>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td>No API tokens</td>
                  <td></td>
                  <td></td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-end">
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#newTokenModal"><i class="fa fa-plus"></i> New Token</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="newTokenModal" tabindex="-1" aria-labelledby="newTokenModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add new API token.</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('myaccount.createtoken') }}" method="post">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="name" class="form-label">API token name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Token for mobile application.">
            </div>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Token</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')

@endsection
