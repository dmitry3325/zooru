(function () {

    function init() {
        W = window.innerWidth;
        H = window.innerHeight;

        let img = document.getElementsByClassName('photo-thumbnail');
        let mainImg = document.getElementById('photo-main');

        function changeImage() {
            if(this.src !== null) {
                let self = this;
                changeActive(self)
            }
        }

        function changeActive(element) {
            for (let i = 0; i < img.length; i++) {
                img[i].parentElement.classList.remove("active");
            }
            element.parentElement.className += " active";

            mainImg.src = element.src;
        }

        for (let i = 0; i < img.length; i++) {
            img[i].addEventListener('click', changeImage, false);
        }

    }

    init();

})();