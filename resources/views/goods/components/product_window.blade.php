<div class="product-window border-radius-4 pb1">
        @include('goods.components.photo', ['minimize' => true])
    <div clxass="col-12">
        <a href="{{ URL::to('/goods/' . $good->id) }}"><h4>{{ $good->getH1Title() }}</h4></a>
        @include('goods.components.price')
        <cartbutton></cartbutton>
    </div>
</div>