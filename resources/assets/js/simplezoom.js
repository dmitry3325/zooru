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

                mainImg.src = this.src;
            }
        }

        for (let i = 0; i < img.length; i++) {
            img[i].addEventListener('click', changeImage, false);
        }
    }

    init();

})();