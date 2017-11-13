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

// var newEvent = new Event('buttonid.update');

// let g = document.getElementsByClassName('price-block')[0];

// g.onclick = function () {
//     this.getAttribute('data-id');
// };
