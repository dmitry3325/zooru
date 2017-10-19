@foreach($good->getPriceBlock() as $g)
    @if($good->id !== $g->id)<a href="{{ URL::to('/goods/' . $g->id) }}">@endif
        <div class="price-block row border-radius-4 z-depth-1 @if($good->id === $g->id) active @endif">
            <div class="col-2 weight">{{ $g->weight }}кг</div>
            <div class="col-6 count">
                @if(!$g->readyToSell())
                    нет на складе
                    <hr/>
                @else
                    &nbsp;
                    <hr />
                @endif
            </div>
            <div class="col-4 price">{{ $g->getPrice() }}</div>
        </div>
    @if($good->id !== $g->id)</a>@endif
@endforeach