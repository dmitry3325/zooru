'use strict';

class Filter {
    constructor() {
        this.filterList = {};
    }

    addParam(key, val) {
        if(!this.filterList[key]){
            this.filterList[key] = [];
        }

        let index = this.filterList[key].indexOf(val);

        if(index === -1) {
            this.filterList[key].push(val);
        } else {
            this.filterList[key].splice(index, 1);
        }

        console.log(this.filterList);
    }

    loadData() {
        if(typeof this.cancelRequest === 'function') {
            this.cancelRequest('Hello (:');
        }

        let goodsEl = document.getElementsByClassName('goods-list')[0];

        let url = 'korma_suhie_dlya_sobak';

        let self = this;

        Axios.post('/ajax/section', {
            requestId: 'filters',
            filter: this.filterList,
            method: 'loadData',
            url: url
        }, {
            cancelToken: new Axios.CancelToken(function executor(c) {
                self.cancelRequest = c;
            })
        })
            .then(function (response) {
                if (response.data.goods && response.data.filters_schema) {
                    self.updateFilterMenu(response.data.filters_schema);

                    goodsEl.innerHTML = response.data.goods;
                    window.updateCartButtons();
                }
            })
            .catch(function (error) {
                console.log(error);
            });


    }

    updateFilterMenu(filters_schema) {
        // document.getElementById('filter-menu')
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
                    // el.parentNode.classList += ' disabled';
                    el.parentNode.classList.add('disabled');
                } else {
                    // el.parentNode.className.remove('disabled');
                }

                el.parentNode.href = filter.url;

                    // console.log(el.getElementsByClassName('count')[0].innerText, filter.goods_count);
                el.getElementsByClassName('count')[0].innerText = '(' + filter.goods_count + ')';
            }

        }
    }

}

//filter ajax
(function () {

    let filter = new Filter('df');

    document.getElementById('filter-menu').onclick = function (elem) {
        elem.preventDefault();

        let filterLink = elem.target.classList.contains('filter-link') ? elem.target : elem.target.parentNode.classList.contains('filter-link') ? elem.target.parentNode : null;

        if (!filterLink || filterLink.parentNode.classList.contains('disabled')) {
            return;
        }

        let dataFilterKey = filterLink.getAttribute('data-filter-key');
        let dataFilterVal = filterLink.getAttribute('data-filter-value');

        filter.addParam(dataFilterKey, dataFilterVal);
        filter.loadData();
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
