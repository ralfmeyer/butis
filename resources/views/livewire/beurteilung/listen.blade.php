<div class="">

    <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50">
        <div class="flex flex-row border border-gray-400 rounded m-2 bg-blue-100 px-2 py-4 text-2xl text-gray-800">
            {{ $kopfueberschrift }}
        </div>
        <div class="m-2 border border-gray-500 rounded-lg">
            <table class="w-full">

                <thead class="bg-slate-200 font-bold text-gray-600">
                    <tr>
                        <x-th-list class="">Name</x-th-list>
                        <x-th-list class="">PersonalNr</x-th-list>
                        <x-th-list class="">N-Beurteilung</x-th-list>
                        <x-th-list class="">Bemerkung</x-th-list>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($beurteilungen as $beurteilung)
                        <tr>
                            <x-td-list>
                                <a href="#" wire:click.prevent="edit({{ $kriterium->id }})">
                                    {{ $beurteilung->name }} {{ $beurteilung->vorname }}
                                </a>
                            </x-td-list>
                            <x-th-list>{{ $beurteilung->personalnr }}</x-th-list>
                            <x-td-list>{{ $beurteilung->nbeurteilung }}</x-td-list>
                            <x-td-list>{{ $beurteilung->bemerkung }}</x-td-list>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
        
    

</div>
