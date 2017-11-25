<div class="prices">
    @foreach($good->getPriceBlock() as $g)
            <a href="{{ URL::to('/goods/' . $g->id) }}" data-product="{{ $g->getCartArray() }}" class="price-block row border-radius-4 @if($g->notForSale())disabled @elseif($good->id === $g->id) active @endif"
                 data-id="{{$g->id}}">
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
            </a>
    @endforeach
</div>
