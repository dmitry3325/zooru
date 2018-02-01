@extends('main')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-3" id="filter-menu">
                    <h5>Фильтры</h5>

                    <div class="filter-block">
                        <div class="filter-title">
                            Производитель
                            {{--<i class="material-icons fl_r">&#xE15D;</i>--}}
                            <i class="material-icons fl_r">&#xE313;</i>
                            <i class="material-icons fl_r hidden">&#xE315;</i>
                        </div>
                        <div class="filter-body">
                            <label class="cbox">
                                DukesFarm
                                <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                            <a href="#">
                                <label class="cbox">
                                    Royal Canin
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </a>
                        </div>
                    </div>
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