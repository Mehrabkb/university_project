<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="ms-4 mt-4">Favorites Places</h2>
                <div class="p-6 text-gray-900">

                    @foreach ($favorites as $fav)
                        @php
                            $jData = json_decode($fav->data);
                        @endphp
                        <div class="mb-4 p-4 border rounded shadow">
                            <h3 class="text-lg font-semibold">
                                {{ $jData->properties->name ?? 'no name' }}
                            </h3>

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#placeModal"
                                data-place='@json($fav->data)'
                                onclick="showModalFromElement(this)">
                                show info
                            </button>

                            <form action="{{ route('favorites.destroy', $fav->user_favorite_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded">
                                    حذف
                                </button>
                            </form>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="placeModal" tabindex="-1" aria-labelledby="placeModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="placeModalLabel">info</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul id="place-info" class="list-group list-group-flush">
                                            <!-- داده‌ها به‌صورت داینامیک اضافه می‌شن -->
                                        </ul>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('favorites.destroy', $fav->user_favorite_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Remove From Favorites</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div id="place-details" class="mt-6 bg-gray-100 p-4 rounded hidden">
                        <pre id="json-view" class="text-sm text-gray-800 overflow-x-auto"></pre>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showDetails(data) {
            document.getElementById('json-view').textContent = JSON.stringify(data, null, 2);
            document.getElementById('place-details').classList.remove('hidden');
        }
        function showModalFromElement(el) {
            let data = JSON.parse(el.dataset.place);
            data = JSON.parse(data);
            console.log(data);
            const ul = document.getElementById('place-info');
            ul.innerHTML = ''; // پاک کردن داده قبلی

            const props = data?.properties || {};

            // نام
            const name = props.name ?? 'no name';
            ul.innerHTML += `<li class="list-group-item"><strong>name:</strong> ${name}</li>`;

            // نمایش سایر ویژگی‌ها
            for (const [key, value] of Object.entries(props)) {
                if (key !== 'name') {
                    ul.innerHTML += `<li class="list-group-item"><strong>${key}:</strong> ${value}</li>`;
                }
            }
        }
    </script>

</x-app-layout>
