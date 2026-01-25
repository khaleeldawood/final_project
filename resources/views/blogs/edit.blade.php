@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
      <h3>ƒ??‹?? Edit Blog</h3>
    </div>
    <div class="card-body">
      <div id="editBlogError" class="alert alert-danger d-none"></div>
      <div id="editBlogWarning" class="alert alert-warning d-none">
        ƒ?ÿ‹?? <strong>Note:</strong> Editing an approved blog will reset its status to PENDING and require re-approval by a supervisor.
      </div>
      <form id="editBlogForm" class="d-none">
        <div class="mb-3">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required minlength="3" />
        </div>
        <div class="mb-3">
          <label class="form-label">Content *</label>
          <textarea name="content" rows="10" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Category *</label>
          <select name="category" class="form-select" required>
            <option value="ARTICLE">ñ??ø Article</option>
            <option value="INTERNSHIP">ñ??? Internship</option>
            <option value="JOB">ñ??? Job Opportunity</option>
          </select>
        </div>
        <div class="mb-4 form-check">
          <input type="checkbox" name="isGlobal" class="form-check-input" id="editBlogGlobal" />
          <label class="form-check-label" for="editBlogGlobal">ñ??? Make this post visible to all universities</label>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary" id="editBlogSubmit">Save Changes</button>
          <a href="/my-blogs" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/blogs/edit.js"></script>
@endpush
