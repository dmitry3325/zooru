@extends('main')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row align-items-start">
                @include('section.filter')
                <div class="col-9 goods-list row">
                    @include('section.goods')
                </div>
            </div>

        </div>
    </div>
@endsection