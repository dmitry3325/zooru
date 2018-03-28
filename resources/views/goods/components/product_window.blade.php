<div class="product-window border-radius-4 pb1">
        @include('goods.components.photo', ['minimize' => true])
    <div clxass="col-12">
        <a href="{{ URL::to('/goods/' . $good->id) }}" class="product-title">{{ $good->getH1Title() }}</a>
        @foreach($good->filters as $filter)
            <div style="font-weight: bold;">{{$filter->num /*.'->'. $filter->code*/.'->'.$filter->value}}</div>
        @endforeach
        @include('goods.components.price')
        <cartbutton></cartbutton>
    </div>
</div>