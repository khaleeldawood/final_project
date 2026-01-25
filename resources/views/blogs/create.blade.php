@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
      <h3>ñ??? Create New Post</h3>
    </div>
    <div class="card-body">
      <div id="createBlogError" class="alert alert-danger d-none"></div>
      <form id="createBlogForm">
        <div class="mb-3">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required minlength="3" />
        </div>
        <div class="mb-3">
          <label class="form-label">Category *</label>
          <select name="category" class="form-select" required>
            <option value="ARTICLE">Article</option>
            <option value="INTERNSHIP">Internship</option>
            <option value="JOB">Job Opportunity</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Content *</label>
          <textarea name="content" rows="10" class="form-control" required></textarea>
        </div>
        <div class="mb-3 form-check">
          <input type="checkbox" name="isGlobal" class="form-check-input" id="createBlogGlobal" />
          <label class="form-check-label" for="createBlogGlobal">Make this post visible globally (across all universities)</label>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-success" id="createBlogSubmit">Create Post</button>
          <a href="/blogs" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/blogs/create.js"></script>
@endpush
