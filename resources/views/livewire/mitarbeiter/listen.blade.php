<div x-data="{ showForm: @entangle('showForm'), }"
    class="">

    <div class="">


        <!-- Formular Anfang  *************************************************** -->
        <div 
            class="flex fixed top-0 bg-opacity-60 item-center w-full h-full backdrop-blur" 
            x-show="showForm"
            x-on:click.self="showForm = false"
            x-on:keydown.escape.window="showForm = false"
            >


                <div 
                    x-data="{ isDisabled: true }" 
                    x-init="$watch('$wire.isModified', value => isDisabled = false);"
                    class="w-1/3 m-auto shadow-2xl rounded-xl p-8 border border-red-400 border-solid">
                    <div class="">
                        <div class="overflow-hidden  bg-blue-50 shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg">
                            <div class="m-2 border border-gray-500 rounded-lg">

                                <form wire:submit.prevent="save">
                                    @csrf
                                    <table class="m-2">

                                        <tbody>
                                            <tr>
                                                <td colspan="2" class="font-semibold">
                                                    Mitarbeiter ändern
                                                </td>
                                            </tr>
                                            <tr class="h-2">


                                                <td class="pl-2">
                                                    <input hidden wire:model.live="id" type="text" id="id">
                                                    <x-input-label>Personalnr</x-input-label>
                                                </td>

                                                <td class="pl-2">
                                                    <x-text-input wire:model.live="personalnr" type="text"
                                                        id="personalnr" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2">
                                                    <x-input-label>Anrede</x-input-label>
                                                </td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="anrede" type="text" id="anrede" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Vorname</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="vorname" type="text" id="vorname" />
                                                </td>
                                            </tr>
                                            <tr class="h-2">

                                                <td class="pl-2"><x-input-label>Name</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="name" type="text" id="name" />
                                                </td>

                                            </tr>
                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>gebdatum</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="gebdatum" type="date" id="gebdatum" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>stelle</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="stelle" type="text" id="stelle" />
                                                </td>
                                            </tr>


                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Password</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="password" type="password"
                                                        id="password" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>anstellung</x-input-label></td>
                                                <td class="pl-2">
                                                    <input wire:model="anstellung"
                                                        @if ($anstellung) checked="true" @endif
                                                        type="checkbox" id="anstellung">
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Besoldung</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="besoldung" type="text"
                                                        id="besoldung" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>lregelbeurteilung</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="lregelbeurteilung" type="date"
                                                        id="lregelbeurteilung" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>L-Sonstbeurteilung</x-input-label>
                                                </td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="lsonstbeurteilung" type="date"
                                                        id="lsonstbeurteilung" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Berechtigung</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="berechtigung" type="text"
                                                        id="berechtigung" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>N-Beurteilung</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="nbeurteilung" type="date"
                                                        id="nbeurteilung" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Amt</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="amt" type="text" id="amt" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Bemerkung</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="bemerkung" type="text"
                                                        id="bemerkung" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>E-Mail</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="email" type="mail" id="email" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Vertragsende</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="vertragsende" type="date"
                                                        id="vertragsende" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Teilzeit</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="teilzeit"
                                                        @if ($teilzeit) checked="true" @endif
                                                        type="checkbox" id="teilzeit" />
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Benachrichtigt</x-input-label></td>
                                                <td class="pl-2">
                                                    <input wire:model="benachrichtigt"
                                                        @if ($benachrichtigt) checked="true" @endif
                                                        type="checkbox" id="benachrichtigt">
                                                </td>
                                            </tr>

                                            <tr class="h-2">
                                                <td class="pl-2"><x-input-label>Abgabedatum</x-input-label></td>
                                                <td class="pl-2">
                                                    <x-text-input wire:model="abgabedatum" type="date"
                                                        id="abgabedatum" />
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
                            <x-th-list class="">ID</x-th-list>
                            <x-th-list class="">Name</x-th-list>
                            <x-th-list class="">Personalnr</x-th-list>
                            <x-th-list class="">Stelle</x-th-list>
                            <x-th-list class="">Nächste Beurtl.</x-th-list>
                            <x-th-list class="">Abgabedatum</x-th-list>
                            <x-th-list class="">Bemerkung</x-th-list>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mitarbeiterliste as $mitarbeiter)
                            <tr>
                                <x-td-list>
                                    {{ $mitarbeiter->id }}
                                </x-td-list>                                
                                <x-td-list>
                                    <a href="#" wire:click.prevent="edit({{ $mitarbeiter->id }})">
                                        {{ $mitarbeiter->name }}
                                    </a>
                                </x-td-list>
                                <x-td-list>
                                    {{ $mitarbeiter->personalnr }}

                                </x-td-list>

                                <x-td-list>{{ $mitarbeiter->stelleBezeichnung ? $mitarbeiter->stelleBezeichnung->bezeichnung : 'keine' }}</x-td-list>
                                <x-td-list>{{ $mitarbeiter->nbeurteilung }}</x-td-list>
                                <x-td-list>{{ $mitarbeiter->abgabedatum }}</x-td-list>
                                <x-td-list>{{ $mitarbeiter->bemerkung }}</x-td-list>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>

</div>
