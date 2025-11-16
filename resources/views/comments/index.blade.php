@extends('layouts.app')

@section('title', 'Manage Comments')
@section('description', 'Administration panel for viewing and moderating all user comments.')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h2 class="mb-4 fw-bold">Comment Moderation</h2>
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="text-muted">Total Comments: {{ $comments->total() }}</p>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                ← Back to Post Dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">All User Comments</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Content</th>
                                <th scope="col">Post</th>
                                <th scope="col">Author</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                                <tr>
                                    <td>{{ Str::limit($comment->content, 80) }}</td>
                                    <td>
                                        <a href="{{ route('posts.show', $comment->post->slug) }}" class="text-decoration-none">{{ Str::limit($comment->post->title, 40) }}</a>
                                    </td>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>{{ $comment->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('comments.destroy', $comment->id) }}" onsubmit="return confirm('Are you sure you want to delete this comment? This action is permanent.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No comments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection