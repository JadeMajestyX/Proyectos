<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Ticket — {{ $ticket->title }} (Admin)
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 shadow sm:rounded-lg">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Proyecto: <span class="font-medium text-gray-700">{{ $ticket->project->name }}</span></div>
                        <div class="mt-1 text-sm text-gray-500">Creador: {{ $ticket->creator->name }}</div>
                        <div class="mt-1 text-sm text-gray-500">@if($ticket->assignee) Asignado a: {{ $ticket->assignee->name }} @else Sin asignar @endif</div>
                    </div>
                    <div>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">{{ str_replace('_',' ', ucfirst($ticket->status)) }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full ml-2"
                              @class([
                                'bg-red-100 text-red-700' => $ticket->priority==='high',
                                'bg-amber-100 text-amber-700' => $ticket->priority==='medium',
                                'bg-green-100 text-green-700' => $ticket->priority==='low',
                              ])>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                        @if($ticket->category)
                            <span class="text-xs px-2 py-0.5 rounded-full ml-2 bg-blue-100 text-blue-700">{{ ucfirst($ticket->category) }}</span>
                        @endif
                    </div>
                </div>

                @if($ticket->media->count())
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($ticket->media as $m)
                            <div class="border rounded p-2">
                                @if($m->type==='image')
                                    <img src="{{ asset('storage/'.$m->path) }}" alt="media" class="w-full max-h-80 object-contain rounded cursor-zoom-in js-open-media" data-type="image" data-src="{{ asset('storage/'.$m->path) }}" />
                                @else
                                    <video src="{{ asset('storage/'.$m->path) }}" controls class="w-full max-h-80 rounded cursor-zoom-in js-open-media" data-type="video" data-src="{{ asset('storage/'.$m->path) }}"></video>
                                @endif
                                <div class="mt-1 text-xs text-gray-500 truncate">{{ $m->original_name }}</div>
                            </div>
                        @endforeach
                    </div>
                @elseif($ticket->image_path)
                    <div class="mt-6">
                        <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="Imagen del ticket" class="w-full max-h-[480px] object-contain rounded border cursor-zoom-in js-open-media" data-type="image" data-src="{{ asset('storage/'.$ticket->image_path) }}" />
                    </div>
                @endif

                @if($ticket->description)
                    <div class="mt-6 prose max-w-none">
                        <h3 class="text-base font-semibold text-gray-800 mb-2">Descripción</h3>
                        <p class="whitespace-pre-line text-gray-700">{{ $ticket->description }}</p>
                    </div>
                @endif

                <div class="mt-6 text-xs text-gray-500">
                    Creado {{ $ticket->created_at->diffForHumans() }} · Actualizado {{ $ticket->updated_at->diffForHumans() }}
                </div>

                <div class="mt-6 flex gap-2">
                    <x-secondary-button as="a" href="{{ route('admin.tickets.index') }}">Volver</x-secondary-button>
                    <x-primary-button as="a" href="{{ route('admin.tickets.edit', $ticket) }}">Asignar / Editar</x-primary-button>
                    <form method="POST" action="{{ route('admin.tickets.destroy', $ticket) }}" onsubmit="return confirm('¿Eliminar este ticket? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Eliminar</x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de medios (imagen/video) --}}
    <div id="media-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80"></div>
        <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-5xl w-full">
                <button type="button" id="media-modal-close" aria-label="Cerrar"
                        class="absolute -top-3 -right-3 bg-white text-gray-800 rounded-full w-8 h-8 shadow flex items-center justify-center hover:bg-gray-100">×</button>
                <img id="media-modal-img" src="" alt="media" class="w-full h-auto max-h-[80vh] rounded hidden" />
                <video id="media-modal-video" src="" controls class="w-full max-h-[80vh] rounded hidden"></video>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const modal = document.getElementById('media-modal');
            const img = document.getElementById('media-modal-img');
            const video = document.getElementById('media-modal-video');
            const closeBtn = document.getElementById('media-modal-close');
            function openModal(type, src){
                img.classList.add('hidden');
                video.classList.add('hidden');
                if(type === 'image'){
                    img.src = src;
                    img.classList.remove('hidden');
                } else {
                    video.src = src;
                    video.classList.remove('hidden');
                    try { video.play(); } catch(e) {}
                }
                modal.classList.remove('hidden');
                document.addEventListener('keydown', onKey);
            }
            function closeModal(){
                if(!modal.classList.contains('hidden')){
                    video.pause && video.pause();
                    modal.classList.add('hidden');
                    img.src = '';
                    video.src = '';
                    document.removeEventListener('keydown', onKey);
                }
            }
            function onKey(e){ if(e.key === 'Escape'){ closeModal(); } }
            document.addEventListener('click', function(e){
                const t = e.target.closest('.js-open-media');
                if(t){
                    e.preventDefault();
                    openModal(t.dataset.type, t.dataset.src);
                }
            });
            modal?.addEventListener('click', function(e){
                if(e.target === modal || e.target === closeBtn){ closeModal(); }
            });
        })();
    </script>
</x-app-layout>
