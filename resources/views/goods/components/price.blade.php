<div class="prices">
    @foreach($good->getPriceBlock() as $g)
        @if($good->id !== $g->id)<a href="{{ URL::to('/goods/' . $g->id) }}">@endif
            <div class="price-block row border-radius-4 @if($good->id === $g->id) active z-depth-1-half @endif"
                 data-id="{{$good->id}}">
                <div class="col-3 weight">{{ $g->weight }}кг</div>
                <div class="col-4 count">
                    @if($g->notForSale())
                        нет на складе
                        <hr/>
                    @else
                        &nbsp;
                        <hr/>
                    @endif
                </div>
                <div class="col-5 price">{{ $g->getFormatedPrice() }}</div>
            </div>
            @if($good->id !== $g->id)</a>@endif
    @endforeach
</div>
