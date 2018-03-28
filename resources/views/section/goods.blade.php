<div class="col-12">
    <div id="goods-info">
        Найдено товаров: {{ $goods->total() }}
        <ul class="itemsPerPage fl_r pagination">
                Товаров на странице:
                <li class="with-active @if($goods->perPage() == 24) active @endif">24</li>
                <li class="with-active @if($goods->perPage() == 48) active @endif">48</li>
                <li class="with-active @if($goods->perPage() == 96) active @endif">96</li>
        </ul>
    </div>
</div>

@foreach($goods as $good)
    <div class="col-4">
        @include('goods.components.product_window', array('good' => $good))
    </div>
@endforeach

<div class="col-12 center">
    {{ $goods->links('partials.pagination') }}
</div>