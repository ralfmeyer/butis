<div class="" x-data="{ showForm: @entangle('showForm') }" x-on:click.self="showForm = false" x-cloak
    x-on:keydown.escape.window="showForm = false">

    <!-- Formular Anfang  *************************************************** -->

    <!-- Formular Ende  *************************************************** -->

    <x-my-form>
        <form wire:submit.prevent="save">
            @csrf
            <table class="m-2 w-full">

                <tbody>
                    <tr>
                        <td colspan="2" class="font-semibold">
                            @if ($isModified)
                                Stelle ändern <span class="text-xs">(ID: {{ $id }})</span>
                            @else
                                Neue Stelle
                            @endif
                        </td>
                    </tr>
                    <tr class="h-2">


                        <td class="pl-2"><input hidden wire:model="id" type="text" id="id">
                            <x-input-label>Kennzeichen</x-input-label>
                        </td>

                        <td class="pl-2">
                            <x-text-input wire:model="kennzeichen" type="text" id="kennzeichen" />
                            @error('kennzeichen')
                            <div class="float-end">
                                <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                            </div>
                            @enderror
                        </td>
                    </tr>
                    <tr class="h-2">

                        <td class="pl-2">
                            <x-input-label>Bezeichnung</x-input-label>
                        </td>
                        <td class="pl-2">
                            <x-text-input wire:model="bezeichnung" type="text" id="bezeichnung" class="w-11/12" />
                            @error('bezeichnung')
                            <div class="float-end">
                                <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                            </div>
                            @enderror
                        </td>
                    </tr>
                    <tr class="h-2">
                        <td class="pl-2"><x-input-label>Ebene</x-input-label></td>
                        <td class="pl-2">
                            <x-text-input wire:model="ebene" type="number" id="ebene" />
                            @error('ebene')
                            <div class="float-end">
                                <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                            </div>
                            @enderror
                        </td>
                    </tr>
                    <tr class="h-2">

                        <td class="pl-2"><x-input-label>Übergeordnet</x-input-label></td>
                        <td class="pl-2">
                            <!-- input class="bg-gray-200" wire:model.live="uebergeordnetName" disabled type="text" id="uebergeordnet"-->

                            <select id="uebergeordnet" wire:model="uebergeordnet" class="border border-white rounded">
                                <option value="">-- Bitte wählen --</option> <!-- Platzhalter für keine Auswahl -->
                                @foreach ($dataList as $item)
                                    <option value="{{ $item->id }}">{{ $item->bezeichnung }}</option>
                                @endforeach
                            </select>
                            @error('uebergeordnet')
                            <div class="float-end">
                                <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                            </div>
                            @enderror

                        </td>

                    </tr>
                    <tr class="h-2">

                        <td class="pl-2"><x-input-label>Führungskompetenz</x-input-label></td>
                        <td class="pl-2">
                            <input wire:model.live="fuehrungskompetenz"
                                @if ($fuehrungskompetenz) checked="true" @endif type="checkbox"
                                id="fuehrungskompetenz">
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
        </form>

    </x-my-form>
    <!-- Tabelle Anfang  *************************************************** -->



    <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50 w-5/6 m-auto">
        <x-my-title>
            <div  class="flex flex-row items-center justify-between w-full h-8">
                <div>
                    {{ $ueberschrift }}
                </div>
                <div class="text-blue-400">
                    <a href='#' wire:click='neu()' class="hover:text-blue-800 items-center flex flex-row">
                        <div class="px-2 ">
                            <x-fluentui-form-new-20-o class="h-8 " />
                        </div>
                        <div class="">
                            Neu
                        </div>
                    </a>
                </div>
            </div>

        </x-my-title>
        <div class="px-2">

            <livewire:stellen-baum />
        </div>

    </div>


    <script>
        // Hört auf das benutzerdefinierte Browser-Event 'refreshPage'
        window.addEventListener('refreshPage', event => {
            // Hier kannst du das Neuzeichnen der Seite oder Komponente erzwingen
            location.reload(); // Zum Beispiel einen vollen Seiten-Reload erzwingen
        });
    </script>
</div>
