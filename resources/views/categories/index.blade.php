@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <h2 class="mb-4 fw-bold">Category Management</h2>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-muted">Total Categories: {{ $categories->total() }}</p>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                    ← Back to Post Dashboard
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Category Creation Form (Left Column) --}}
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-header bg-primary text-white fw-bold">
                        Create New Category
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('categories.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success w-100">Add Category</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Categories List (Right Column) --}}
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">
                        Existing Categories
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Posts</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td class="text-center">{{ $category->posts_count }}</td>
                                            <td class="text-center">
                                                <form method="POST" action="{{ route('categories.destroy', $category->slug) }}" onsubmit="return confirm('WARNING: Deleting this category will detach it from {{ $category->posts_count }} posts. Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" @if($category->posts_count > 0) title="Category is linked to posts" @endif>Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">No categories created yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection