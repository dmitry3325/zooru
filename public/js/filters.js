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
/******/ 	return __webpack_require__(__webpack_require__.s = 60);
/******/ })
/************************************************************************/
/******/ ({

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(61);


/***/ }),

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Filter = function () {
    function Filter() {
        _classCallCheck(this, Filter);

        this.filterList = {};
    }

    _createClass(Filter, [{
        key: 'addParam',
        value: function addParam(key, val) {
            if (!this.filterList[key]) {
                this.filterList[key] = [];
            }

            if (this.filterList[key].indexOf(val) === -1) {
                this.filterList[key].push(val);
            }
        }
    }, {
        key: 'loadData',
        value: function loadData() {
            var goodsEl = document.getElementsByClassName('goods-list')[0];

            var url = 'korma_suhie_dlya_sobak';

            var self = this;

            Axios.post('/ajax/section', {
                filter: this.filterList,
                method: 'loadData',
                url: url
            }).then(function (response) {
                if (response.data.goods && response.data.filters_schema) {
                    self.updateFilterMenu(response.data.filters_schema);

                    goodsEl.innerHTML = response.data.goods;
                    window.updateCartButtons();
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    }, {
        key: 'updateFilterMenu',
        value: function updateFilterMenu(filters_schema) {
            // document.getElementById('filter-menu')
            for (var filterNum in filters_schema) {
                // let mainCode = filters_schema[filterNum].code;

                for (var filterCode in filters_schema[filterNum].list) {
                    var filter = filters_schema[filterNum].list[filterCode];
                    var el = document.querySelector('[data-filter-value="' + filter.code + '"]');

                    if (filter.checked) {
                        el.querySelector('input').checked = true;
                    }

                    if (filter.disabled === 'true') {
                        el.parentNode.classList += ' disabled';
                    }

                    el.parentNode.href = filter.url;

                    // console.log(el.getElementsByClassName('count')[0].innerText, filter.goods_count);
                    el.getElementsByClassName('count')[0].innerText = '(' + filter.goods_count + ')';
                }
            }
        }
    }]);

    return Filter;
}();

//filter ajax


(function () {

    var filter = new Filter('df');

    document.getElementById('filter-menu').onclick = function (elem) {
        elem.preventDefault();

        var filterLink = elem.target.classList.contains('filter-link') ? elem.target : elem.target.parentNode.classList.contains('filter-link') ? elem.target.parentNode : null;

        if (!filterLink) {
            return;
        }

        var dataFilterKey = filterLink.getAttribute('data-filter-key');
        var dataFilterVal = filterLink.getAttribute('data-filter-value');

        filter.addParam(dataFilterKey, dataFilterVal);
        filter.loadData();
    };
})();

//filter slideUp slideDown
(function () {
    var block = void 0,
        i = void 0,
        j = void 0,
        len = void 0,
        len1 = void 0,
        ref = void 0,
        ref1 = void 0,
        slideToggler = void 0,
        trigger = void 0,
        bind = function bind(fn, me) {
        return function () {
            return fn.apply(me, arguments);
        };
    },
        indexOf = [].indexOf || function (item) {
        for (var _i = 0, l = this.length; _i < l; _i++) {
            if (_i in this && this[_i] === item) return _i;
        }
        return -1;
    };

    slideToggler = function () {
        function slideToggler(el1) {
            this.el = el1;
            this.toggle = bind(this.toggle, this);
            this.getHeight = bind(this.getHeight, this);
            if (!this.el) {
                return;
            }
            window.addEventListener('resize', this.getHeight);
        }

        slideToggler.prototype.getHeight = function () {
            var clone = void 0;
            clone = this.el.cloneNode(true);
            clone.style.cssText = 'visibility: hidden; display: block; margin: -999px 0';
            this.height = this.el.parentNode.appendChild(clone).clientHeight;
            this.el.parentNode.removeChild(clone);
            return this.height;
        };

        slideToggler.prototype.toggle = function (time) {
            var currHeight = void 0,
                disp = void 0,
                el = void 0,
                end = void 0,
                init = void 0,
                ref = void 0,
                _repeat = void 0,
                start = void 0;
            this.getHeight();

            //иконки меняем
            var icons = this.el.parentNode.getElementsByClassName('material-icons');
            toggleClass(icons[0], 'hidden');
            toggleClass(icons[1], 'hidden');

            time || (time = this.height / 3 + 150);
            currHeight = this.el.clientHeight * (getComputedStyle(this.el).display !== 'none');
            ref = currHeight > this.height / 2 ? [this.height, 0] : [0, this.height], start = ref[0], end = ref[1];
            disp = end - start;
            el = this.el;
            this.el.classList[end === 0 ? 'remove' : 'add']('open');
            this.el.style.cssText = "overflow: hidden; display: block;";
            init = new Date().getTime();
            _repeat = function repeat() {
                var i = void 0,
                    instance = void 0,
                    ref1 = void 0,
                    repeatLoop = void 0,
                    results = void 0,
                    step = void 0;
                instance = new Date().getTime() - init;
                step = start + disp * instance / time;
                if (instance <= time) {
                    el.style.height = step + 'px';
                } else {
                    el.style.cssText = "display: " + (end === 0 ? 'none' : 'block');
                }
                repeatLoop = requestAnimationFrame(_repeat);
                if (ref1 = Math.floor(step), indexOf.call(function () {
                    results = [];
                    for (var _i2 = start; start <= end ? _i2 <= end : _i2 >= end; start <= end ? _i2++ : _i2--) {
                        results.push(_i2);
                    }
                    return results;
                }.apply(this), ref1) < 0) {
                    return cancelAnimationFrame(repeatLoop);
                }
            };
            return _repeat();
        };

        return slideToggler;
    }();

    ref = document.querySelectorAll('.filter-body');
    for (i = 0, len = ref.length; i < len; i++) {
        block = ref[i];
        block.toggler = new slideToggler(block);
    }

    ref1 = document.querySelectorAll('.filter-title');
    for (j = 0, len1 = ref1.length; j < len1; j++) {
        trigger = ref1[j];
        trigger.addEventListener('click', function () {
            var ref2 = void 0;
            return (ref2 = this.parentNode.querySelector('.filter-body').toggler) != null ? ref2.toggle() : void 0;
        });
    }

    function toggleClass(el, _class) {
        if (el && el.className && el.className.indexOf(_class) >= 0) {
            var pattern = new RegExp('\\s*' + _class + '\\s*');
            el.className = el.className.replace(pattern, ' ');
        } else if (el) {
            el.className = el.className + ' ' + _class;
        } else {
            console.log("Element not found");
        }
    }
})();

/***/ })

/******/ });