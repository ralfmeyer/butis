<div x-data="{ showForm: @entangle('showForm'), }" x-on:click.self="showForm = false" x-on:keydown.escape.window="showForm = false" class="">

    


        <!-- Formular Anfang  *************************************************** -->
        <div class="flex fixed top-0 bg-opacity-60 item-center w-full h-full" x-show="showForm">

            <div class="m-auto shadow-2xl rounded-xl p-8">
                <div x-data="{ isDisabled: true }"x-init="$watch('$wire.isModified', value => isDisabled = false);">
                    <div class="">
                        <div class="overflow-hidden  bg-blue-50 shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg">
                            <div class="m-2 border border-gray-500 rounded-lg">

                                <form wire:submit.prevent="save">
                                    @csrf
                                    <table class="m-2">

                                        <tbody>
                                            <tr>
                                                <td colspan="2" class="font-semibold">
                                                    Stelle ändern <span class="text-xs">(ID: {{ $id }})</span>
                                                </td>
                                            </tr>
                                            <tr class="h-2">


                                                <td class="pl-2"><input hidden wire:model.live="id" type="text"
                                                        id="id">
                                                    <x-input-label>Kennzeichen</x-input-label>
                                                </td>

                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="kennzeichen" type="text"
                                                        id="kennzeichen" />
                                                    @error('materialnummer')
                                                        <br><span
                                                            class="text-red-500 text-xl mt-3 block ">{{-- $message --}}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2">
                                                    <x-input-label>Bezeichnung</x-input-label>
                                                </td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="bezeichnung" type="text"
                                                        id="bezeichnung" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Ebene</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="ebene" type="number"
                                                        id="ebene" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Übergeordnet</x-input-label></td>
                                                <td class="pl-2">
                                                    <!-- input class="bg-gray-200" wire:model.live="uebergeordnetName" disabled type="text" id="uebergeordnet"-->
                                                    <x-text-input class="bg-gray-200"
                                                        wire:model.live="uebergeordnetName" disabled type="text"
                                                        id="uebergeordnetName" />
                                                </td>

                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Führungskompetenz</x-input-label></td>
                                                <td class="pl-2">
                                                    <input wire:model.live="fuehrungskompetenz"
                                                        @if ($fuehrungskompetenz) checked="true" @endif
                                                        type="checkbox" id="fuehrungskompetenz">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <button type="submit" :disabled="isDisabled"
                                                        class="mt-2 px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed">
                                                        Änderungen übernehmen
                                                    </button>
                                                </td>
                                            </tr>
                                            </button>
                                        </tbody>
                                    </table>
                                    @if (session()->has('message'))
                                        <div class="mt-4 p-4 bg-green-200 text-green-800">
                                            {{ session('message') }}
                                        </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Formular Ende  *************************************************** -->


        <!-- Tabelle Anfang  *************************************************** -->

        
            
        <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50">
            <div class="flex flex-row border border-gray-400 rounded m-2 bg-blue-100 px-2 py-4 text-2xl text-gray-800">
                {{ $ueberschrift }}
            </div>
            <div class="m-2 border border-gray-500 rounded-lg">
                <table class="w-full">

                    <thead class="bg-slate-200 font-bold text-gray-600">
                        <tr>
                            <x-th-list class="">Kennzeichen</x-th-list>
                            <x-th-list>&nbsp;</x-th-list>
                            <x-th-list class=" text-left">Bezeichnung</x-th-list>
                            <x-th-list class="">Ebene</x-th-list>
                            <x-th-list class="text-left">Übergeordnete Stelle</x-th-list>
                            <x-th-list class="">Führungskompetenz</x-th-list>
                            <!--x-th-list class="">Links</x-th-list -->
                            <!--x-th-list class="pr-2">Rechts</x-th-list -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stellen as $stelle)
                            <tr>
                                <x-td-list>
                                    <a href="#" wire:click.prevent="edit({{ $stelle->id }})">
                                        {{ $stelle->kennzeichen }} <span class="text-xs">(ID: {{ $stelle->id }})</span>
                                    </a>
                                </x-td-list>
                                <x-th-list>
                                    @if ($stelle->ebene >= 5)
                                        <x-ebene6 />
                                    @elseif ($stelle->ebene >= 4)
                                        <x-ebene5 />
                                    @elseif ($stelle->ebene >= 3)
                                        <x-ebene4 />
                                    @elseif ($stelle->ebene >= 2)
                                        <x-ebene3 />
                                    @elseif ($stelle->ebene >= 1)
                                        <x-ebene2 />
                                    @elseif ($stelle->ebene >= 0)
                                        <x-ebene1 />
                                    @else
                                        &nbsp;
                                    @endif
                                </x-th-list>

                                <x-td-list>{{ $stelle->bezeichnung }}</x-td-list>
                                <x-td-list>{{ $stelle->ebene }}</x-td-list>
                                <x-td-list>{{ $stelle->uebergeordneteStelle ? $stelle->uebergeordneteStelle->bezeichnung : 'Keine' }}</x-td-list>
                                <x-td-list class="text-center">
                                    @if ($stelle->fuehrungskompetenz)
                                        ja
                                    @else
                                        &nbsp;
                                    @endif
                                </x-td-list>
                                <!-- x-td-list>{{ $stelle->l }}</x-td-list -->
                                <!-- x-td-list>{{ $stelle->r }}</x-td-list -->
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
            
        
    
</div>
