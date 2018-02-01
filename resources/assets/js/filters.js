(function () {

    function init() {
        document.getElementById('filter-menu').addEventListener('click', function (event) {
            let target = event.target;
            if(target.classList.contains('filter-title') || target.parentNode.classList.contains('filter-title')){
                let el = target.parentNode.getElementsByClassName('filter-body')[0] ? target.parentNode.getElementsByClassName('filter-body')[0] : target.parentNode.parentNode.getElementsByClassName('filter-body')[0];
                toggleClass(el, 'hidden');

                //меняем кнопки
                let icons = el.parentNode.getElementsByClassName('material-icons');
                toggleClass(icons[0], 'hidden');
                toggleClass(icons[1], 'hidden');
            }

        }, false);
    }

    init();

})();

function toggleClass(el, _class) {
    if (el && el.className && el.className.indexOf(_class) >= 0) {
        var pattern = new RegExp('\\s*' + _class + '\\s*');
        el.className = el.className.replace(pattern, ' ');
    }
    else if (el){
        el.className = el.className + ' ' + _class;
    }
    else {
        console.log("Element not found");
    }
}