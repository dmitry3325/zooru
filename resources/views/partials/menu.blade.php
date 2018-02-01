<div class="header__menu z-depth-2">
    <div class="container">
        <div class="row">
            <div class="col-9">
                <ul class="header-menu">
                    @foreach($menu as $catetgory)
                        <li><a href="#">{{ $catetgory['main']['title'] }}</a>
                            @if(count($catetgory) > 1)
                                <div class="header-menu-submenu z-depth-1-half">
                                    <ul>
                                        @foreach($catetgory as $key => $subcatetgory)
                                            @if($key !== 'main')
                                                <li style="display: block;">{{ $subcatetgory['title'] }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-3 right">
                <input class="header__menu_search z-depth-1-half" type="text" name="search" placeholder="Найдется все для питомца!">
            </div>
        </div>
    </div>
</div>