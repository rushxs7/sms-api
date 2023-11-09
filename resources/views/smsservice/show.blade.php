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
            <h4 class="ps-2 pt-3">Send Job #{{ $sendJob->id }}</h4>
            <hr>
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex border rounded p-3 mb-3">
                        <div>
                            <p>
                                <strong>Timing</strong>: {{ $sendJob->type }}<br />
                                <strong>Job Type</strong>: {{ $sendJob->bulk ? 'Bulk' : 'Single' }}<br />
                                <strong>Scheduled for</strong>: {{ \Carbon\Carbon::parse($sendJob->scheduled_at ? $sendJob->scheduled_at : $sendJob->created_at)->format('d F Y \\a\\t h:i:s A') }} <br />
                                <strong>Error</strong>: {{ $sendJob->error ? $sendJob->error : '-' }}
                            </p>
                        </div>
                        <div class="flex-grow-1 d-flex justify-content-end">
                            <div>
                                <h3>
                                    @switch($overallStatus)
                                    @case('sent')
                                    <span class="badge bg-success">Sending</span>
                                    @break

                                    @case('sending')
                                    <span class="badge bg-info">Sending</span>
                                    @break

                                    @case('scheduled')
                                    <span class="badge bg-info">Scheduled</span>
                                    @break

                                    @case('error')
                                    <span class="badge bg-danger">Error</span>
                                    @break

                                    @default
                                    <span class="badge bg-danger">Error</span>
                                    @break
                                    @endswitch
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded p-3 mb-3">
                        <strong>
                            Message:
                        </strong>
                        <p>
                            {{ $sendJob->message }}
                        </p>
                    </div>
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <td>Recipient</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sendJob->messages as $message)
                            <tr>
                                <td>{{ $message->recipient }}</td>
                                <td>
                                    @switch($message->status)
                                    @case('scheduled')
                                    <span class="badge bg-warning">
                                        {{ $message->status }}
                                    </span>
                                    @break
                                    @case('sent')
                                    <span class="badge bg-success">
                                        {{ $message->status }}
                                    </span>
                                    @break
                                    @case('error')
                                    <span class="badge bg-error">
                                        {{ $message->status }}
                                    </span>
                                    @break

                                    @default
                                    <span class="badge bg-light">
                                        {{ $message->status }}
                                    </span>
                                    @break

                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

</script>
@endsection
