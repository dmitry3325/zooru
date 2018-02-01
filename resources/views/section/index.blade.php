@extends('main')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-3" id="filter-menu">
                    <h5>Фильтры</h5>

                    @foreach($filters_schema as $filter)
                        <div class="filter-block">
                            <div class="filter-title">
                                {{ array_get($filter, 'title') }}
                                {{--<i class="material-icons fl_r">&#xE15D;</i>--}}
                                <i class="material-icons fl_r">&#xE313;</i>
                                <i class="material-icons fl_r hidden">&#xE315;</i>
                            </div>
                            <div class="filter-body">
                                @foreach($filter['list'] as $f)
                                    <a href="{{ array_get($f, 'url') }}">
                                        <label class="cbox">
                                            {{ array_get($f, 'value') }} ({{ array_get($f, 'goods_count') }}шт.)
                                            <input type="checkbox">
                                            <span class="checkmark"></span>
                                        </label>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>

                <div class="col-9 goods-list row">
                    @foreach($goods as $good)
                        <div class="col-4">
                            @include('goods.components.product_window', array('good' => $good))
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="/js/filters.js"></script>
@endpush