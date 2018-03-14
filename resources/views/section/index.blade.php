@extends('main')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row align-items-start">
                @include('section.filter')
                @include('section.goods')
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="/js/filters.js"></script>
@endpush