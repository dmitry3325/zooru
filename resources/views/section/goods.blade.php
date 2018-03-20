@foreach($goods as $good)
    <div class="col-4">
        @include('goods.components.product_window', array('good' => $good))
    </div>
@endforeach