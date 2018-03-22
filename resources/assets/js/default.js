'use strict';

import * as range from './uiElements/rangeSlider';
import Filter from './classes/Filter'

window.filter = new Filter();

//листалка фоток
(function () {

    function init() {
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

//tabs
(function(){
    function onTabClick(event){
        event.preventDefault();
        let actives = document.querySelectorAll('.order-description .active');

        // deactivate existing active tab and panel
        for (let i = 0; i < actives.length; i++){
            actives[i].className = actives[i].className.replace('active', '');
        }

        // activate new tab and panel
        event.target.className += ' active';
        document.getElementById(event.target.getAttribute('data-href').split('#')[1]).className += ' active';
    }

    let el = document.getElementById('nav-tab');

    if(el){
        el.addEventListener('click', onTabClick, false);
    }
})();

//filter ajax
(function () {
    //клики по фильтрам с выбором
    document.getElementById('filter-menu').onclick = function (elem) {
        elem.preventDefault();

        let filterLink = elem.target.classList.contains('filter-link') ? elem.target : elem.target.parentNode.classList.contains('filter-link') ? elem.target.parentNode : null;

        if (!filterLink || filterLink.parentNode.classList.contains('disabled')) {
            return;
        }

        let dataFilterKey = filterLink.getAttribute('data-filter-key');
        let dataFilterVal = filterLink.getAttribute('data-filter-value');

        filter.toggleParam(dataFilterKey, dataFilterVal);
        filter.loadData();
    };

    //изменения в ренж фильтрах
    let range = document.getElementsByClassName('range-input');
    for(let i = 0; i < range.length; i++){
        range[i].addEventListener('change', function (event) {
            let elem = event.target;

            let dataFilterKey = elem.getAttribute('data-filter-key');
            let type = elem.classList.contains('slider-min') ? 'min' : 'max';

            filter.toggleParam(dataFilterKey, elem.value, type);
            filter.loadData();
        })
    }
})();


//filter slideUp slideDown
(function () {
    let block, i, j, len, len1, ref, ref1, slideToggler, trigger,
        bind = function (fn, me) {
            return function () {
                return fn.apply(me, arguments);
            };
        },
        indexOf = [].indexOf || function (item) {
            for (let i = 0, l = this.length; i < l; i++) {
                if (i in this && this[i] === item) return i;
            }
            return -1;
        };

    slideToggler = (function () {
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
            let clone;
            clone = this.el.cloneNode(true);
            clone.style.cssText = 'visibility: hidden; display: block; margin: -999px 0';
            this.height = (this.el.parentNode.appendChild(clone)).clientHeight;
            this.el.parentNode.removeChild(clone);
            return this.height;
        };

        slideToggler.prototype.toggle = function (time) {
            let currHeight, disp, el, end, init, ref, repeat, start;
            this.getHeight();

            //иконки меняем
            let icons = this.el.parentNode.getElementsByClassName('material-icons');
            toggleClass(icons[0], 'hidden');
            toggleClass(icons[1], 'hidden');

            time || (time = this.height / 3 + 150);
            currHeight = this.el.clientHeight * (getComputedStyle(this.el).display !== 'none');
            ref = currHeight > this.height / 2 ? [this.height, 0] : [0, this.height], start = ref[0], end = ref[1];
            disp = end - start;
            el = this.el;
            this.el.classList[end === 0 ? 'remove' : 'add']('open');
            this.el.style.cssText = "overflow: hidden; display: block;";
            init = (new Date).getTime();
            repeat = function () {
                let i, instance, ref1, repeatLoop, results, step;
                instance = (new Date).getTime() - init;
                step = start + disp * instance / time;
                if (instance <= time) {
                    el.style.height = step + 'px';
                } else {
                    el.style.cssText = "display: " + (end === 0 ? 'none' : 'block');
                }
                repeatLoop = requestAnimationFrame(repeat);
                if (ref1 = Math.floor(step), indexOf.call((function () {
                        results = [];
                        for (let i = start; start <= end ? i <= end : i >= end; start <= end ? i++ : i--) {
                            results.push(i);
                        }
                        return results;
                    }).apply(this), ref1) < 0) {
                    return cancelAnimationFrame(repeatLoop);
                }
            };
            return repeat();
        };

        return slideToggler;

    })();

    ref = document.querySelectorAll('.filter-body');
    for (i = 0, len = ref.length; i < len; i++) {
        block = ref[i];
        block.toggler = new slideToggler(block);
    }

    ref1 = document.querySelectorAll('.filter-title');
    for (j = 0, len1 = ref1.length; j < len1; j++) {
        trigger = ref1[j];
        trigger.addEventListener('click', function () {
            let ref2;
            return (ref2 = this.parentNode.querySelector('.filter-body').toggler) != null ? ref2.toggle() : void 0;
        });
    }

    function toggleClass(el, _class) {
        if (el && el.className && el.className.indexOf(_class) >= 0) {
            let pattern = new RegExp('\\s*' + _class + '\\s*');
            el.className = el.className.replace(pattern, ' ');
        }
        else if (el) {
            el.className = el.className + ' ' + _class;
        }
        else {
            console.log("Element not found");
        }
    }

})();
