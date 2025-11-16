@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-lg rounded-3">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Publish a New Blog Post</h4>
            </div>
            <div class="card-body">
                
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Post Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Feature Image (Optional)</label>
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Max size: 2MB. Recommended dimensions: 1200x600 px.</small>
                    </div>

                    {{-- Category Selector --}}
                    <div class="mb-3">
                        <label for="categories" class="form-label fw-bold">Categories (Select All That Apply)</label>
                        <select class="form-select @error('categories') is-invalid @enderror" multiple aria-label="Select categories" id="categories" name="categories[]">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('categories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Hold CTRL or CMD to select multiple categories.</small>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label fw-bold">Post Content (Supports Markdown)</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">Publish Post</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection