@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h2 class="mb-4 fw-bold">Post Management Dashboard</h2>
        
        {{-- BUTTONS SECTION (Updated for all three management links) --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-muted">Currently logged in as: {{ Auth::user()->name }}</p>
            <div>
                <a href="{{ route('comments.index') }}" class="btn btn-outline-dark me-2 shadow-sm">
                    Manage Comments
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark me-2 shadow-sm">
                    Manage Categories
                </a>
                <a href="{{ route('posts.create') }}" class="btn btn-success btn-lg shadow-sm">
                    + Create New Post
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">All Blog Posts</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col" class="text-center">Categories</th>
                                <th scope="col" class="text-center">Comments</th>
                                <th scope="col">Published</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none fw-bold">{{ Str::limit($post->title, 60) }}</a>
                                    </td>
                                    {{-- Display Categories --}}
                                    <td class="text-center">
                                        @forelse ($post->categories as $category)
                                            <span class="badge bg-secondary">{{ $category->name }}</span>
                                        @empty
                                            <span class="text-muted fst-italic">None</span>
                                        @endforelse
                                    </td>
                                    <td class="text-center">{{ $post->comments->count() }}</td>
                                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('posts.edit', $post->slug) }}" class="btn btn-sm btn-outline-warning me-2">Edit</a>
                                        <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No posts found. <a href="{{ route('posts.create') }}">Create your first post!</a></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection