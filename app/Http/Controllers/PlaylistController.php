<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\Song;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playlists = Playlist::all();
        return view('playlist.index', compact('playlists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tag' => 'required'
        ]);

        Playlist::create([
            'name' => $request->input('name'),
            'tag' => $request->input('tag')
        ]);

        return redirect('/playlist')->with('success', 'Playlist created successfully!');
    }
    public function create()
    {
       return view('playlist.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Playlist $playlist)
    {
        $allSongs = Song::all();
        return view('playlist.show', ['playlist' => $playlist, 'allSongs' => $allSongs ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    // Retrieve the playlist by its ID
    $playlist = Playlist::findOrFail($id);
    
    // Pass the playlist to the view
    return view('playlist.edit', ['playlist' => $playlist]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'name' => 'required',
        'tag' => 'required'
    ]);

    // Find the playlist and update its attributes
    
    Playlist::where('id', $id)->update([
        'name' => $request->input('name'),
        'tag' => $request->input('tag'),
    ]);

    // Redirect back to the playlists index page
    return redirect()->route('playlist.index')->with('success', 'Playlist updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

        $playlist = Playlist::where('id', $id);

        $playlist->delete();

        return redirect('/playlist')->with('success', 'Playlist deleted successfully!');
    }

    public function addSong(Request $request, Playlist $playlist) {

        if ($playlist->songs->contains($request->song)) {
            return redirect()->back()->with('error', 'Song is already in the playlist.');
        }

        $playlist->songs()->attach($request->song);
        return redirect('/playlist/' . $playlist->id)->with('success', 'Song added successfully!');
    }
    public function removesong(Request $request, Playlist $playlist) {

        if ($playlist->songs->contains($request->song)) {
        $playlist->songs()->detach($request->song);
        }else{
            return redirect()->back()->with('error', 'Song is not in the playlist.');
        }

        return redirect('/playlist/' . $playlist->id)->with('success', 'Song removed successfully!');
    }

}
