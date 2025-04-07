<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold mb-6">Gestion des Médias</h1>

        <div id="dropzone"
             class="border-2 border-dashed rounded-lg p-8 text-center cursor-pointer hover:bg-gray-100 transition duration-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L4 8m4-4v12"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600">Glisser-déposer les fichiers ici ou cliquer pour sélectionner</p>
            <input type="file" name="media[]" multiple id="media" class="hidden" onchange="uploadFiles()">
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="text-2xl font-semibold mt-8 mb-4">Médias Existants</h2>

        <div id="media-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($mediaFiles as $index => $file) <!-- $index est l'indice de l'itération -->
            <div class="relative rounded-lg shadow-md hover:shadow-lg transition duration-300">
                <div class="overflow-hidden rounded-t-lg">
                    @if($file->type == 'image')
                        <img src="{{ asset('storage/' . $file->path) }}" alt="Image"
                             class="w-full h-auto object-cover rounded-t-lg">
                    @elseif($file->type == 'video')
                        <video src="{{ asset('storage/' . $file->path) }}" class="w-full h-auto rounded-t-lg"
                               controls></video>
                    @endif
                </div>
                <div class="p-4">
                    <!-- Affichage du numéro d'ordre -->
                    <div class="mb-2">
                        <span class="text-sm text-gray-500">#{{ $index + 1 }}</span> <!-- +1 pour commencer à 1 au lieu de 0 -->
                    </div>
                    <div class="flex justify-between items-center">
                        <button onclick="location.href='{{ route('media.moveUp', $file->id) }}'"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
                            Monter
                        </button>
                        <button onclick="location.href='{{ route('media.moveDown', $file->id) }}'"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">
                            Descendre
                        </button>
                    </div>
                    <button
                        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-3 rounded-md transition duration-300"
                        onclick="showDeleteModal('{{ $file->id }}')">
                        Supprimer
                    </button>
                </div>
            </div>
            @endforeach
        </div>


        <div id="delete-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
             role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Confirmer la suppression</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Êtes-vous sûr de vouloir supprimer ce média ?</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button id="confirm-delete" type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Supprimer
                        </button>
                        <button id="cancel-delete" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('media');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteButton = document.getElementById('confirm-delete');
        const cancelDeleteButton = document.getElementById('cancel-delete');
        let mediaIdToDelete;

        dropzone.addEventListener('click', () => fileInput.click());

        dropzone.addEventListener('dragover', (e) => e.preventDefault());

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInput.files = e.dataTransfer.files;
            uploadFiles();
        });

        function uploadFiles() {
            const files = fileInput.files;
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('media[]', files[i]);
            }
            fetch('{{ route('media.upload') }}', {
                method: 'POST',
                body: formData,
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
                .then(() => location.reload())
                .catch(error => console.error('Erreur lors de l\'upload :', error));
        }

        function showDeleteModal(mediaId) {
            mediaIdToDelete = mediaId;
            deleteModal.classList.remove('hidden');
        }

        confirmDeleteButton.addEventListener('click', () => {
            fetch(`/media/${mediaIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(() => location.reload())
                .catch(error => console.error('Erreur lors de la suppression :', error));
            deleteModal.classList.add('hidden');
        });

        cancelDeleteButton.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
    </script>
</x-app-layout>
