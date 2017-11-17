@extends('main')

@section('content')
    <div class="container">
        @include('partials.breadcrumb')

        <div class="row">
            <div class="col-5">
                @include('goods.components.photo')
            </div>
            <div class="col-3">
                <h1>{{ $good->getH1Title() }}</h1>
                <div class="articul">Артикул: {{ $good->articul }}</div>
                @include('goods.components.price')
                <cartbutton product="{{ $good->getCartArray() }}"></cartbutton>
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
                <div class="order-description-text">
                    @if($good->getMetaData(['big_description']))
                        {{ $good->getMetaData(['big_description']) }}
                    @else
                        {{ $good->short_description }}
                    @endif
                </div>
            </div>
        </div>

        <div class="row associated-products">
            <div class="col-12">
                <h3>С этим товаром покупают</h3>
            </div>
            @foreach($good->getAssociatedList() as $product)
                <div class="col-3">
                    @include('goods.components.product_window', array('good' => $product))
                </div>
            @endforeach
        </div>

    </div>
@endsection

@push('scripts')
    <script src="/js/simplezoom.js"></script>
@endpush