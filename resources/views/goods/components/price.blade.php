<div class="prices">
    @foreach($good->getPriceBlock() as $g)
            <a href="{{ URL::to('/goods/' . $g->id) }}" data-product="{{ $g->getCartArray() }}" class="price-block row border-radius-4 @if($g->notForSale())disabled @elseif($good->id === $g->id) active @endif"
                 data-id="{{$g->id}}">
                <div class="col col-auto weight">{{ $g->weight }}кг</div>

                <div class="col count">
                    @if($g->withDiscount())
                        <span class="price-line-through">{{ $g->getFormatedPrice() }}</span>
                    @endif
                    @if($g->notForSale())
                        нет на складе
                    @endif
                    &nbsp;
                    <hr/>
                </div>
                <div class="col col-auto price @if($g->withDiscount() && !$g->notForSale()) red @endif">
                    {{ $g->getFormatedPrice($g->withDiscount()) }}
                </div>

            </a>
    @endforeach
</div>
