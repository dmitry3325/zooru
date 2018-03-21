<div class="col-3" id="filter-menu" data-section-id="{{ $section_id }}">
    <strong>Фильтры</strong>

    {{--фильтры цены--}}
    {{--<div class="filter-block">--}}
        {{--<div class="filter-title">--}}
            {{--Цена--}}
            {{--<i class="material-icons fl_r">&#xE313;</i>--}}
            {{--<i class="material-icons fl_r hidden">&#xE315;</i>--}}
        {{--</div>--}}
        {{--<div class="filter-body">--}}

            {{--<div class="rangeSlider-container">--}}
                {{--<div se-min="0" se-step="100" se-max="20000" se-name="price" class="rangeSlider"></div>--}}
            {{--</div>--}}

        {{--</div>--}}
    {{--</div>--}}

    @foreach($filters_schema as $filter)
        <div class="filter-block">
            <div class="filter-title">
                {{ array_get($filter, 'title') }}
                <i class="material-icons fl_r">&#xE313;</i>
                <i class="material-icons fl_r hidden">&#xE315;</i>
            </div>
            <div class="filter-body">
                @foreach($filter['list'] as $f)
                    <a href="{{ array_get($f, 'url') }}" class="@if(array_get($f, 'disabled')) disabled @endif">
                        <label class="cbox filter-link" data-filter-key="{{ array_get($filter, 'code') }}" data-filter-value="{{ array_get($f, 'code') }}">
                            {{ array_get($f, 'value') }} <span class="count">({{ array_get($f, 'goods_count') }})</span>
                            <input type="checkbox" name="{{ array_get($f, 'code') }}" @if(array_get($f, 'checked') == 1) checked @endif>
                            <span class="checkmark"></span>
                        </label>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
