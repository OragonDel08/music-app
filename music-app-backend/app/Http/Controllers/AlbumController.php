<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 8);
 
        // $albums = Album::withCount([
        //     'upvotes as upvote_count',
        //     'downvotes as downvote_count'
        //     ])
        //     ->selectRaw('(upvote_count + downvote_count) as totalvote_count')
        //     ->with('votes')
        //     ->where('title', 'like', "%$search%")
        //     ->orWhere('artist', 'like', "%$search%")
        //     ->orderByDesc('totalvote_count')
        //     ->paginate($perPage);

        $albums = Album::withCount([
            'upvotes as upvote_count',
            'downvotes as downvote_count'
            ])
            ->selectRaw('((SELECT COUNT(*) FROM votes WHERE votes.album_id = albums.id AND votes.vote_type = "upvote") + (SELECT COUNT(*) FROM votes WHERE votes.album_id = albums.id AND votes.vote_type = "downvote")) as totalvote_count')
            ->with('votes')
            ->where('title', 'like', "%$search%")
            ->orWhere('artist', 'like', "%$search%")
            ->orderByDesc('totalvote_count')
            ->orderBy('title')
            ->paginate($perPage);
        return response()->json($albums);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return $album;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        if (Gate::denies('delete-album')) {
            return response()->json([
                'message' => 'Forbidden: Only admins can delete albums.'
            ], 403);
        }

        $album->delete();

        return [
            'message' => 'The album was deleted'
        ];
    }
}
