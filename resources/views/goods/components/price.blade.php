<div class="price-block row border-radius-4 z-depth-1">
    <div class="col-2 weight">{{ $good->weight }}кг</div>
    <div class="col-6 count">нет на складе
        <hr/>
    </div>
    <div class="col-4 price">{{ $good->getPrice() }}</div>
</div>