//листалка фоток
(function () {

    function init() {
        W = window.innerWidth;
        H = window.innerHeight;

        let img = document.getElementsByClassName('photo-thumbnail');
        let mainImg = document.getElementById('photo-main');

        function changeImage() {
            if (this.src !== null) {
                for (let i = 0; i < img.length; i++) {
                    img[i].parentElement.classList.remove("active");
                }
                this.parentElement.className += " active";

                let src = this.src.replace("/thumb/", "/medium/");

                mainImg.src = src;
            }
        }

        for (let i = 0; i < img.length; i++) {
            img[i].addEventListener('click', changeImage, false);
        }
    }

    init();

})();

//кликалка по ценам
let prices = document.getElementsByClassName('price-block');
for (let i = 0; i < prices.length; i++) {
    prices[i].addEventListener('click', function () {
        event.preventDefault();

        if(this.classList.contains('disabled')) {
            return;
        }

        let old = this.parentElement.getElementsByClassName('price-block');


        for (let i = 0; i < old.length; i++) {
            old[i].classList.remove("active");
        }

        let buyBtn = this.parentElement.parentElement.getElementsByClassName('quantity-block__hiddent_product')[0];
        buyBtn.value = this.getAttribute('data-product');

        this.className += " active";
    });
}


//подгонялка высоты для окошек товаров
window.onload = function() {
    let maxHeight = 0;
    let prices = document.querySelectorAll('.product-window .prices');

    for (let i = 0, len = prices.length; i < len; i++) {
        let elHeight = parseFloat(window.getComputedStyle(prices[i]).height.slice(0, -2));
        maxHeight = elHeight > maxHeight ? elHeight : maxHeight;
    }

    for (let i = 0, len = prices.length; i < len; i++) {
        prices[i].style.height = maxHeight + 'px';
    }
};