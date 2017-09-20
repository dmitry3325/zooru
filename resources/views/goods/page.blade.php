@extends('main')

@section('content')
    <div class="container">
        @include('partials.breadcrumb')
        <div class="row">
            <div class="col-4">
                @include('goods.components.photo')
            </div>
            <div class="col-4">
                <h1>{{ $good->h1_title }}</h1>
                <div class="articul">{{ $good->articul }}</div>
                @include('goods.components.price')
                @include('goods.components.buyButton')
            </div>
            <div class="col-4">
                <h2>UTP</h2>
            </div>
        </div>
    </div>
@endsection