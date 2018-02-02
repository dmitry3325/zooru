<div class="prices">
    @foreach($good->getPriceBlock() as $g)
            <a href="{{ URL::to('/goods/' . $g->id) }}" data-product="{{ $g->getCartArray() }}" class="price-block row border-radius-4 @if($g->notForSale())disabled @elseif($good->id === $g->id) active @endif"
                 data-id="{{$g->id}}">
                <div class="col col-auto weight">{{ $g->weight }}кг</div>
                <div class="col count">
                    @if($g->notForSale())
                        нет на складе
                        <hr/>
                    @else
                        &nbsp;
                        <hr/>
                    @endif
                </div>
                <div class="col col-auto price">{{ $g->getFormatedPrice() }}</div>
            </a>
    @endforeach
</div>
