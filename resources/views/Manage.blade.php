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

        <div class="bg-gray-700 rounded-xl shadow-md p-8 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Ajouter des fichiers</h2>

            <form action="{{ route('media.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="dropzone"
                     onclick="document.getElementById('media').click()"
                     class="border-2 border-dashed border-indigo-300 rounded-lg p-10 text-center cursor-pointer hover:bg-gray-600 transition duration-300">
                    <svg class="mx-auto h-16 w-16 text-indigo-400" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L4 8m4-4v12"></path>
                    </svg>
                    <p class="mt-4 text-gray-300">Glisser-déposer les fichiers ici ou cliquer pour sélectionner</p>
                    <p class="text-sm text-gray-400 mt-2">Images et vidéos acceptées</p>
                    <input type="file" name="media[]" multiple id="media" class="hidden" accept="image/*,video/*">
                </div>
                <div id="preview" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 mt-6"></div>
                <button type="submit"
                        class="mt-6 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 flex items-center mx-auto">
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

        <div class="bg-gray-700 rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-semibold text-white mb-6">Médias Existants</h2>

            <div id="media-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($mediaFiles as $index => $file)
                    <div class="relative bg-gray-600 rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden group">
                        <div class="aspect-w-16 aspect-h-9 overflow-hidden rounded-t-xl">
                            @if($file->type === 'image')
                                <img src="{{ asset('storage/' . $file->path) }}" alt="Image"
                                     class="w-full h-48 object-cover">
                            @elseif($file->type === 'video')
                                <video src="{{ asset('storage/' . $file->path) }}" class="w-full h-48 object-cover"
                                       controls></video>
                            @endif

                            <div class="absolute top-2 left-2 bg-indigo-600 text-white text-sm px-3 py-1 rounded-full">
                                #{{ $file->order }}
                            </div>

                            <form method="POST" action="{{ route('media.destroy', $file->id) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce média ?')"
                                  class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white font-medium p-2 rounded-full transition duration-300 opacity-0 group-hover:opacity-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center gap-2">
                                <a href="{{ route('media.moveUp', $file->id) }}"
                                   class="flex-1 bg-gray-600 hover:bg-gray-500 text-white font-medium py-2 px-2 rounded-lg transition duration-300 flex items-center justify-center">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.7071 9.1716L11.2929 7.75739L7.05024 12L11.2929 16.2426L12.7071 14.8284L9.87869 12L12.7071 9.1716Z"
                                              fill="#FFFFFF"/>
                                        <path d="M15.5355 7.75739L16.9497 9.1716L14.1213 12L16.9497 14.8284L15.5355 16.2426L11.2929 12L15.5355 7.75739Z"
                                              fill="#FFFFFF"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M1 5C1 2.79086 2.79086 1 5 1H19C21.2091 1 23 2.79086 23 5V19C23 21.2091 21.2091 23 19 23H5C2.79086 23 1 21.2091 1 19V5ZM5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3Z"
                                              fill="#FFFFFF"/>
                                    </svg>
                                    <span class="ml-1">Monter</span>
                                </a>
                                <a href="{{ route('media.moveDown', $file->id) }}"
                                   class="flex-1 bg-gray-600 hover:bg-gray-500 text-white font-medium py-2 px-2 rounded-lg transition duration-300 flex items-center justify-center">
                                    <span class="ml-1">Descendre</span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.46448 7.75739L7.05026 9.1716L9.87869 12L7.05029 14.8284L8.46451 16.2426L12.7071 12L8.46448 7.75739Z"
                                              fill="#FFFFFF"/>
                                        <path d="M11.2929 9.1716L12.7071 7.75739L16.9498 12L12.7071 16.2426L11.2929 14.8284L14.1213 12L11.2929 9.1716Z"
                                              fill="#FFFFFF"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                              d="M23 5C23 2.79086 21.2091 1 19 1H5C2.79086 1 1 2.79086 1 5V19C1 21.2091 2.79086 23 5 23H19C21.2091 23 23 21.2091 23 19V5ZM19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z"
                                              fill="#FFFFFF"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(count($mediaFiles) === 0)
                    <div class="col-span-full text-center py-10 bg-gray-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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

