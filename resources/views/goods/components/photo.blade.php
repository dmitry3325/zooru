<div class="photo-block row">
    @if(isset($noThumb))
        @if($good->discount)
            <div class="sale-cover">
                <div class="sale"></div>
                <div class="sale-text">{{ $good->discount }}%</div>
            </div>
        @endif

        <img class="photo-main" id="photo-main" src="{{ $good->getFirstPhoto('medium') }}">
    @else
        <div class="thumb-block col-2" id="thumb-block">
            <ul>
                <li class="active"><img class="photo-thumbnail" src="{{ $good->getFirstPhoto('thumb') }}"></li>
                @foreach($good->getSizedPhotos('thumb') as $url)
                    <li><img class="photo-thumbnail" src="{{ $url }}"></li>
                @endforeach
            </ul>
        </div>
        <div class="col-10">

            @if($good->discount)
                <div class="sale-cover">
                    <div class="sale"></div>
                    <div class="sale-text">{{ $good->discount }}%</div>
                </div>
            @endif

            <img class="photo-main" id="photo-main" src="{{ $good->getFirstPhoto('medium') }}">
        </div>
    @endif
</div>