@extends('main')

@section('content')
    <div class="container">
        @include('partials.breadcrumb')

        <div class="row">
            <div class="col-5">
                @include('goods.components.photo')
            </div>
            <div class="col-3">
                <h1>{{ $good->h1_title }}</h1>
                <div class="articul">Артикул: {{ $good->articul }}</div>
                @include('goods.components.price')
                @include('goods.components.buyButton')
            </div>
            <div class="col-4">
                @include('goods.components.utp')
            </div>
        </div>


        <div class="row">
            <div class="order-description">
                <ul>
                    <li class="active"><a href="#">Описание</a></li>
                    <li><a href="#">Состав / характеристики</a></li>
                    <li><a href="#">Отзывы</a></li>
                </ul>
                <div class="order-description-text">{{ $good->short_description }}</div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="/js/simplezoom.js"></script>
@endpush