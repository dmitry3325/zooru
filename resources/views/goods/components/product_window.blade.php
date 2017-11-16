<div class="product-window border-radius-4 pb1">
        @include('goods.components.photo', ['minimize' => true])
    <div clxass="col-12">
        <h4>{{ $good->getH1Title() }}</h4>
        @include('goods.components.price')
        <cartbutton product="{{ $good->getCartArray() }}"></cartbutton>
    </div>
</div>