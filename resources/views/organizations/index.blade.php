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
      <h4 class="ps-2 pt-3">Organization Management</h4>
      <hr>
      <button class="btn btn-primary d-block" data-bs-toggle="modal" data-bs-target="#createOrganizationModal" id="newOrganizationButton">
        <i class="fa-solid fa-plus"></i>
        New Organization
      </button>
    </div>
    <div class="col-9">
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Default Sender</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse ($organizations as $organization)
              @livewire('organization-row', ['organization' => $organization])
              @empty
              <tr>
                <td colspan="3">No organizations</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $organizations->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="createOrganizationModal" tabindex="-1" aria-labelledby="createOrganizationModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Organization</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('organizations.store') }}" method="post" autocomplete="off">
          @csrf
          <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="name" name="name">
            </div>
          </div>

          <div class="row mb-3">
            <label for="default_sender" class="col-sm-3 col-form-label">Sender Display Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="default_sender" name="default_sender">
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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
@if (request('action') == 'new')
<script>
  window.onload = function() {
    var modalButton = document.getElementById("newOrganizationButton")
    modalButton.click()
  }

</script>
@endif
<script>
  const deletionForm = function(formElement) {
    if (!confirm('Are you sure you want to delete this organization?')) {
      return false;
    }
    return formElement.submit();
  }

</script>
@endsection
