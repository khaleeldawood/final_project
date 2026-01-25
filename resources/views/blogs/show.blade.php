@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div id="blogDetailsRoot"></div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/blogs/show.js"></script>
@endpush
