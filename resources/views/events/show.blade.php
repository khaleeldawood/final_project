@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div id="eventDetailsRoot"></div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/events/show.js"></script>
@endpush
