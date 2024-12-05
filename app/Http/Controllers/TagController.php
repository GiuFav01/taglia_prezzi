<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    /**
     * Display a listing of tags.
     *
     * @return Response
     */
    public function index(): Response
    {
        try {
            $tags = Tag::all();
            if (session('success')) {
                return Inertia::render('Product', [
                    'tags' => $tags,
                    'flash' => ['success' => session('success')],
                ]);
            } elseif (session('error')) {
                return Inertia::render('Product', [
                    'tags' => $tags,
                    'flash' => ['error' => session('error')],
                ]);
            }
            return Inertia::render('Product', [
                'tags' => $tags,
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Product', [
                'tags' => [],
                'flash' => ['error' => 'Error fetching tags: ' . $e->getMessage()],
            ]);
        }
    }

    /**
     * Store a new tag.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        try {
            Tag::create($validated);
            return redirect()->route('tags.index')->with('success', 'Tag successfully created!');
        } catch (\Exception $e) {
            Log::error('Error creating tag: ' . $e->getMessage());
            return redirect()->route('tags.index')->with('error', 'Error creating the tag.');
        }
    }

    /**
     * Search for tags by name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $search = $request->query('search', '');
            $tags = Tag::where('name', 'like', "%{$search}%")->get();

            return response()->json($tags);
        } catch (\Exception $e) {
            Log::error('Error searching tags: ' . $e->getMessage());
            return response()->json(['error' => 'Error searching tags.'], 500);
        }
    }

    /**
     * Update an existing tag.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        try {
            $tag = Tag::findOrFail($id);
            $tag->update($validated);

            return redirect()->route('tags.index')->with('success', 'Tag successfully updated!');
        } catch (\Exception $e) {
            Log::error('Error updating tag: ' . $e->getMessage());
            return redirect()->route('tags.index')->with('error', 'Error updating the tag.');
        }
    }

    /**
     * Delete a tag.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();

            return redirect()->route('tags.index')->with('success', 'Tag successfully deleted!');
        } catch (\Exception $e) {
            Log::error('Error deleting tag: ' . $e->getMessage());
            return redirect()->route('tags.index')->with('error', 'Error deleting the tag.');
        }
    }
}
