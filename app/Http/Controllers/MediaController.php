<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Carbon\Carbon;

class MediaController extends Controller
{
    public function showSlideshow()
    {
        $today = Carbon::today(); // pour une comparaison sur le champ date uniquement

        $mediaFiles = Media::where(function ($query) use ($today) {
            $query->whereDate('display_date', $today)
                ->orWhereNull('display_date');
        })
            ->orderBy('order')
            ->get();

        return view('SlideShow', compact('mediaFiles'));
    }

    public function showManagePage()
    {
        $mediaFiles = Media::orderBy('display_date', 'asc')->get();

        // On groupe les fichiers par date (format 'Y-m-d') ou par une clé 'Sans date'
        $groupedMedia = $mediaFiles->groupBy(function ($item) {
            return $item->display_date ? Carbon::parse($item->display_date)->format('Y-m-d') : 'Sans date';
        });

        return view('Manage', compact('groupedMedia'));
    }

    public function uploadMedia(Request $request)
    {
        // Validation des fichiers avec messages personnalisés
        $request->validate([
            'media.*' => 'required|mimes:jpeg,png,jpg,gif,svg,mp4,mov,ogg,qt|max:200000',
            'display_date' => 'nullable|date',
        ], [
            'media.*.required' => 'Un fichier est requis.',
            'media.*.mimes' => 'Seuls les fichiers de type : jpeg, png, jpg, gif, svg, mp4, mov, ogg, qt sont autorisés.',
            'media.*.max' => 'La taille maximale pour chaque fichier est de 200 Mo.',
            'display_date.date' => 'La date d\'affichage doit être une date valide.',
        ]);

        $files = $request->file('media');

        if (!$files || count($files) === 0) {
            return redirect()->route('manage')->with('error', 'Aucun fichier valide n\'a été téléchargé.');
        }

        $displayDate = $request->display_date
            ? Carbon::parse($request->display_date)->format('Y-m-d')
            : null;


        foreach ($files as $file) {
            try {
                $type = $file->getMimeType();
                Log::info('Type MIME reçu : ' . $type);

                $path = $file->store('media', 'public');
                $mediaType = str_contains($type, 'image') ? 'image' : 'video';

                Media::create([
                    'id' => Media::max('id') + 1,
                    'path' => $path,
                    'type' => $mediaType,
                    'order' => Media::max('order') + 1,
                    'display_date' => $displayDate,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors du téléchargement du fichier : ' . $e->getMessage());
                return redirect()->route('manage')->with('error', 'Une erreur s\'est produite lors du téléchargement.');
            }
        }
        //dd du Media créé
        $media = Media::orderBy('id', 'desc')->first();
        //dd($media);

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

    private function swapOrderWithAdjacent(Media $media, string $direction = 'up')
    {
        $query = Media::query();

        if ($direction === 'up') {
            $adjacentMedia = $query->where('order', '<', $media->order)
                ->orderByDesc('order')
                ->first();
        } elseif ($direction === 'down') {
            $adjacentMedia = $query->where('order', '>', $media->order)
                ->orderBy('order')
                ->first();
        } else {
            return false;
        }

        if ($adjacentMedia) {
            $tempOrder = $media->order;
            $media->order = $adjacentMedia->order;
            $adjacentMedia->order = $tempOrder;

            $media->save();
            $adjacentMedia->save();
            return true;
        }

        return false;
    }

    public function moveUp($id)
    {
        try {
            $media = Media::findOrFail($id);
            if ($this->swapOrderWithAdjacent($media, 'up')) {
                return redirect()->route('manage')->with('success', 'L\'ordre du média a été mis à jour.');
            }
            return redirect()->route('manage')->with('info', 'Le média est déjà au sommet.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('manage')->with('error', 'Média non trouvé.');
        }
    }

    public function moveDown($id)
    {
        try {
            $media = Media::findOrFail($id);
            if ($this->swapOrderWithAdjacent($media, 'down')) {
                return redirect()->route('manage')->with('success', 'L\'ordre du média a été mis à jour.');
            }
            return redirect()->route('manage')->with('info', 'Le média est déjà en bas.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('manage')->with('error', 'Média non trouvé.');
        }
    }

}
