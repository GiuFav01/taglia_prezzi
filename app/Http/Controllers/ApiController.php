<?php

namespace App\Http\Controllers;

use App\Models\Api;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Services\ApiManagementService;

class ApiController extends Controller
{
    /**
     * Display a listing of APIs
     *
     * @return Response
     */
    public function index(): Response
    {
        try {
            $apis = Api::with('tags')->get();
            return Inertia::render('Apis', [
                'apis' => $apis,
                'flash' => [
                    'success' => session('success') ? session('success') : null,
                    'error' => session('error') ? session('error') : null
                ],
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Apis', [
                'apis' => [],
                'flash' => ['error' => 'Unable to fetch APIs. ' . $e->getMessage()],
            ]);
        }
    }

    /**
     * Show the details of a specific API.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $api = Api::with('tags')->findOrFail($id);
            return response()->json($api);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch API: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a new API.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|string',
            'description' => 'required|string',
            'tags' => 'array',
        ]);

        try {
            $api = Api::create($validated);

            if ($request->has('tags')) {
                $tagIds = collect($request->tags)->pluck('id')->toArray();
                $api->tags()->sync($tagIds);
            }

            return redirect()->route('apis.index')->with('success', 'API successfully created!');
        } catch (\Exception $e) {
            return redirect()->route('apis.index')->with('error', 'Error creating the API: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing API.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|string',
            'description' => 'required|string',
            'tags' => 'array',
        ]);

        try {
            $api = Api::findOrFail($id);
            $api->update($validated);

            if ($request->has('tags')) {
                $tagIds = collect($request->tags)->pluck('id')->toArray();
                $api->tags()->sync($tagIds);
            }

            return redirect()->route('apis.index')->with('success', 'API successfully updated!');
        } catch (\Exception $e) {
            return redirect()->route('apis.index')->with('error', 'Error updating the API: ' . $e->getMessage());
        }
    }

    /**
     * Delete an API.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $api = Api::findOrFail($id);
            $api->tags()->detach();
            $api->delete();

            return redirect()->route('apis.index')->with('success', 'API successfully deleted!');
        } catch (\Exception $e) {
            return redirect()->route('apis.index')->with('error', 'Error deleting the API: ' . $e->getMessage());
        }
    }

    /**
     * Detach a tag from an API.
     *
     * @param string $apiId
     * @param string $tagId
     * @return RedirectResponse
     */
    public function detachTag(string $apiId, string $tagId): RedirectResponse
    {
        try {
            Log::info("Detaching tag $tagId from API $apiId");
            $api = Api::findOrFail($apiId);
            $api->tags()->detach($tagId);

            return redirect()->route('apis.index')->with('success', 'Tag successfully removed!');
        } catch (\Exception $e) {
            return redirect()->route('apis.index')->with('error', 'Error removing the tag: ' . $e->getMessage());
        }
    }

    /**
     * Execute an API.
     *
     * @param string $apiId
     * @return RedirectResponse
     */
    public function execute(string $apiId)
    {
        try {
            $service = new ApiManagementService();
            $api = Api::findOrFail($apiId);
            $result = $service->executeApi($api);

            if (!$result['success']) {
                return redirect()->route('apis.index')->with('error', $result['message']);
            }

            return redirect()->route('apis.index')->with('success', 'API successfully executed!');
        } catch (\Exception $e) {
            return redirect()->route('apis.index')->with('error', 'Error executing the API: ' . $e->getMessage());
        }
    }
}
