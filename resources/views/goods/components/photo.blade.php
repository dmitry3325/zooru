<div class="photo-block row">
    <div class="thumb-block col-2">
        <ul>
            <li class="active"><img class="photo-thumbnail" src="/images/food.png"></li>
            <li><img class="photo-thumbnail" src="/images/food2.png"></li>
            <li><img class="photo-thumbnail" src="/images/food3.png"></li>
        </ul>
    </div>
    <div class="col-10">

        @if($good->discount)
        <div class="sale-cover">
            <div class="sale"></div>
            <div class="sale-text">{{ $good->discount }}%</div>
        </div>
        @endif

        <img class="photo-main" id="photo-main" src="/images/food.png">
    </div>
</div>