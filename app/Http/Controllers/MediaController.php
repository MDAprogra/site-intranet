<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class MediaController extends Controller
{
    public function showSlideshow()
    {
        $mediaFiles = Media::orderBy('order')->get();  // Trie par 'order'
        return view('SlideShow', compact('mediaFiles'));
    }

    public function showManagePage()
    {
        $mediaFiles = Media::all();
        return view('Manage', compact('mediaFiles'));
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'media.*' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,qt|max:200000',
        ]);

        foreach ($request->file('media') as $file) {
            $type = $file->getMimeType();
            Log::info('Type MIME reçu: ' . $type); // Ajout du log
            $path = $file->store('media', 'public');
            $type = str_contains($type, 'image') ? 'image' : 'video';

            Media::create([
                'path' => $path,
                'type' => $type,
            ]);
        }

        return redirect()->route('manage')->with('success', 'Media files uploaded successfully.');
    }
    public function destroy(Media $media)
    {
        Storage::delete($media->path); // Supprimer le fichier du stockage
        $media->delete(); // Supprimer l'enregistrement de la base de données
        return response()->json(['success' => true]);
    }

    public function moveUp($id)
    {
        $media = Media::findOrFail($id);
        $prevMedia = Media::where('order', '<', $media->order)->orderByDesc('order')->first();

        if ($prevMedia) {
            // Échanger les valeurs 'order' entre les deux fichiers
            $tempOrder = $media->order;
            $media->order = $prevMedia->order;
            $prevMedia->order = $tempOrder;

            // Sauvegarder les deux médias
            $media->save();
            $prevMedia->save();
        }

        return redirect()->route('manage')->with('success', 'L\'ordre du média a été mis à jour.');
    }

    public function moveDown($id)
    {
        $media = Media::findOrFail($id);
        $nextMedia = Media::where('order', '>', $media->order)->orderBy('order')->first();

        if ($nextMedia) {
            // Échanger les valeurs 'order' entre les deux fichiers
            $tempOrder = $media->order;
            $media->order = $nextMedia->order;
            $nextMedia->order = $tempOrder;

            // Sauvegarder les deux médias
            $media->save();
            $nextMedia->save();
        }

        return redirect()->route('manage')->with('success', 'L\'ordre du média a été mis à jour.');
    }

}
