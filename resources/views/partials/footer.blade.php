<footer>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <a id="logo" href="/"></a>
                <div class="city"><i class="material-icons">&#xE0C8;</i>Москва</div>
                <div class="phone font-heavy">
                    <a href="tel:{{ Config::get('info.clear_phone') }}">
                        {{ Config::get('info.phone') }}
                    </a>
                </div>

                <div class="email font-heavy">
                    <a href="email:{{ Config::get('info.email') }}">
                        {{ Config::get('info.email') }}
                    </a>
                </div>

            </div>
            <div class="col-3">
                <h4>Сервис и помощь</h4>
                <ul>
                    <li><a href="#">Доставка</a></li>
                    <li><a href="#">Скидки</a></li>
                    <li><a href="#">Как покупать</a></li>
                    <li><a href="#">Публичная оферта</a></li>
                    <li><a href="#">Питомникам</a></li>
                    <li><a href="#">Юридическим лицам</a></li>
                </ul>
            </div>
            <div class="col-3">
                <h4>О компании</h4>
                <ul>
                    <li><a href="#">Контакты</a></li>
                    <li><a href="#">Преимущества</a></li>
                    <li><a href="#">Сертефикаты</a></li>
                    <li><a href="#">Реквизиты</a></li>
                    <li><a href="#">Сотрудничество</a></li>
                    <li><a href="#">Работа у нас</a></li>
                </ul>
            </div>
            <div class="col-3">
                <p>соц сети</p>
                <p>черновой вариант футера</p>
            </div>
        </div>
    </div>
</footer>