@extends('layouts.app')

{{-- SEO Tags for Single Post --}}
@section('title', $post->title)
@section('description', Str::limit(strip_tags(app(\League\CommonMark\CommonMarkConverter::class)->convert($post->content)->getContent()), 160))
{{-- End SEO Tags --}}

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">

        <!-- Post Content Section -->
        <article class="bg-white p-5 shadow rounded-3 mb-5">
            <h1 class="mb-3 display-4 fw-bold text-dark">{{ $post->title }}</h1>
            
            <p class="text-muted mb-4 border-bottom pb-3">
                Published by: 
                @if ($post->user) <!-- Check if user exists -->
                    <span class="fw-bold text-dark">{{ $post->user->name }}</span> 
                @else
                    <span class="text-danger">Admin (Author Unknown)</span> 
                @endif
                on {{ $post->created_at->format('F d, Y') }}
            </p>

            {{-- New: Display Feature Image --}}
            @if ($post->image)
                <div class="mb-4 text-center">
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         alt="{{ $post->title }}" 
                         class="img-fluid rounded-3 shadow-sm" 
                         style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
            @endif
            
            <div class="post-content mb-5">
                {{-- Render the content as raw HTML (parsed from Markdown in the Controller) --}}
                {!! $postHtml !!}
            </div>

            <div class="d-flex justify-content-end">
                @auth
                    @if (auth()->user()->isAdmin()) {{-- NEW CHECK --}}
                        <a href="{{ route('posts.edit', $post->slug) }}" class="btn btn-sm btn-outline-primary me-2">Edit Post</a>
                        
                        <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete Post</button>
                        </form>
                    @endif {{-- END NEW CHECK --}}
                @endauth
            </div>
        </article>

        <!-- Comments Section -->
        <h3 class="mb-4 fw-bold text-dark">Comments ({{ $post->comments->count() }})</h3>
        
        @auth
            <!-- Comment Submission Form (Visible only if logged in) -->
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <h5 class="card-title">Leave a Comment</h5>
                    <form method="POST" action="{{ route('comments.store', $post->slug) }}">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="3" required placeholder="Write your comment here..."></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Submit Comment</button>
                    </form>
                </div>
            </div>
        @else
            <!-- Guest Prompt -->
            <div class="alert alert-info text-center">
                Please <a href="{{ route('login') }}" class="alert-link">log in</a> to post a comment.
            </div>
        @endauth

        <!-- Display Existing Comments -->
        <div class="comments-list">
            @forelse ($post->comments as $comment)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <p class="card-text">{{ $comment->content }}</p>
                        <footer class="blockquote-footer mt-2">
                            Commented by: 
                            <cite title="Source Title" class="fw-bold">{{ $comment->user->name }}</cite> 
                            on {{ $comment->created_at->format('M d, Y H:i') }}
                        </footer>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">No comments yet. Be the first!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection