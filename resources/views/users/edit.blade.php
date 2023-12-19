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
            <div class="mb-3">
              <label for="organization_id" class="form-label">Organization</label>
              <select class="form-select" id="organization_id" name="organization_id">
                <option value="0">Please select...</option>
                @foreach ($organizations as $organization)
                <option value="{{ $organization->id }}" @if($user->organization_id == $organization->id) selected @endif>{{ $organization->name }}</option>
                @endforeach
              </select>
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
          <form action="{{ route('users.updatedefaultsender', ['user' => $user]) }}" method="POST">

            @method("PUT")
            @csrf
            <div class="mb-3">
              <label for="default_sender" class="col-form-label">Sender Display Name</label>
              <input type="text" class="form-control" id="default_sender" name="default_sender" value="{{ $user->organizations->default_sender }}" placeholder="{{ env("SMPP_DEFAULT_SENDER") }}">
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
                    <form action="{{ route('myaccount.revoketoken', ['tokenId' => $token->token]) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="event.preventDefault(); copyToken(this)"><i class="fa-solid fa-copy"></i></button>
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); deletionForm(this)"><i class="fa fa-trash"></i></button>
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
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  function copyToken(element) {
    var token = $(element).parent().parent().parent().prev().text()
    // console.log(copyButton)
    navigator.clipboard.writeText(token).then(function() {
      // Success message
      Swal.fire({
        toast: true
        , showConfirmButton: false
        , position: 'top-end'
        , icon: 'success'
        , timer: 3000
        , timerProgressBar: true
        , title: 'Token copied successfully!'
      });
    }, function(err) {
      // Error message
    });
  }

  const deletionForm = function(formElement) {
    if (!confirm('Are you sure you want to delete this token?')) {
      return false;
    }
    return $(formElement).parent().parent().submit()
    // return formElement.submit();
  }

</script>
@endsection
