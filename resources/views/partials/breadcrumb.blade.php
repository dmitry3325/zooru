<div class="col-12">
    <ul class="breadcrumb">
        @foreach($good->getBreadcrumbs() as $name => $link)
            @if($link)
                <li><a href="{{ $link }}">{{ $name }}</a></li>
                <li>|</li>
            @else
                <li>{{ $name }}</li>
            @endif
        @endforeach
    </ul>
</div>
