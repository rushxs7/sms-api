@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-header">{{ __('Welcome') }}</div>

                <div class="card-body">
                    <h1 class="card-title">Hello, {{ Auth::user()->name }}!</h1>
                    <p class="card-text">We are so glad you're here.</p>
                    <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-primary">View my account</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('Notifications') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Notification</th>
                            <th scope="col">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">Lorem ipsum dolor sit amet consectetur adipisicing elit.</th>
                            <td>3 minutes ago</td>
                          </tr>
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header">{{ __('My Account') }}</div>

                <div class="card-body">
                    <div class="d-flex">
                        <div>
                            <div class="mb-2">
                                <a href="/add-funds" class="btn btn-primary">Add Funds</a>
                            </div>
                            <div>
                                <a href="/withdraw-funds" class="btn btn-secondary">View Transfer History</a>
                            </div>
                        </div>
                        <div class="flex-fill">
                            <h2 class="card-title text-end">$100.00</h2>
                            <h5 class="card-subtitle text-secondary text-end">($5.90 on allocation)</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">{{ __('Recent Jobs') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Sent/Scheduled</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="d-flex justify-content-end">
                        <a href="#" class="btn btn-primary">View recent jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
