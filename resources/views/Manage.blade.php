<x-app-layout>
    <div class="container mx-auto p-6 max-w-7xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Médias</h1>
            <a href="{{ route('slideshow') }}" target="_blank">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    Voir l'écran de l'atelier
                </button>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Ajouter des fichiers</h2>

            {{-- Upload de fichiers --}}
            <form action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="dropzone"
                     onclick="document.getElementById('media').click()"
                     class="border-2 border-dashed border-indigo-300 rounded-lg p-10 text-center cursor-pointer hover:bg-indigo-50 transition duration-300">
                    <svg class="mx-auto h-16 w-16 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L4 8m4-4v12"></path>
                    </svg>
                    <p class="mt-4 text-gray-600">Glisser-déposer les fichiers ici ou cliquer pour sélectionner</p>
                    <p class="text-sm text-gray-500 mt-2">Images et vidéos acceptées</p>
                    <input type="file" name="media[]" multiple id="media" class="hidden" accept="image/*,video/*">
                </div>
                <div id="preview" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 mt-6"></div>
                <button type="submit" class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 flex items-center mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Envoyer les fichiers
                </button>
            </form>
        </div>

        {{-- Messages de session --}}
        @if (session('success'))
            <div class="mt-4 bg-green-100 text-green-700 p-4 rounded-lg border border-green-200 flex items-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 bg-red-100 text-red-700 p-4 rounded-lg border border-red-200 mb-6">
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
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

        {{-- Liste des médias --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Médias Existants</h2>

            <div id="media-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($mediaFiles as $index => $file)
                    <div class="relative bg-gray-50 rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden group">
                        <div class="aspect-w-16 aspect-h-9 overflow-hidden rounded-t-xl">
                            @if($file->type === 'image')
                                <img src="{{ asset('storage/' . $file->path) }}" alt="Image"
                                     class="w-full h-48 object-cover">
                            @elseif($file->type === 'video')
                                <video src="{{ asset('storage/' . $file->path) }}" class="w-full h-48 object-cover"
                                       controls></video>
                            @endif

                            {{-- Badge avec numéro --}}
                            <div class="absolute top-2 left-2 bg-indigo-600 text-white text-sm px-3 py-1 rounded-full">
                                #{{ $index + 1 }}
                            </div>

                            {{-- Bouton suppression --}}
                            <form method="POST" action="{{ route('media.destroy', $file->id) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média ?')"
                                  class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white font-medium p-2 rounded-full transition duration-300 opacity-0 group-hover:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center gap-2">
                                <a href="{{ route('media.moveUp', $file->id) }}"
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-2 rounded-lg transition duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                    <span class="ml-1">Monter</span>
                                </a>
                                <a href="{{ route('media.moveDown', $file->id) }}"
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-2 rounded-lg transition duration-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <span class="ml-1">Descendre</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(count($mediaFiles) === 0)
                    <div class="col-span-full text-center py-10 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-gray-500">Aucun média disponible</p>
                    </div>
                @endif
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