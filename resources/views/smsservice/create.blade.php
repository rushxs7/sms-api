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
      <h4 class="ps-2 pt-3">New Send Job</h4>
      <hr>
    </div>
    <div class="col-9">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('smsservice.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="mb-3">
              <h4>Job Type</h4>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type-instant" value="instant" checked>
                <label class="form-check-label" for="type-instant">Instant</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="type-scheduled" value="scheduled">
                <label class="form-check-label" for="type-scheduled">Scheduled</label>
              </div>
              <div id="scheduler" class="d-none">
                <label for="scheduled_at">Scheduled at</label>
                {{-- <input type="datetime" name="scheduled_at" id="scheduled_at" class="form-control"> --}}
                <div class="input-group date-time-picker">
                  <input type="datetime-local" class="form-control" name="scheduled_at" id="scheduled_at" placeholder="Select date and time">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <h4>Bulk Job</h4>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="bulk-switch" name="bulk" value="true">
                <label class="form-check-label" for="bulk-switch">Bulk</label>
              </div>
            </div>
            <div class="border rounded p-2 mb-3">

              <h4>Receiver(s)</h4>
              <div id="phoneNumbers">
                <div class="d-flex align-items-end">
                  <div class="form-group flex-grow-1">
                    <label for="phone-number">Phone number</label>
                    <input type="number" class="form-control" id="phone-number" name="recipients[]" placeholder="597xxxxxxx" required>
                  </div>
                  <div class="deleteButtons d-none">
                    <button class="btn btn-outline-danger ms-2" onclick="removeNumber(this)" type="button"><i class="fa-solid fa-trash"></i></button>
                  </div>
                </div>
              </div>
              <div id="addButton" class="input-group mt-2 d-none">
                <button class="btn btn-outline-secondary" onclick="addNewNumber()" type="button"><i class="fa-solid fa-plus"></i></button>

              </div>
            </div>
            <div class="form-group mb-3">
              <label for="message">Message</label>
              <textarea class="form-control" id="message" name="message" rows="3" maxlength="140" oninput="updateCharCount(this)"></textarea>
              <span id="char-count" class="text-secondary">0 / 140</span>
            </div>
            <div class="form-group mb-3">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  function scheduleInput(display) {
    if (display) {
      $("#scheduler").removeClass("d-none");
      $("#scheduled_at").attr("required", true)

    } else {
      $("#scheduler").addClass("d-none");
      $("#scheduled_at").attr("required", false)
    }
  }
  $("#type-instant").change(function() {
    scheduleInput(false)
  })
  $("#type-scheduled").change(function() {
    scheduleInput(true)
  })

  $("#bulk-switch").change(function() {
    if ($(this).is(':checked')) {
      showDeleteButtons()
      showAddNewNumberButton()
      determineDeletable()
    } else {
      showDeleteButtons(false)
      showAddNewNumberButton(false)
      determineDeletable()
    }
  })

  function determineDeletable() {
    console.log($("#phoneNumbers").find('input[type="number"]').length)
    if ($("#phoneNumbers").find('input[type="number"]').length > 1) {
      // Deletable
      var deleteButtons = $(".deleteButtons").find("button")
      deleteButtons.attr("disabled", false);
    } else {
      // Not deletable
      var deleteButtons = $(".deleteButtons").find("button")
      deleteButtons.attr("disabled", true);
    }
  }

  function showAddNewNumberButton(show = true) {
    if (show) {
      $("#addButton").removeClass("d-none");
    } else {
      $("#addButton").addClass("d-none");
    }
  }

  function showDeleteButtons(show = true) {
    if (show) {
      $(".deleteButtons").removeClass("d-none");
    } else {
      $(".deleteButtons").addClass("d-none");
    }
  }

  function addNewNumber() {
    $('#phoneNumbers').append(`<div class="d-flex align-items-end">
            <div class="form-group flex-grow-1">
                <label for="phone-number">Phone number</label>
                <input type="number" class="form-control" id="phone-number" name="recipients[]" placeholder="597xxxxxxx" required>
            </div>
            <div class="deleteButtons d-none">
                <button class="btn btn-outline-danger ms-2" onclick="removeNumber(this)" type="button"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>`)
    showDeleteButtons()
    determineDeletable()
  }

  function removeNumber(element) {
    $(element).parent().parent().remove()
    determineDeletable()
  }



  function updateCharCount(textarea) {
    var charCount = textarea.value.length;
    var span = document.getElementById("char-count");

    span.innerHTML = charCount + " / 140";

    if (charCount > 140) {
      textarea.value = textarea.value.substring(0, 140);
    }
  }

</script>
@endsection
