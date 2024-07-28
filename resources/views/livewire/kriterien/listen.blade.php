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
                                                    Kriterium ändern
                                                </td>
                                            </tr>
                                            <tr class="h-2">


                                                <td class="pl-2"><input hidden wire:model.live="id" type="text"
                                                        id="id">
                                                    <x-input-label>Bereich</x-input-label>
                                                </td>

                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="bereich" type="text"
                                                        id="bereich" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2">
                                                    <x-input-label>Nummer</x-input-label>
                                                </td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="nummer" type="text"
                                                        id="nummer" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Überschrift</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="ueberschrift" type="text"
                                                        id="ueberschrift" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Text1</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="text1" type="text"
                                                    id="text1"
                                                    cols="50" rows="3">
                                               </textarea>

                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Text2</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="text2" type="text"
                                                    id="text2"
                                                    cols="50" rows="3">
                                               </textarea>

                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Text3</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="text3" type="text"
                                                    id="text3"
                                                    cols="50" rows="3">
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Text4</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="text4" type="text"
                                                    id="text4"
                                                    cols="50" rows="3">
                                                </td>
                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Text5</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="text5" type="text"
                                                    id="text5"
                                                    cols="50" rows="3">
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Art mit Min/Max</x-input-label></td>
                                                <td class="pl-2">
                                                    <input wire:model.live="art" type="checkbox" id="art" min="1" max="4">
                                                </td>
                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Hinweistext1</x-input-label></td>
                                                <td class="pl-2">
                                                   <textarea class=""
                                                        wire:model.live="hinweistext1" type="text"
                                                        id="hinweistext1"
                                                        cols="50" rows="3">
                                                   </textarea>
                                                </td>
                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Hinweistext2</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="hinweistext2" type="text"
                                                    id="hinweistext2"
                                                    cols="50" rows="3">
                                               </textarea>
                                            </td>
                                            </tr>                                            
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Hinweistext3</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="hinweistext3" type="text"
                                                    id="hinweistext3"
                                                    cols="50" rows="3">
                                               </textarea>
                                            </td>
                                            </tr>                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Hinweistext4</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="hinweistext4" type="text"
                                                    id="hinweistext4"
                                                    cols="50" rows="3">
                                               </textarea>
                                            </td>
                                            </tr>                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Hinweistext5</x-input-label></td>
                                                <td class="pl-2">
                                                    <textarea class=""
                                                    wire:model.live="hinweistext5" type="text"
                                                    id="hinweistext5"
                                                    cols="50" rows="3">
                                               </textarea>
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
                {{ $kopfueberschrift }}
            </div>
            <div class="m-2 border border-gray-500 rounded-lg">
                <table class="w-full">

                    <thead class="bg-slate-200 font-bold text-gray-600">
                        <tr>
                            <x-th-list class="">Bereich</x-th-list>
                            <x-th-list class="">Nummer</x-th-list>
                            <x-th-list class="">Überschrift</x-th-list>
                            <x-th-list class="">Art</x-th-list>
                            <x-th-list class="">Führungsmerkmal</x-th-list>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kriterien as $kriterium)
                            <tr>
                                <x-td-list>
                                    <a href="#" wire:click.prevent="edit({{ $kriterium->id }})">
                                        {{ $kriterium->bereich }}
                                    </a>
                                </x-td-list>
                                <x-th-list>{{ $kriterium->nummer }}</x-th-list>
                                <x-td-list>{{ $kriterium->ueberschrift }}</x-td-list>
                                <x-td-list>{{ $kriterium->art }}</x-td-list>
                                <x-td-list>{{ $kriterium->fuehrungsmerkmal }}</x-td-list>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
            
        
    
</div>
