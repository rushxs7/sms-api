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
            <h4 class="ps-2 pt-3">Send Jobs</h4>
            <hr>
            <a href="{{ route('smsservice.create') }}" class="btn btn-primary d-block">New SMS</a>
        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Message</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sendJobs as $sendJob)
                            <tr>
                                <td>
                                    @if($sendJob->type == 'instant')
                                    <span class="badge rounded-pill bg-dark"><i class="fa-solid fa-bolt-lightning"></i>&nbsp;instant</span>
                                    @else
                                    <span class="badge rounded-pill bg-secondary"><i class="fa-solid fa-clock"></i>&nbsp;scheduled</span>
                                    @endif
                                    @if($sendJob->bulk)
                                    <span class="badge rounded-pill bg-primary"><i class="fa-solid fa-boxes-stacked"></i>&nbsp;bulk</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sendJob->job_finished_at)
                                    @if ($sendJob->error)
                                    <span class="badge rounded-pill bg-warning"><i class="fa-solid fa-info"></i>&nbsp;Executed with errors</span>
                                    @else
                                    <span class="badge rounded-pill bg-success"><i class="fa-solid fa-check"></i>&nbsp;finished</span>
                                    @endif
                                    @else
                                    @if (\Carbon\Carbon::parse($sendJob->scheduled_at)->greaterThan(\Carbon\Carbon::now()))
                                    <span data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="{{ \Carbon\Carbon::parse($sendJob->scheduled_at ? $sendJob->scheduled_at : $sendJob->created_at)->toDayDateTimeString() }}">

                                        <span class="badge bg-info">
                                            <i class="fa-solid fa-clock"></i>
                                            executing {{ \Carbon\Carbon::parse($sendJob->scheduled_at ? $sendJob->scheduled_at : $sendJob->created_at)->diffForHumans() }}
                                        </span>
                                    </span>
                                    @else
                                    <span data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="{{ \Carbon\Carbon::parse($sendJob->scheduled_at ? $sendJob->scheduled_at : $sendJob->created_at)->toDayDateTimeString() }}">

                                        <span class="badge bg-success">
                                            <i class="fa-solid fa-check"></i>
                                            executed {{ \Carbon\Carbon::parse($sendJob->scheduled_at ? $sendJob->scheduled_at : $sendJob->created_at)->diffForHumans() }}
                                        </span>
                                    </span>
                                    @endif
                                    @endif
                                </td>
                                <td>{{ $sendJob->message }}</td>
                                <td>
                                    <div class="d-flex align-items-stretch justify-content-end">
                                        @if (\Carbon\Carbon::parse($sendJob->scheduled_at)->greaterThan(\Carbon\Carbon::now()))
                                        <a href="{{ route('smsservice.edit', ['job' => $sendJob->id]) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        @endif
                                        @if ($sendJob->bulk)
                                        <button class="btn btn-outline-secondary btn-sm ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#messages_job{{ $sendJob->id }}" aria-expanded="false" aria-controls="messages_job{{ $sendJob->id }}">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @if($sendJob->bulk)
                            <tr>
                                <td colspan="4" class="p-0" style="border-spacing: 0px;">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <div id="messages_job{{ $sendJob->id }}" class="accordion-collapse collapse" data-bs-parent="#messages_job{{ $sendJob->id }}">
                                                <div class="accordion-body">
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
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="5">No send jobs</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $sendJobs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
