<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote(Request $request, Album $album)
    {
        $request->validate([
            'vote_type' => 'required|in:upvote,downvote',
        ]);

        $user = auth()->user();

        // Check if user already voted for this album
        $existingVote = Vote::where('user_id', $user->id)
                            ->where('album_id', $album->id)
                            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $request->vote_type) {
                $existingVote->delete();
                return response()->json(['message' => 'Vote removed successfully']);
            }
            
            // Update vote if different
            $existingVote->update(['vote_type' => $request->vote_type]);
            return response()->json(['message' => 'Vote updated successfully']);
        }

        // Create new vote
        Vote::create([
            'user_id' => $user->id,
            'album_id' => $album->id,
            'vote_type' => $request->vote_type,
        ]);

        return response()->json(['message' => 'Vote recorded successfully']);
    }
}
