@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Add Credits</h1>
  <div class="row gx-5">
    <div class="col-3">
      <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#uni5payModal">
        <div class="card shadow-sm">
          <img src="{{ asset('payments/uni5pay_card.png') }}" class="card-img-top" alt="Uni5Pay Card">
          <div class="card-body">
            <h5 class="card-title">Uni5Pay</h5>

          </div>
        </div>
      </a>
    </div>
    <div class="col-3">
      <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#mopeModal">
        <div class="card shadow-sm">
          <img src="{{ asset('payments/mope_card.png') }}" class="card-img-top" alt="Mope Card">
          <div class="card-body">
            <h5 class="card-title">Mope</h5>

          </div>
        </div>
      </a>
    </div>
    <div class="col-3">
      <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#dsbModal">
        <div class="card shadow-sm">
          <img src="{{ asset('payments/dsb_card.png') }}" class="card-img-top" alt="DSB Card">
          <div class="card-body">
            <h5 class="card-title">DSB (Bank Transfer)</h5>

          </div>
        </div>
      </a>
    </div>
    <div class="col-3">
      <a href="#" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#finabankModal">
        <div class="card shadow-sm">
          <img src="{{ asset('payments/finabank_card.png') }}" class="card-img-top" alt="Finabank Card">
          <div class="card-body">
            <h5 class="card-title">Finabank (Bank Transfer)</h5>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>

<div class="modal fade" id="uni5payModal" tabindex="-1" aria-labelledby="uni5payModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="uni5payModalLabel">Add credits with Uni5Pay</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Initiate Payment</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mopeModal" tabindex="-1" aria-labelledby="mopeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="mopeModalLabel">Add credits with Mope</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Initiate Payment</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dsbModal" tabindex="-1" aria-labelledby="dsbModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="dsbModalLabel">Add credits with DSB</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Initiate Payment</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="finabankModal" tabindex="-1" aria-labelledby="finabankModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="finabankModalLabel">Add credits with Finabank Bank Transfer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Initiate Payment</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
@endsection
