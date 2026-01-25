@extends('layouts.app')

@section('content')
<div class="container py-4" style="margin-top: 100px;">
  <div class="row mb-4">
    <div class="col">
      <div class="d-flex justify-content-between align-items-center">
        <h2>ñ??? Blogs & Opportunities</h2>
        <a href="/blogs/new" class="btn btn-success" data-auth="user">Create Post</a>
      </div>
    </div>
  </div>

  <div id="blogsAlert"></div>

  <div class="card blogs-filters mb-4" style="box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
    <div class="card-body">
      <h5 class="mb-3">ñ??? Filters</h5>
      <div class="row g-3">
        <div class="col-md-3" id="blogsStatusWrap" hidden>
          <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Status</label>
          <select id="blogsStatusFilter" class="form-select" style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
            <option value="">All Status</option>
            <option value="APPROVED">ƒ?? Approved</option>
            <option value="PENDING">ƒ?? Pending</option>
            <option value="REJECTED">ƒ?? Rejected</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Category</label>
          <select id="blogsCategoryFilter" class="form-select" style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
            <option value="">All Categories</option>
            <option value="ARTICLE">ñ??ø Article</option>
            <option value="INTERNSHIP">ñ??? Internship</option>
            <option value="JOB">ñ??? Job</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label" style="font-weight: 600; font-size: 0.95rem;">Search</label>
          <input id="blogsSearchFilter" type="text" class="form-control" placeholder="ñ??? Search blogs by title or content..." style="border: 2px solid #dee2e6; border-radius: 0.5rem;">
        </div>
      </div>
    </div>
  </div>

  <div id="blogsLoading" class="text-center py-5 d-none">
    <div class="spinner-border text-primary"></div>
  </div>

  <div id="blogsList" class="row g-3"></div>
</div>
@endsection

@push('scripts')
<script src="/js/utils/helpers.js"></script>
<script src="/js/blogs/index.js"></script>
@endpush
