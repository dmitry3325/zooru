<div class="col-3" id="filter-menu" data-section-id="{{ $section_id }}">
    <strong>Фильтры</strong>
    @foreach($filters_schema as $filter)
        <div class="filter-block">
            <div class="filter-title">
                {{ array_get($filter, 'title') }}
                <i class="material-icons fl_r">&#xE313;</i>
                <i class="material-icons fl_r hidden">&#xE315;</i>
            </div>
            <div class="filter-body">
                @if(array_get($filter, 'view_type') === 'items_list')
                    @foreach($filter['list'] as $f)
                        <a href="{{ array_get($f, 'url') }}" class="@if(array_get($f, 'disabled')) disabled @endif">
                            <label class="cbox filter-link" data-filter-key="{{ array_get($filter, 'code') }}" data-filter-value="{{ array_get($f, 'code') }}">
                                {{ array_get($f, 'value') }} <span class="count">({{ array_get($f, 'goods_count') }})</span>
                                <input type="checkbox" name="{{ array_get($f, 'code') }}" @if(array_get($f, 'checked') == 1) checked @endif>
                                <span class="checkmark"></span>
                            </label>
                        </a>
                    @endforeach
                @elseif(array_get($filter, 'view_type') === 'data_range')
                    <div class="rangeSlider-container">
                        <div se-min="{{ array_get($filter, 'range.min')  }}" se-step="50" se-max="{{ array_get($filter, 'range.max')  }}" data-filter-key="{{ array_get($filter, 'code') }}" class="rangeSlider"></div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
