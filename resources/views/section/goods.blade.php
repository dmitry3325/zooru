<div class="col-12">
    <div id="goods-info">
        Найдено товаров: {{ $goods->total() }}
        <span class="itemsPerPage fl_r prices">
                Товаров на странице:
                <span class="with-active @if(isset($perPage) && $perPage == 24 || !isset($perPage)) active @endif">24</span>
                <span class="with-active @if(isset($perPage) && $perPage == 48) active @endif">48</span>
                <span class="with-active @if(isset($perPage) && $perPage == 96) active @endif">96</span>
        </span>
    </div>
</div>

@foreach($goods as $good)
    <div class="col-4">
        @include('goods.components.product_window', array('good' => $good))
    </div>
@endforeach

<div class="col-12 center">
    {{ $goods->links() }}
</div>