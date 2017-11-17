<header>
    <div class="container">
        <div class="row">
            <div class="col-2">
                <a id="logo" href="/"></a>
            </div>
            <div class="col-2">
                <div class="city"><i class="material-icons">&#xE0C8;</i>Москва</div>
                <div class="phone font-heavy"><a href="tel:{{ Config::get('info.clear_phone') }}">{{ Config::get('info.phone') }}</a></div>
            </div>
            <div class="sub-menu col-8">
                <a class="header_up_item"><i class="material-icons">&#xE8FD;</i><span>Сервис и помощь</span></a>
                <a class="header_up_item"><i class="material-icons">&#xE8A1;</i><span>Доставка и оплата</span></a>
                <a class="header_up_item"><i class="material-icons">&#xE0CD;</i><span>Контакты</span></a>
                <cart donation-percent="{{ Config::get('info.donation_percent')  }}"></cart>
                <a class="header_up_item seporator"><i class="material-icons">&#xE5D4;</i></a>
                <a class="header_up_item"><i class="material-icons">&#xE853;</i><span>Вход / Регистрация</span></a>
            </div>
        </div>
    </div>
</header>