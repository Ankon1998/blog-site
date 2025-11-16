@extends('layouts.app')

{{-- SEO Tags for Blog Index --}}
@section('title', 'Latest Articles and News')
@section('description', 'Welcome to the index of our lightweight, fast-loading blog. Read the newest articles and insights here.')
{{-- End SEO Tags --}}

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h1 class="fw-bolder mb-0 text-primary">BLOG HEADLINES</h1>
            @auth
                @if (auth()->user()->isAdmin()) {{-- NEW CHECK --}}
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-dark">Go to Admin Dashboard</a>
                @endif
            @endauth
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        {{-- If posts exist, we structure them into a BBC-style grid --}}
        @if ($posts->isNotEmpty())
            
            {{-- 1. FEATURED ARTICLE --}}
            @php $featuredPost = $posts->first(); @endphp
            
            <div class="card mb-4 shadow-sm border-0 rounded-3 overflow-hidden bg-white">
                <div class="row g-0">
                    <div class="col-md-7">
                        @if ($featuredPost->image)
                            <img src="{{ asset('storage/' . $featuredPost->image) }}" class="img-fluid w-100 h-100 object-fit-cover" alt="{{ $featuredPost->title }}" style="max-height: 400px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100" style="min-height: 250px;">
                                <span class="text-muted fw-bold fs-4">Featured Story</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-5 d-flex align-items-center">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="card-title fw-bolder display-6">
                                <a href="{{ route('posts.show', $featuredPost->slug) }}" class="text-decoration-none text-dark">{{ $featuredPost->title }}</a>
                            </h2>
                            <p class="card-text text-muted mb-3">
                                Published {{ $featuredPost->created_at->diffForHumans() }} | 
                                <span class="text-primary">{{ $featuredPost->comments->count() }} Comments</span>
                            </p>
                            <p class="card-text fs-5">
                                {{ Str::limit(strip_tags(app(\League\CommonMark\CommonMarkConverter::class)->convert($featuredPost->content)->getContent()), 150) }}
                            </p>
                            <a href="{{ route('posts.show', $featuredPost->slug) }}" class="btn btn-dark btn-lg mt-3">Full Story &raquo;</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. ARTICLE GRID --}}
            <h3 class="fw-bold mb-3 mt-5 text-dark border-bottom pb-2">More Stories</h3>
            <div class="row">
                @foreach ($posts->skip(1) as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-3 h-100">
                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top object-fit-cover" alt="{{ $post->title }}" style="height: 180px;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bolder">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark">{{ Str::limit($post->title, 65) }}</a>
                                </h5>
                                <p class="card-text text-muted small mt-1 mb-3">
                                    {{ $post->created_at->diffForHumans() }} 
                                </p>
                                <p class="card-text text-dark flex-grow-1">
                                    {{ Str::limit(strip_tags(app(\League\CommonMark\CommonMarkConverter::class)->convert($post->content)->getContent()), 80) }}
                                </p>
                                <a href="{{ route('posts.show', $post->slug) }}" class="mt-auto text-primary fw-bold text-decoration-none">Read More</a>
                                
                                @auth
                                    @if (auth()->user()->isAdmin()) {{-- NEW CHECK --}}
                                        {{-- Quick access links for admin --}}
                                        <a href="{{ route('posts.edit', $post->slug) }}" class="btn btn-sm btn-outline-secondary mt-2 ms-2">Edit</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination links --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection