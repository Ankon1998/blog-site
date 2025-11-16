<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostService
{
    /**
     * Handle file upload and return the storage path.
     */
    protected function handleImageUpload(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('posts', 'public');
        }
        return null;
    }

    /**
     * Delete an image file from storage if it exists.
     */
    protected function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Create a new post and handle the image upload.
     */
    public function createPost(array $validatedData, Request $request): Post
    {
        $imagePath = $this->handleImageUpload($request);
        $slug = Str::slug($validatedData['title']);

        return Post::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'slug' => $slug,
            'image' => $imagePath,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Update an existing post and handle image changes.
     */
    public function updatePost(Post $post, array $validatedData, Request $request): Post
    {
        $updateData = [
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
        ];

        // 1. Handle Slug Change
        if ($post->title !== $validatedData['title']) {
            $updateData['slug'] = Str::slug($validatedData['title']);
        }

        // 2. Handle Image Removal
        if (isset($validatedData['remove_image']) && $validatedData['remove_image']) {
            $this->deleteImage($post->image);
            $updateData['image'] = null;
        }

        // 3. Handle New Image Upload
        if ($request->hasFile('image')) {
            // Delete old image before uploading new one
            $this->deleteImage($post->image);
            $updateData['image'] = $this->handleImageUpload($request);
        }
        
        $post->update($updateData);

        return $post;
    }

    /**
     * Delete the post and its associated image.
     */
    public function deletePost(Post $post): void
    {
        $this->deleteImage($post->image);
        $post->delete();
    }
}