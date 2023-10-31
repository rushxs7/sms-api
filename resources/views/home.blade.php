@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 d-flex">
            <div class="card flex-grow-1">
                <div class="card-header">{{ __('Welcome') }}</div>

                <div class="card-body">
                    <h1 class="card-title">Hello, {{ Auth::user()->name }}!</h1>
                    <p class="card-text">We are so glad you're here.</p>
                    <a href="#" class="btn btn-primary">My account</a>
                </div>
            </div>
        </div>
        <div class="col-md-7 d-flex">
            <div class="card flex-grow-1">
                <div class="card-header">{{ __('My Account') }}</div>

                <div class="card-body">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo nulla soluta deserunt quidem itaque ex harum perferendis! Ex consequuntur animi quas dolore exercitationem impedit explicabo, sit tempore quae saepe magni id nam praesentium cum obcaecati dolores amet harum ipsum nesciunt aliquid cupiditate temporibus dolorem. Architecto voluptatibus aliquam cumque reprehenderit quisquam tempore magni mollitia voluptate nihil? Omnis voluptates facilis autem modi corporis animi perferendis, amet sunt delectus vitae aperiam non dolor error laudantium. Minus sunt neque voluptatem voluptatibus accusantium atque, numquam, aliquam ipsam eius est quae quisquam! Facilis commodi deleniti dolor nemo fugit, rerum qui animi unde sint, placeat nostrum suscipit!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
