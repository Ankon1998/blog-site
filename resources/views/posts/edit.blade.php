@extends('layouts.app')

@section('title', 'Edit Post: ' . $post->title)

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-lg rounded-3">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Editing: {{ $post->title }}</h4>
            </div>
            <div class="card-body">
                
                <form method="POST" action="{{ route('posts.update', $post->slug) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Post Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Current Image Display and Removal Option --}}
                    @if ($post->image)
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Current Feature Image</label>
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="img-fluid rounded mb-2" style="max-height: 200px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                <label class="form-check-label" for="remove_image">
                                    Check to delete current image
                                </label>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Upload New Feature Image (Optional)</label>
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Uploading a new image will replace the old one.</small>
                    </div>

                    {{-- Category Selector --}}
                    @php
                        // Get the IDs of categories currently attached to the post
                        $currentCategoryIds = $post->categories->pluck('id')->toArray();
                    @endphp

                    <div class="mb-3">
                        <label for="categories" class="form-label fw-bold">Categories (Select All That Apply)</label>
                        <select class="form-select @error('categories') is-invalid @enderror" multiple aria-label="Select categories" id="categories" name="categories[]">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $currentCategoryIds)) ? 'selected' : '' }}>
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
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="15" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">You can use Markdown syntax.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary btn-lg">Update Post</button>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection