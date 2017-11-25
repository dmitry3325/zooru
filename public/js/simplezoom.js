/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 16);
/******/ })
/************************************************************************/
/******/ ({

/***/ 16:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(17);


/***/ }),

/***/ 17:
/***/ (function(module, exports) {

//листалка фоток
(function () {

    function init() {
        W = window.innerWidth;
        H = window.innerHeight;

        var img = document.getElementsByClassName('photo-thumbnail');
        var mainImg = document.getElementById('photo-main');

        function changeImage() {
            if (this.src !== null) {
                for (var i = 0; i < img.length; i++) {
                    img[i].parentElement.classList.remove("active");
                }
                this.parentElement.className += " active";

                var src = this.src.replace("/thumb/", "/medium/");

                mainImg.src = src;
            }
        }

        for (var i = 0; i < img.length; i++) {
            img[i].addEventListener('click', changeImage, false);
        }
    }

    init();
})();

//кликалка по ценам
var prices = document.getElementsByClassName('price-block');
for (var i = 0; i < prices.length; i++) {
    prices[i].addEventListener('click', function () {
        event.preventDefault();

        if (this.classList.contains('disabled')) {
            return;
        }

        var old = this.parentElement.getElementsByClassName('price-block');

        for (var _i = 0; _i < old.length; _i++) {
            old[_i].classList.remove("active");
        }

        var buyBtn = this.parentElement.parentElement.getElementsByClassName('quantity-block__hiddent_product')[0];
        buyBtn.value = this.getAttribute('data-product');

        this.className += " active";
    });
}

//подгонялка высоты для окошек товаров
window.onload = function () {
    var maxHeight = 0;
    var prices = document.querySelectorAll('.product-window .prices');

    for (var _i2 = 0, len = prices.length; _i2 < len; _i2++) {
        var elHeight = parseFloat(window.getComputedStyle(prices[_i2]).height.slice(0, -2));
        maxHeight = elHeight > maxHeight ? elHeight : maxHeight;
    }

    for (var _i3 = 0, _len = prices.length; _i3 < _len; _i3++) {
        prices[_i3].style.height = maxHeight + 'px';
    }
};

/***/ })

/******/ });