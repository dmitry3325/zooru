@extends('main')

@section('content')
    <div class="container">
        @include('partials.breadcrumb')

        <div class="row">
            <div class="col-4">
                @include('goods.components.photo')
            </div>
            <div class="col-8 row">
                <div class="col-12">
                    <h1>{{ $good->getH1Title() }}</h1>
                </div>
                <div class="col-5">
                    @if (Illuminate\Support\Facades\Auth::user() && Illuminate\Support\Facades\Auth::user()->hasRole(App\Models\Auth\UserRole::ROLE_GOD))
                        <a href="{{ env('PHOTO_SERVER') }}/shop?entity=Goods&id={{ $good->id }}"><i
                                    class="material-icons">settings</i></a>
                    @endif
                    <div class="articul">Артикул: {{ $good->articul }}</div>
                    @include('goods.components.price')
                    <cartbutton></cartbutton>
                </div>
                <div class="col-7">
                    @include('goods.components.utp')
                </div>
            </div>
        </div>

        <div class="row">
            <section class="order-description">

                <ul id="nav-tab" class="nav">
                    <li class="active" data-href="#tab1">Описание</li>
                    <li data-href="#tab2">Состав / характеристики</li>
                    <li data-href="#tab3">Отзывы</li>
                </ul>

                <div class="tab-content order-description-text">
                    <div class="tab-panel active" id="tab1">
                        @if($good->getMetaData(['big_description']))
                            {!! $good->getMetaData(['big_description']) !!}
                        @else
                            {!! $good->short_description !!}
                        @endif
                    </div>
                    <div class="tab-panel" id="tab2">
                        <h2>описание</h2>
                    </div>
                    <div class="tab-panel" id="tab3">Отзывы</div>
                </div>
            </section>
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