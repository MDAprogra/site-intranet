@php use Carbon\Carbon; @endphp
<x-app-layout>
    <div class="container mx-auto p-6 max-w-7xl rounded-xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-white">Gestion des Médias</h1>
            <a href="{{ route('slideshow') }}" target="_blank">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Voir l'écran de l'atelier
                </button>
            </a>
        </div>

        <div class="bg-gray-800 rounded-2xl shadow-lg p-10 mb-10 border border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-6">Ajouter des fichiers</h2>

            <form action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="dropzone"
                     onclick="document.getElementById('media').click()"
                     class="border-2 border-dashed border-indigo-400 rounded-2xl p-12 text-center cursor-pointer hover:bg-gray-700 transition-colors duration-300">
                    <svg class="mx-auto h-20 w-20 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L4 8m4-4v12"></path>
                    </svg>
                    <p class="mt-6 text-gray-200 text-lg">Glisser-déposer les fichiers ici ou <span class="underline text-indigo-400">cliquer pour sélectionner</span></p>
                    <p class="text-sm text-gray-400 mt-2">Formats acceptés : images et vidéos</p>
                    <input type="file" name="media[]" multiple id="media" class="hidden" accept="image/*,video/*">
                </div>

                <label for="display_date" class="block text-sm font-medium text-gray-300 mt-8 mb-2">
                    Date d'affichage <span class="text-gray-500">(optionnelle)</span>
                </label>
                <input type="date" name="display_date" id="display_date"
                       class="w-full bg-gray-900 text-white border border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">

                <div id="preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 mt-8"></div>

                <button type="submit"
                        class="mt-8 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 flex items-center mx-auto shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Envoyer les fichiers
                </button>
            </form>
        </div>


    @foreach (['success' => 'green', 'info' => 'blue'] as $type => $color)
            @if (session($type))
                <div class="mt-4 bg-{{ $color }}-600 text-white p-4 rounded-lg flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            <div class="mt-4 bg-red-600 text-white p-4 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-medium">Erreur :</span>
                </div>
                <ul class="list-disc pl-10">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gray-800 rounded-2xl shadow-lg p-10">
            <h2 class="text-2xl font-bold text-white mb-8">📁 Médias existants</h2>

            <div id="media-grid" class="space-y-14">
                @foreach($groupedMedia as $date => $files)
                    <section>
                        <h3 class="text-xl font-semibold text-white border-b border-gray-700 pb-3 mb-8 flex items-center gap-2">
                            📅 {{ $date === 'Sans date' ? 'Aucune date définie (affiché en permanence)' : \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($files as $file)
                                <div class="relative bg-gray-900 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group">
                                    <div class="aspect-w-16 aspect-h-9">
                                        @if($file->type === 'image')
                                            <img src="{{ asset('storage/' . $file->path) }}" alt="Image"
                                                 class="w-full h-full object-cover rounded-t-2xl">
                                        @elseif($file->type === 'video')
                                            <video src="{{ asset('storage/' . $file->path) }}"
                                                   class="w-full h-full object-cover rounded-t-2xl"
                                                   controls></video>
                                        @endif

                                        <div class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-md">
                                            Ordre #{{ $file->order }}
                                        </div>

                                        <form method="POST" action="{{ route('media.destroy', $file->id) }}"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média ?')"
                                              class="absolute top-3 right-3">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 shadow-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 4h6a1 1 0 011 1v1H8V5a1 1 0 011-1z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="p-4 flex gap-2">
                                        <a href="{{ route('media.moveUp', $file->id) }}"
                                           class="flex-1 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium py-2 px-3 rounded-lg text-center transition">
                                            ⬆️ Monter
                                        </a>
                                        <a href="{{ route('media.moveDown', $file->id) }}"
                                           class="flex-1 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium py-2 px-3 rounded-lg text-center transition">
                                            ⬇️ Descendre
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>

    </div>
    <script>
        const input = document.getElementById('media');
        const preview = document.getElementById('preview');
        const dropzone = document.getElementById('dropzone');

        input.addEventListener('change', () => {
            preview.innerHTML = '';
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                const previewItem = document.createElement('div');
                previewItem.className = 'relative bg-gray-100 rounded-lg overflow-hidden';

                // Prévisualisation des images
                if (file.type.startsWith('image/')) {
                    reader.onload = e => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-32 object-cover';
                        previewItem.appendChild(img);

                        const fileNameDiv = document.createElement('div');
                        fileNameDiv.className = 'p-2 text-xs truncate';
                        fileNameDiv.textContent = file.name;
                        previewItem.appendChild(fileNameDiv);

                        preview.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }

                // Prévisualisation des vidéos
                if (file.type.startsWith('video/')) {
                    reader.onload = e => {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.className = 'w-full h-32 object-cover';
                        video.controls = true;
                        previewItem.appendChild(video);

                        const fileNameDiv = document.createElement('div');
                        fileNameDiv.className = 'p-2 text-xs truncate';
                        fileNameDiv.textContent = file.name;
                        previewItem.appendChild(fileNameDiv);

                        preview.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Style visuel pour glisser-déposer
        dropzone.addEventListener('dragover', e => {
            e.preventDefault();
            dropzone.classList.add('bg-indigo-50');
            dropzone.classList.add('border-indigo-500');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('bg-indigo-50');
            dropzone.classList.remove('border-indigo-500');
        });

        dropzone.addEventListener('drop', e => {
            e.preventDefault();
            dropzone.classList.remove('bg-indigo-50');
            dropzone.classList.remove('border-indigo-500');
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change')); // déclenche le preview
        });
    </script>
</x-app-layout>

