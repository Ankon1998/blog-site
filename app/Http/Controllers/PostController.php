<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Services\PostService;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Support\Facades\Schema; // Used for error checking

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource (The main blog page).
     */
    public function index()
    {
        // Add Schema check to prevent recurring errors if tables were deleted
        if (Schema::hasTable('posts')) {
            $posts = Post::with(['user'])->latest()->paginate(10); 
        } else {
            $posts = collect();
        }
        
        $categories = Schema::hasTable('categories') ? Category::all() : collect();

        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * Handle post searching based on title or content. (NEW METHOD)
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Guard against missing table
        if (Schema::hasTable('posts')) {
            $posts = Post::where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->with('user')
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } else {
             $posts = collect();
        }
        
        $categories = Schema::hasTable('categories') ? Category::all() : collect();

        return view('posts.index', compact('posts', 'categories', 'query'));
    }


    /**
     * Display a listing of posts filtered by category.
     */
    public function postsByCategory(Category $category)
    {
        $posts = $category->posts()->with('user')->latest()->paginate(10);
        $categories = Category::all();

        return view('posts.index', compact('posts', 'category', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);
        
        $post = $this->postService->createPost($validated, $request);
        $post->categories()->attach($validated['categories'] ?? []);

        return redirect()->route('posts.index')->with('success', 'Post published successfully!');
    }

    /**
     * Display the specified resource (Single post view).
     */
    public function show(Post $post)
    {
        $post->load('user', 'categories');
        
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $postHtml = $converter->convert($post->content);
        $categories = Category::all();

        return view('posts.show', compact('post', 'postHtml', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_image' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $post = $this->postService->updatePost($post, $validated, $request);
        $post->categories()->sync($validated['categories'] ?? []);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    /**
     * Display the Admin Dashboard (list all posts for management).
     */
    public function dashboard()
    {
        if (Schema::hasTable('posts')) {
            $posts = Post::latest()->paginate(15);
        } else {
            $posts = collect();
        }
        return view('dashboard', compact('posts'));
    }

    /**
     * Display a listing of all comments for management.
     */
    public function commentIndex()
    {
        $comments = Comment::with(['post', 'user'])->latest()->paginate(25); 
        return view('comments.index', compact('comments'));
    }
}