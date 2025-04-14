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
        $mediaFiles = Media::orderBy('order')->get();
        return view('Manage', compact('mediaFiles'));
    }

    public function uploadMedia(Request $request)
    {
        // Validation des fichiers avec messages personnalisés
        $request->validate([
            'media.*' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,qt|max:200000',
        ], [
            'media.*.required' => 'Un fichier est requis.',
            'media.*.mimes' => 'Seuls les fichiers de type : jpeg, png, jpg, gif, svg, mp4, mov, ogg, qt sont autorisés.',
            'media.*.max' => 'La taille maximale pour chaque fichier est de 200 Mo.',
        ]);

        // Récupération des fichiers
        $files = $request->file('media');

        // Vérification si des fichiers ont été fournis
        if (!$files || count($files) === 0) {
            return redirect()->route('manage')->with('error', 'Aucun fichier valide n\'a été téléchargé.');
        }

        foreach ($files as $file) {
            try {
                // Log du type MIME
                $type = $file->getMimeType();
                Log::info('Type MIME reçu : ' . $type);

                // Sauvegarde du fichier
                $path = $file->store('media', 'public');

                // Détermination du type (image ou vidéo)
                $mediaType = str_contains($type, 'image') ? 'image' : 'video';

                // Création de l'entrée dans la base de données
                Media::create([
                    'id' => Media::max('id') + 1, // Incrémente l'ID
                    'path' => $path,
                    'type' => $mediaType,
                    'order' => Media::max('order') + 1, // Incrémente l'ordre
                ]);

            } catch (\Exception $e) {
                // Log de l'erreur en cas de problème
                Log::error('Erreur lors du téléchargement du fichier : ' . $e->getMessage());
                return redirect()->route('manage')->with('error', 'Une erreur s\'est produite lors du téléchargement.');
            }
        }

        // Redirection avec un message de succès
        return redirect()->route('manage')->with('success', 'Les fichiers ont été téléchargés avec succès.');
    }

    public function destroy(Media $media)
    {
        if (Storage::disk('public')->delete($media->path)) {
            $media->delete();
            return redirect()->route('manage')->with('success', 'Media supprimé avec succès.');
        }
        return redirect()->route('manage')->with('error', 'Le fichier n\'existe pas.');
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
