let ZBRangeSlider = function (el) {
    let self = this;
    let startX = 0, x = 0;

    let slider = el;
    let touchLeft = slider.querySelector('.slider-touch-left');
    let touchRight = slider.querySelector('.slider-touch-right');
    let lineSpan = slider.querySelector('.slider-line span');

    // get some properties
    let min = parseFloat(slider.getAttribute('se-min'));
    let max = parseFloat(slider.getAttribute('se-max'));

    let step = 0.0;

    if (slider.getAttribute('se-step')) {
        step = Math.abs(parseFloat(slider.getAttribute('se-step')));
    }

    self.slider = slider;
    self.reset = function () {
        touchLeft.style.left = '0px';
        touchRight.style.left = (slider.offsetWidth - touchLeft.offsetWidth) + 'px';
        lineSpan.style.marginLeft = '0px';
        lineSpan.style.width = (slider.offsetWidth - touchLeft.offsetWidth) + 'px';
        startX = 0;
        x = 0;
        slider.setAttribute('se-min-value', min);
        slider.setAttribute('se-max-value', max);
    };

    // initial reset
    self.reset();

    // usefull values, min, max, normalize fact is the width of both touch buttons
    let normalizeFact = touchRight.offsetWidth / 2;

    let maxX = slider.offsetWidth - touchRight.offsetWidth;
    let selectedTouch = null;
    let initialValue = (lineSpan.offsetWidth - normalizeFact);

    // setup touch/click events
    function onStart(event) {

        // Prevent default dragging of selected content
        event.preventDefault();
        let eventTouch = event;

        if (event.touches) {
            eventTouch = event.touches[0];
        }

        if (this === touchLeft) {
            x = touchLeft.offsetLeft;
        }
        else {
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
        let eventTouch = event;

        if (event.touches) {
            eventTouch = event.touches[0];
        }

        x = eventTouch.pageX - startX;

        if (selectedTouch === touchLeft) {
            if (x > (touchRight.offsetLeft - selectedTouch.offsetWidth + normalizeFact)) {
                x = (touchRight.offsetLeft - selectedTouch.offsetWidth + normalizeFact)
            }
            else if (x < 0) {
                x = 0;
            }

            selectedTouch.style.left = x + 'px';
        }
        else if (selectedTouch === touchRight) {
            if (x < (touchLeft.offsetLeft + touchLeft.offsetWidth - normalizeFact)) {
                x = (touchLeft.offsetLeft + touchLeft.offsetWidth - normalizeFact)
            }
            else if (x > maxX) {
                x = maxX;
            }
            selectedTouch.style.left = x + 'px';
        }

        // update line span
        lineSpan.style.marginLeft = touchLeft.offsetLeft + 'px';
        lineSpan.style.width = (touchRight.offsetLeft - touchLeft.offsetLeft) + 'px';

        // write new value
        calculateValue();

        // call on change
        if (slider.getAttribute('on-change')) {
            let fn = new Function('min, max', slider.getAttribute('on-change'));
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
            let fn = new Function('min, max', slider.getAttribute('did-changed'));
            fn(slider.getAttribute('se-min-value'), slider.getAttribute('se-max-value'));
        }

        if (self.didChanged) {
            self.didChanged(slider.getAttribute('se-min-value'), slider.getAttribute('se-max-value'));
        }
    }

    function calculateValue() {
        let newValue = (lineSpan.offsetWidth - normalizeFact) / initialValue;
        let minValue = lineSpan.offsetLeft / initialValue;
        let maxValue = minValue + newValue;

        minValue = minValue * (max - min) + min;
        maxValue = maxValue * (max - min) + min;

        // console.log(step);
        if (step !== 0.0) {
            let multi = Math.floor((minValue / step));
            minValue = step * multi;

            multi = Math.floor((maxValue / step));
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

let sliders = document.getElementsByClassName('rangeSlider');
for (let i = 0; i < sliders.length; i++) {
    let el = sliders[i];
    let html = '<div class="slider-touch-left">' +
        '                                        <span></span>' +
        '                                    </div>\n' +
        '                                    <div class="slider-touch-right">' +
        '                                        <span></span>' +
        '                                    </div>' +
        '                                    <div class="slider-line">' +
        '                                        <span></span>' +
        '                                    </div>';

    el.innerHTML = html;

    let nameParam = el.getAttribute('data-filter-key');
    let parentContainer = el.parentNode;
    let htmlResult = '<input type="text" data-filter-key="' + nameParam + '" class="half-width slider-min range-input" placeholder="От"/>' +
        '<input type="text" data-filter-key="' + nameParam + '" class="half-width fl_r slider-max range-input" placeholder="До"/>';
    parentContainer.innerHTML = htmlResult + parentContainer.innerHTML;

    let newRangeSlider = new ZBRangeSlider(sliders[i]);

    newRangeSlider.onChange = function (min, max) {
        parentContainer.getElementsByClassName('slider-min')[0].value = min;
        parentContainer.getElementsByClassName('slider-max')[0].value = max;
    }

    newRangeSlider.didChanged = function (min, max) {
        parentContainer.getElementsByClassName('slider-min')[0].value = min;
        parentContainer.getElementsByClassName('slider-max')[0].value = max;
    }
}
