<div class="" x-data="{ showForm: @entangle('showForm') }" x-cloak x-on:click.self="showForm = false"
    x-on:keydown.escape.window="showForm = false">



    <!-- Tabelle Anfang  *************************************************** -->
    <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50 w-5/6 m-auto z-0 relative">
        <x-my-title>
            {{ $ueberschrift }}
        </x-my-title>
        <div class="m-2 border border-gray-500 rounded-lg ">

            <!-- Kopfzeile der Tabelle -->
            <div class="grid grid-cols-11 gap-x-4 bg-slate-200 rounded-t-lg font-bold text-gray-600 align-top">
                <div class="col-span-2 pl-2">
                    Name<br>
                    <input type="text" wire:model.lazy="nameFilter" class="suchFilter" placeholder="(Suche)">
                </div>
                <div class="col-span-1 text-right">
                    Personalnr
                    <input type="text" wire:model.lazy="personalnrFilter" class="suchFilter w-24 text-right" placeholder="(Suche)">


                </div>
                <div class="col-span-3">
                    Stelle<br>
                    <input type="text" wire:model.lazy="stelleFilter" class="suchFilter" placeholder="(Suche)">
                </div>
                <div class="col-span-1">Nächste<br>Beurteilung</div>
                <div class="col-span-1">Abgabedatum</div>
                <div class="col-span-3">Bemerkung</div>
            </div>

            <!-- Tabelleninhalt -->
            <div class="grid grid-cols-11 gap-x-4 gap-y-1">
                @foreach ($mitarbeiterliste as $mitarbeiter)
                    <div class="col-span-2 pl-2 @if ($mitarbeiter->ausgeschieden) line-through @endif">
                        <a href="#" wire:click.prevent="edit({{ $mitarbeiter->id }})" class="hover:underline">
                            {{ $mitarbeiter->name }}, {{ $mitarbeiter->vorname }}
                        </a>
                    </div>
                    <div class="col-span-1 text-right">
                        {{ $mitarbeiter->personalnr }}
                    </div>
                    <div class="col-span-3 truncate ...">
                        {{ $mitarbeiter->stelleBezeichnung ? $mitarbeiter->stelleBezeichnung->bezeichnung : 'keine' }}
                    </div>
                    <div class="col-span-1">{{ $mitarbeiter->nbeurteilung }}</div>
                    <div class="col-span-1">
                        @if ($mitarbeiter->abgabedatum != '0000-00-00')
                            {{ $mitarbeiter->abgabedatum }}
                        @else
                            &nbsp;
                        @endif
                    </div>
                    <div class="col-span-3 pr-2"><p class="truncate ... " title="{{ $mitarbeiter->bemerkung }}">{{ $mitarbeiter->bemerkung }}</p></div>
                @endforeach
                <div class="col-span-8 pl-2">
                    {{ $mitarbeiterliste->links() }}
                </div>
            </div>

        </div>
    </div>


    <x-my-form class="w-5/12 z-50 relative">

        <form wire:submit.prevent="save">
            @csrf
            <table class="w-full">

                <colgroup>
                    <col class="w-4/12">
                    <col class="w-8/12">
                </colgroup>

                <tbody>
                    <tr>
                        <td colspan="2" class="font-semibold">
                            Mitarbeiter ändern
                        </td>
                    </tr>
                    <tr class="">


                        <td class="">
                            <input hidden wire:model.live="id" type="text" id="id">
                            <x-input-label class="ed_label">Personalnr:</x-input-label>
                        </td>

                        <td class="">
                            <x-text-input class="ed_input"  wire:model.live="personalnr" type="text" id="personalnr" />
                        </td>
                    </tr>
                    <tr class="">

                        <td class="">
                            <x-input-label class="ed_label">Anrede:</x-input-label>
                        </td>
                        <td class="">
                            <x-text-input class="ed_input"  wire:model="anrede" type="text" id="anrede" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Vorname - Name:</x-input-label></td>
                        <td class="w-full">
                            <x-text-input class="ed_input"  wire:model="vorname" type="text" id="vorname" /> - <x-text-input class="ed_input"  wire:model="name" type="text" id="name" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Geburtsdatum:</x-input-label></td>
                        <td class="w-full">
                            <x-text-input class="ed_input"  wire:model="gebdatum" type="date" id="gebdatum" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Password:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input"  wire:model="password" type="password" id="password" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Amt:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="amt" type="text" id="amt" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Stelle:</x-input-label></td>
                        <td class="">
                            <select id="stelle" wire:model="stelle" class="ed_select">
                                <option value="">-- Bitte wählen --</option> <!-- Platzhalter für keine Auswahl -->



                                @foreach ($dataList as $item)
                                    <option value="{{ $item->id }}">{{ $item->bezeichnung }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>



                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Letzte Regelbeurteilung:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input"  wire:model="lregelbeurteilung" type="date" id="lregelbeurteilung" />
                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Letzte Sonstbeurteilung</x-input-label>
                        </td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="lsonstbeurteilung" type="date" id="lsonstbeurteilung" />
                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Nächste-Beurteilung</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="nbeurteilung" type="date" id="nbeurteilung" />
                        </td>
                    </tr>





                    <tr class="">
                        <td class=""><x-input-label class="ed_label">E-Mail:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="email" type="mail" id="email" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Anstellung - Besoldung:</x-input-label></td>
                        <td class="">
                            <input wire:model="anstellung" @if ($anstellung) checked="true" @endif
                                type="checkbox" id="anstellung"> -
                            <x-text-input class="ed_input"  wire:model="besoldung" type="text" id="besoldung" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Ausgeschieden - Vertragsende:</x-input-label></td>
                        <td class="">
                            <input wire:model="ausgeschieden" type="checkbox" id="ausgeschieden" @if ($ausgeschieden) checked="true" @endif /> -
                            <x-text-input class="ed_input" wire:model="vertragsende" type="date" id="vertragsende" />
                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Teilzeit:</x-input-label></td>
                        <td class="">

                            <input wire:model="teilzeit" @if ($teilzeit) checked="true" @endif type="checkbox" id="teilzeit" />
                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Benachrichtigt:</x-input-label></td>
                        <td class="">
                            <input wire:model="benachrichtigt" @if ($benachrichtigt) checked="true" @endif
                                type="checkbox" id="benachrichtigt">
                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Abgabedatum:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="abgabedatum" type="date" id="abgabedatum" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Berechtigung</x-input-label></td>
                        <td class="">

                            <select id="berechtigung" wire:model="berechtigung" class="ed_select">
                                <option value="">-- Bitte wählen --</option> <!-- Platzhalter für keine Auswahl -->
                                    <option value="1">Admin</option>
                                    <option value="5">Benutzer</option>

                            </select>
                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Bemerkung:</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="bemerkung" type="text" id="bemerkung" > </textarea>
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

                </tbody>
            </table>
            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-200 text-green-800">
                    {{ session('message') }}
                </div>
            @endif
        </form>
    </x-my-form>




</div>

