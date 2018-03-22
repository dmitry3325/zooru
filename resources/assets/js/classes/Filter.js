export default class Filter {
    constructor() {
        this.filterList = {};
        this.sectionId = document.getElementById('filter-menu').getAttribute('data-section-id');
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

    loadData() {
        console.log(this.filterList);
        if(typeof this.cancelRequest === 'function') {
            this.cancelRequest('Hello (:');
        }

        let goodsEl = document.getElementsByClassName('goods-list')[0];

        let self = this;

        Axios.post('/ajax/section', {
            requestId: 'filters',
            method: 'loadData',
            filter: self.filterList,
            sectionId: self.sectionId
        }, {
            cancelToken: new Axios.CancelToken(function executor(c) {
                self.cancelRequest = c;
            })
        })
            .then(function (response) {
                if (response.data.goods && response.data.filters_schema) {
                    self.updateFilterMenu(response.data.filters_schema);

                    goodsEl.innerHTML = response.data.goods;
                    updateCartButtons();
                    goodsHeightEqual();
                }
            })
            .catch(function (error) {
                // console.log(error);
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

}
