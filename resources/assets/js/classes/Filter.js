import CartButton from '../components/CartButton.vue'

export default class Filter {
    constructor() {
        this.filterList = {};
        this.page = 1;
        this.sectionId = document.getElementById('filter-menu').getAttribute('data-section-id');
        this.goodsList = document.getElementsByClassName('goods-list')[0];

        this.updateCartButtons();
        this.goodsHeightEqual();
        this.updatePerPageButtons();
    }

    toggleParam(key, val, type = null) {
        if(!this.filterList[key]){
            this.filterList[key] = type ? {} : [];
        }

        if(type){
            this.filterList[key][type] = val;
            return;
        }

        let index = this.filterList[key].indexOf(val);

        if(index === -1) {
            this.filterList[key].push(val);
        } else {
            this.filterList[key].splice(index, 1);
        }
    }

    changeGoodsOpacity($percent = 1){
        this.goodsList.style.opacity = $percent;
    }

    loadData() {
        if(typeof this.cancelRequest === 'function') {
            this.cancelRequest('Hello (:');
        }

        this.changeGoodsOpacity('0.5');

        let self = this;

        Axios.post('/ajax/section', {
            requestId: 'filters',
            method: 'loadData',
            filter: self.filterList,
            sectionId: self.sectionId,
            perPage: self.getPerPageValue()
        }, {
            cancelToken: new Axios.CancelToken(function executor(c) {
                self.cancelRequest = c;
            })
        })
            .then(function (response) {
                if (response.data.filters_schema) {
                    self.updateFilterMenu(response.data.filters_schema);

                    self.goodsList.innerHTML = response.data.goods ? response.data.goods : '<div class="col-12 center">Товары не найдены</div>';

                    self.updateCartButtons();
                    self.goodsHeightEqual();
                    self.updatePerPageButtons();
                }

                self.changeGoodsOpacity();
            })
            .catch(function (error) {
                self.goodsList.innerHTML = '<div class="col-12 center">При загрузке произошла ошибка, пожалуйста перезагрузите страницу</div>';
                self.changeGoodsOpacity();
                console.log(error);
            });
    }

    updateFilterMenu(filters_schema) {
        for (let filterNum in filters_schema) {
            // let mainCode = filters_schema[filterNum].code;

            for (let filterCode in filters_schema[filterNum].list){
                let filter = filters_schema[filterNum].list[filterCode];
                let el = document.querySelector('[data-filter-value="' + filter.code + '"]');

                if(filter.checked){
                    el.querySelector('input').checked = true;
                } else {
                    el.querySelector('input').checked = false;
                }

                if(filter.disabled === true){
                    el.parentNode.classList.add('disabled');
                } else {
                    el.parentNode.classList.remove('disabled');
                }

                el.parentNode.href = filter.url ? filter.url : '#';

                //количество
                el.getElementsByClassName('count')[0].innerText = '(' + filter.goods_count + ')';
            }

        }
    }

    //кликалка по ценам делает их активными
    updateCartButtons() {

        let cartButtons = document.querySelectorAll('cartbutton');
        for(let i = 0; i < cartButtons.length; i++){
            let cartBtn = Vue.extend(CartButton);
            // new cartBtn({store: store, parent: }).$mount(cartButtons[0], store);
            new cartBtn({store: Store}).$mount(cartButtons[i]);
        }

        function priceClicks(){
            function onTabClick(event){
                event.preventDefault();

                if(this.classList.contains('disabled')) {
                    return;
                }

                let old = this.parentElement.getElementsByClassName('with-active');

                for (let i = 0; i < old.length; i++) {
                    old[i].classList.remove("active");
                }

                this.className += " active";

                let buyBtn = this.parentElement.parentElement.getElementsByClassName('quantity-block__hiddent_product')[0];
                if(!buyBtn){
                    return
                }
                buyBtn.value = this.getAttribute('data-product');
            }

            let prices = document.getElementsByClassName('with-active');
            for (let i = 0; i < prices.length; i++) {
                prices[i].addEventListener('click', onTabClick, false);
            }
        }

        priceClicks();
    }

    //подгонялка высоты для окошек товаров
    goodsHeightEqual() {
        let maxHeight = 0;

        //цены
        let prices = document.querySelectorAll('.product-window .prices');
        for (let i = 0, len = prices.length; i < len; i++) {
            let elHeight = parseFloat(window.getComputedStyle(prices[i]).height.slice(0, -2));
            maxHeight = elHeight > maxHeight ? elHeight : maxHeight;
        }

        for (let i = 0, len = prices.length; i < len; i++) {
            prices[i].style.height = maxHeight + 'px';
        }

        //названия
        maxHeight = 0;
        let titles = document.querySelectorAll('.product-window .product-title');
        for (let i = 0, len = titles.length; i < len; i++) {
            let elHeight = parseFloat(window.getComputedStyle(titles[i]).height.slice(0, -2));
            maxHeight = elHeight > maxHeight ? elHeight : maxHeight;
        }

        for (let i = 0, len = titles.length; i < len; i++) {
            titles[i].style.height = maxHeight + 'px';
        }
    }

    getPerPageValue(){
        return document.querySelector('.itemsPerPage .active').innerText;
    }

    updatePerPageButtons(){
        let self = this;
        //клик по кол-ву отображаемых на странице
        let perPage = document.querySelectorAll('.itemsPerPage .with-active');
        for(let i = 0; i < perPage.length; i++){
            perPage[i].addEventListener('click', function () {
                self.loadData();
            });
        }
    }
}
