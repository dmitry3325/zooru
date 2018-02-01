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
/******/ 	return __webpack_require__(__webpack_require__.s = 59);
/******/ })
/************************************************************************/
/******/ ({

/***/ 59:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(60);


/***/ }),

/***/ 60:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__uiElements_rangeSlider__ = __webpack_require__(69);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__uiElements_rangeSlider___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__uiElements_rangeSlider__);
//импортируем ренж инпуты


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

/***/ }),

/***/ 69:
/***/ (function(module, exports) {

var ZBRangeSlider = function ZBRangeSlider(el) {
    var self = this;
    var startX = 0,
        x = 0;

    var slider = el;
    var touchLeft = slider.querySelector('.slider-touch-left');
    var touchRight = slider.querySelector('.slider-touch-right');
    var lineSpan = slider.querySelector('.slider-line span');

    // get some properties
    var min = parseFloat(slider.getAttribute('se-min'));
    var max = parseFloat(slider.getAttribute('se-max'));

    var step = 0.0;

    if (slider.getAttribute('se-step')) {
        step = Math.abs(parseFloat(slider.getAttribute('se-step')));
    }

    self.slider = slider;
    self.reset = function () {
        touchLeft.style.left = '0px';
        touchRight.style.left = slider.offsetWidth - touchLeft.offsetWidth + 'px';
        lineSpan.style.marginLeft = '0px';
        lineSpan.style.width = slider.offsetWidth - touchLeft.offsetWidth + 'px';
        startX = 0;
        x = 0;
        slider.setAttribute('se-min-value', min);
        slider.setAttribute('se-max-value', max);
    };

    // initial reset
    self.reset();

    // usefull values, min, max, normalize fact is the width of both touch buttons
    var normalizeFact = touchRight.offsetWidth / 2;

    var maxX = slider.offsetWidth - touchRight.offsetWidth;
    var selectedTouch = null;
    var initialValue = lineSpan.offsetWidth - normalizeFact;

    // setup touch/click events
    function onStart(event) {

        // Prevent default dragging of selected content
        event.preventDefault();
        var eventTouch = event;

        if (event.touches) {
            eventTouch = event.touches[0];
        }

        if (this === touchLeft) {
            x = touchLeft.offsetLeft;
        } else {
            x = touchRight.offsetLeft;
        }

        startX = eventTouch.pageX - x;
        selectedTouch = this;
        document.addEventListener('mousemove', onMove);
        document.addEventListener('mouseup', onStop);
        document.addEventListener('touchmove', onMove);
        document.addEventListener('touchend', onStop);
    }

    function onMove(event) {
        var eventTouch = event;

        if (event.touches) {
            eventTouch = event.touches[0];
        }

        x = eventTouch.pageX - startX;

        if (selectedTouch === touchLeft) {
            if (x > touchRight.offsetLeft - selectedTouch.offsetWidth + normalizeFact) {
                x = touchRight.offsetLeft - selectedTouch.offsetWidth + normalizeFact;
            } else if (x < 0) {
                x = 0;
            }

            selectedTouch.style.left = x + 'px';
        } else if (selectedTouch === touchRight) {
            if (x < touchLeft.offsetLeft + touchLeft.offsetWidth - normalizeFact) {
                x = touchLeft.offsetLeft + touchLeft.offsetWidth - normalizeFact;
            } else if (x > maxX) {
                x = maxX;
            }
            selectedTouch.style.left = x + 'px';
        }

        // update line span
        lineSpan.style.marginLeft = touchLeft.offsetLeft + 'px';
        lineSpan.style.width = touchRight.offsetLeft - touchLeft.offsetLeft + 'px';

        // write new value
        calculateValue();

        // call on change
        if (slider.getAttribute('on-change')) {
            var fn = new Function('min, max', slider.getAttribute('on-change'));
            fn(slider.getAttribute('se-min-value'), slider.getAttribute('se-max-value'));
        }

        if (self.onChange) {
            self.onChange(slider.getAttribute('se-min-value'), slider.getAttribute('se-max-value'));
        }
    }

    function onStop(event) {
        document.removeEventListener('mousemove', onMove);
        document.removeEventListener('mouseup', onStop);
        document.removeEventListener('touchmove', onMove);
        document.removeEventListener('touchend', onStop);

        selectedTouch = null;

        // write new value
        calculateValue();

        // call did changed
        if (slider.getAttribute('did-changed')) {
            var fn = new Function('min, max', slider.getAttribute('did-changed'));
            fn(slider.getAttribute('se-min-value'), slider.getAttribute('se-max-value'));
        }

        if (self.didChanged) {
            self.didChanged(slider.getAttribute('se-min-value'), slider.getAttribute('se-max-value'));
        }
    }

    function calculateValue() {
        var newValue = (lineSpan.offsetWidth - normalizeFact) / initialValue;
        var minValue = lineSpan.offsetLeft / initialValue;
        var maxValue = minValue + newValue;

        minValue = minValue * (max - min) + min;
        maxValue = maxValue * (max - min) + min;

        // console.log(step);
        if (step !== 0.0) {
            var multi = Math.floor(minValue / step);
            minValue = step * multi;

            multi = Math.floor(maxValue / step);
            maxValue = step * multi;
        }

        slider.setAttribute('se-min-value', minValue);
        slider.setAttribute('se-max-value', maxValue);
    }

    // link events
    touchLeft.addEventListener('mousedown', onStart);
    touchRight.addEventListener('mousedown', onStart);
    touchLeft.addEventListener('touchstart', onStart);
    touchRight.addEventListener('touchstart', onStart);
};

var sliders = document.getElementsByClassName('rangeSlider');

var _loop = function _loop(i) {
    var el = sliders[i];
    var html = '<div class="slider-touch-left">' + '                                        <span></span>' + '                                    </div>\n' + '                                    <div class="slider-touch-right">' + '                                        <span></span>' + '                                    </div>' + '                                    <div class="slider-line">' + '                                        <span></span>' + '                                    </div>';

    el.innerHTML = html;

    var nameParam = el.getAttribute('se-name');
    var parentContainer = el.parentNode;
    var htmlResult = '<input type="text" name="' + nameParam + '-slider-min" class="half-width slider-min" placeholder="От"/>' + '<input type="text" name="' + nameParam + '-slider-max" class="half-width fl_r slider-max" placeholder="До"/>';
    parentContainer.innerHTML = htmlResult + parentContainer.innerHTML;

    var newRangeSlider = new ZBRangeSlider(sliders[i]);

    newRangeSlider.onChange = function (min, max) {
        parentContainer.getElementsByClassName('slider-min')[0].value = min;
        parentContainer.getElementsByClassName('slider-max')[0].value = max;
    };

    newRangeSlider.didChanged = function (min, max) {
        parentContainer.getElementsByClassName('slider-min')[0].value = min;
        parentContainer.getElementsByClassName('slider-max')[0].value = max;
    };
};

for (var i = 0; i < sliders.length; i++) {
    _loop(i);
}

/***/ })

/******/ });