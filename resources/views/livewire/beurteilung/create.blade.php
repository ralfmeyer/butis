<div class="mx-4" x-data="{
    activeUser: @entangle('activeUser'),
    regelbeurteilung: @entangle('regelbeurteilung'),
    showTextarea: @entangle('showTextarea'),
    beurteilungszeitpunkt: @entangle('beurteilungszeitpunkt')
}" x-cloak >


    @php
         $textBewertungFehlt = 'Bewertung fehlt:'
    @endphp


    <div class="mb-10 w-content mx-auto">
        <legend class="be_head1">Neue Beurteilung für {{ $mmitarbeiter->vorname }} {{ $mmitarbeiter->name }}</legend>
    </div>


    <form wire:submit.prevent="save">
        @csrf
        <div class="grid grid-cols-2 mx-auto mb-10 w-content gap-4">
            <div class="bc_create_border p-2 mr-4 mb-2">
                <div class="grid grid-cols-2">
                    <div class="text-left pr-2">Name, Vorname:</div>
                    <div class="text-left">{{ $mmitarbeiter->name }}, {{ $mmitarbeiter->vorname }}</div>
                </div>
            </div>

            <div class="bc_create_border p-2 mb-2 @if ($activeUser == 1) bc_create_col_active @endif">
                <div class="grid grid-cols-2">
                    <div class="text-left pr-2">Erstbeurteiler:</div>
                    <div class="text-left">{{ $mbeurteiler1->anrede }} {{ $mbeurteiler1->vorname }}
                        {{ $mbeurteiler1->name }} ({{ $mstelleB1->bezeichnung }})</div>
                </div>
            </div>

            <div class="bc_create_border p-2 mr-4 mb-2">
                <div class="grid grid-cols-2">
                    <div class="text-left pr-2">Organisationseinheit:</div>
                    <div class="text-left">{{ $mstelle->bezeichnung }}</div>
                </div>
            </div>

            <div class="bc_create_border p-2 mb-2 @if ($activeUser == 2) bc_create_col_active @endif">
                <div class="grid grid-cols-2">
                    <div class="text-left pr-2">Zweitbeurteiler:</div>
                    <div class="text-left">{{ $mbeurteiler2->anrede }} {{ $mbeurteiler2->vorname }}
                        {{ $mbeurteiler2->name }} ({{ $mstelleB2->bezeichnung }})</div>
                </div>
            </div>

            <div class="bc_create_border p-2 mr-4 mb-2">
                <div class="grid grid-cols-2">
                    <div class="text-left pr-2">Geburtsdatum:</div>
                    <div class="text-left">{{ $mmitarbeiter->gebdatum }}</div>
                </div>
            </div>

            <div class="bc_create_border p-2 mb-2">
                <div class="grid grid-cols-2">
                    <div class="text-left pr-2">Besoldungs-/Entgeltgruppe:</div>
                    <div class="text-left">{{ $mmitarbeiter->besoldung }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 mx-auto mb-2 w-content gap-4">
            <div class="bc_create_border p-2">
                <div class="flex items-center">
                    <div class="flex-initial w-1/4 -mr-2">Beurteilungszeitraum - Datum von:</div>
                    <div class="flex-grow">
                        <div class="flex flex-row items-center">
                            <input wire:model.live="zeitraumvon" type="date" class="inputdate mr-2">
                            <div>
                                @error('zeitraumvon')
                                    <span class="text-red-500 px-4">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Zeitraum bis -->
                            <div>
                                <label for="zeitraumbis">bis</label>
                                <input wire:model.live="zeitraumbis" type="date" id="zeitraumbis" class="inputdate ml-2">
                                @error('zeitraumbis')
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bc_create_border p-2 mb-2">
                <div class="flex items-center">
                    <div class="flex-initial w-1/4 -mr-2">Art der Beurteilung:</div>
                    <div class="flex-grow">
                        <div>
                            <label>
                                <input wire:model.live="regelbeurteilung" type="radio" name="art" value="1"
                                    class="be_create_radiogroup" />
                                Regelbeurteilung
                            </label>
                        </div>
                        <div>
                            <label>
                                <input wire:model.live="regelbeurteilung" type="radio" name="art" value="0"
                                    class="be_create_radiogroup">
                                Bedarfsbeurteilung
                            </label>
                        </div>
                        <div>
                            <label>
                                <input wire:model.live="regelbeurteilung" type="radio" name="art" value="2"
                                    class="be_create_radiogroup">
                                Probezeitbeurteilung
                            </label>
                        </div>
                        <div class="ml-10">
                            <label>
                                <input wire:model.live="beurteilungszeitpunkt" type="radio" name="zeitpunkt" value="0"
                                    :disabled="regelbeurteilung != 2"
                                    class="be_create_radiogroup">
                                Hälfte
                            </label>
                            <label>
                                <input wire:model.live="beurteilungszeitpunkt" type="radio" name="zeitpunkt" value="1"
                                    :disabled="regelbeurteilung != 2"
                                    class="be_create_radiogroup">
                                Ende
                            </label>

                        </div>
                    </div>
                </div>
            </div>


            <div class="bc_create_border p-2 mb-2">
                <div class="grid grid-cols-1">
                    <div class="text-left pr-2">Kurze Beschreibung des Aufgabenbereichs</div>
                        <!-- Edit Large -->
                        <div class="flex flex-row">

                                <div class="w-10">
                                    <button type="button" wire:click="doShowTextarea('aufgabenbereich')" ><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                </div>

                            <div class="flex w-full">
                                <textarea :disabled="activeUser !== 1" class="ed_input_textbox bg-transparent" wire:model="aufgabenbereich"
                                type="text" id="aufgabenbereich" rows="2"></textarea>
                            </div>

                        </div>
                        <!-- Edit Large Ende -->

                </div>
            </div>
        </div>

        <div>
            <table class="m-auto w-content " x-show="beurteilungszeitpunkt != 0">
                <tr class="be_create_k_trenner " >
                    <th colspan="2" class="be_create_k_col1 be_head1">
                        Beurteilungsmerkmale
                    </th>
                    <th class="be_create_k_col2">
                        Entspricht mit größeren Einschränkungen den Anforderungen des Statusamtes/der
                        Entgeltgruppe<br>unter 80%
                    </th>
                    <th class="be_create_k_col3">
                        Entspricht mit Einschränkungen den Anforderungen des Statusamtes/der Entgeltgruppe<br>80%
                    </th>
                    <th class="be_create_k_col4">
                        Entspricht den Anforderungen des Statusamtes/der Entgeltgruppe<br>100%
                    </th>
                    <th class="be_create_k_col5">
                        Liegt über den Anforderungen des Statusamtes/der Entgeltgruppe<br>120%
                    </th>
                </tr>




                @foreach ($details as $key => $detail)
                    @if ( !$detail['k']->fuehrungsmerkmal || (  $detail['k']->fuehrungsmerkmal === $mstelle->fuehrungskompetenz ))

                    @php
                        $red = '';
                         if ($details[$key]['w']['beurteiler1noteError']) $red = "bg-red-500 text-xl";
                    @endphp
                    <tr class="be_create_k_trenner_small {{ $red }} border-l border-r ">
                        <td rowspan="@if ($activeUser == 1) 3 @else 6 @endif" colspan="2"
                            class="be_create_k_col1 pb-10 bc_create_col_active be_create_k_trenner_blue ">
                            @if ($details[$key]['w']['beurteiler1noteError'] === true)
                                <div class="text-red-500 text-right">{{ $textBewertungFehlt }}</div>
                            @endif
                            <div class="be_create_k_head">{{ $detail['k']->ueberschrift }}</div>
                            <div class="be_create_k_text">{{ $detail['k']->text1 }}</div>
                        </td>
                        <td
                            class="be_create_k_col2 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif">
                            <label for="details.{{ $detail['k']->id }}.w.beurteiler1note_1">unter 80%</label>
                            <input type="radio" wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler1note"
                                id="details.{{ $detail['k']->id }}.w.beurteiler1note_1" value="1"
                                :disabled="activeUser !== 1" class="disabled:text-blue-400">
                        </td>
                        <td
                            class="be_create_k_col3 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif">
                            <label for="details.{{ $detail['k']->id }}.w.beurteiler1note_2">80%</label>
                            <input type="radio" wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler1note"
                                id="details.{{ $detail['k']->id }}.w.beurteiler1note_2" value="2"
                                :disabled="activeUser !== 1" class="disabled:text-blue-400">
                        </td>
                        <td
                            class="be_create_k_col4 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif">

                            <label for="details.{{ $detail['k']->id }}.w.beurteiler1note_3">100%</label>
                            <input type="radio" wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler1note"
                                id="details.{{ $detail['k']->id }}.w.beurteiler1note_3" value="3"
                                :disabled="activeUser !== 1" class="disabled:text-blue-400">
                        </td>
                        <td
                            class="be_create_k_col5 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif">
                            <div class="grid grid-cols-2">
                                <div>
                                    <label for="details.{{ $detail['k']->id }}.w.beurteiler1note_4">120%</label>
                                    <input type="radio"
                                        wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler1note"
                                        id="details.{{ $detail['k']->id }}.w.beurteiler1note_4" value="4"
                                        :disabled="activeUser !== 1" class="disabled:text-blue-400">
                                </div>
                                @if ($activeUser === 2)
                                <div class="text-right pr-2 text-sm align-middle">{{ $mbeurteiler1->vorname }}
                                    {{ $mbeurteiler1->name }} </div>
                                    @endif
                            </div>
                        </td>

                    </tr>
                    <tr class=" border-l border-r border-sky-500">
                        <td colspan="6" :disabled="activeUser !== 1"
                            class="@if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                            @if ($details[$key]['w']['beurteiler2noteError'] === true)
                                <div class="text-red-500 text-right">{{ $textBewertungFehlt }}</div>
                            @endif
                            Bemerkung zur {{ $detail['k']->ueberschrift }}
                            @if ($details[$key]['w']['beurteiler1bemerkungError'] ?? false)
                                <div
                                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-1 rounded font-semibold">
                                    Bemerkung muss angegeben werden
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr class="@if ($activeUser === 1) be_create_k_trenner_blue @endif border-l border-r border-sky-500 "  >
                        <td colspan="5"
                            class="@if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif pr-3">

                                <!-- Edit Large -->
                                <div class="flex flex-row">
                                    @if ($activeUser === 1)
                                        <div class="w-10">
                                            <button type="button" wire:click="doShowTextarea('details.{{ $detail['k']->id }}.w.beurteiler1bemerkung')" ><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                        </div>
                                    @endif
                                    <div class="flex w-full">
                                        <textarea :disabled="activeUser !== 1" class="ed_input_textbox bg-transparent "
                                        wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler1bemerkung" type="text" id="beurteiler1bemerkung"
                                        rows="2"></textarea>
                                    </div>

                                </div>
                                <!-- Edit Large Ende -->
                        </td>
                    </tr>
                    @if ($activeUser === 2)
                        <tr class="be_create_k_trenner border-l border-r border-sky-500">
                            <td
                                class="be_create_k_col2 @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif ">
                                <label for="details.{{ $detail['k']->id }}.w.beurteiler2note_1">unter 80%</label>
                                <input type="radio" wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler2note"
                                    id="details.{{ $detail['k']->id }}.w.beurteiler2note_1" value="1"
                                    :disabled="activeUser !== 2" class="disabled:text-slate-200">
                            </td>
                            <td
                                class="be_create_k_col3 @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif">
                                <label for="details.{{ $detail['k']->id }}.w.beurteiler2note_2">80%</label>
                                <input type="radio" wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler2note"
                                    id="details.{{ $detail['k']->id }}.w.beurteiler2note_2" value="2"
                                    :disabled="activeUser !== 2" class="disabled:text-slate-200">
                            </td>
                            <td
                                class="be_create_k_col4 @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif">
                                <label for="details.{{ $detail['k']->id }}.w.beurteiler2note_3">100%</label>
                                <input type="radio" wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler2note"
                                    id="details.{{ $detail['k']->id }}.w.beurteiler2note_3" value="3"
                                    :disabled="activeUser !== 2" class="disabled:text-slate-200">
                            </td>
                            <td
                                class="be_create_k_col5  @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif">
                                <div class="grid grid-cols-2">
                                    <div>
                                        <label for="details.{{ $detail['k']->id }}.w.beurteiler2note_4">120%</label>
                                        <input type="radio"
                                            wire:model.live="details.{{ $detail['k']->id }}.w.beurteiler2note"
                                            id="details.{{ $detail['k']->id }}.w.beurteiler2note_4" value="4"
                                            :disabled="activeUser !== 2" class="disabled:text-slate-200">
                                    </div>
                                    <div class="text-right pr-2 text-sm align-middle">{{ $mbeurteiler2->vorname }}
                                        {{ $mbeurteiler2->name }} </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-l border-r border-sky-500">

                            <td colspan="6" :disabled="activeUser !== 2"
                                class="@if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                                Bemerkung zur {{ $detail['k']->ueberschrift }}
                                @if ($details[$detail['k']->id]['w']['beurteiler2bemerkungError'] ?? false)
                                <div
                                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-1 rounded font-semibold">
                                    Bemerkung muss angegeben werden
                                </div>
                            @endif
                            </td>
                        </tr>
                        <tr class="border-l border-r border-sky-500">
                            <td colspan="6" class="be_create_k_trenner_blue pr-3">
                                <!-- Edit Large -->
                                <div class="flex flex-row">
                                    @if ($activeUser === 2)
                                        <div class="w-10">
                                            <button type="button" wire:click="doShowTextarea('details.{{ $detail['k']->id }}.w.beurteiler2bemerkung')" ><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                        </div>
                                    @endif
                                    <div class="flex w-full">
                                        <textarea :disabled="activeUser !== 2" class="ed_input_textbox"
                                            wire:model="details.{{ $detail['k']->id }}.w.beurteiler2bemerkung" type="text" id="beurteiler2bemerkung"
                                            rows="2"></textarea>
                                    </div>
                                </div>
                                <!-- Edit Large Ende -->
                            </td>
                        </tr>
                        @endif
                    @endif
                @endforeach
            </table>
            <table  class="m-auto w-content" >
                <tr class=" border-l border-r border-sky-500">

                    <td colspan="3"
                        class=" text-center
                            @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">

                        @if ($activeUser == 1)
                            <span class="font-bold">Gesamtnote als Beurteiler 1:&nbsp;</span>
                            @if ($gesamtnote1Error ===true)<span class="text-red-500">(fehlt)</span> @endif
                        @else
                            Gesamtnote von Beurteiler 1: {{ $mbeurteiler1->anrede }} {{ $mbeurteiler1->vorname }}
                            {{ $mbeurteiler1->name }}
                        @endif
                        <select wire:model.live="gesamtnote1" id="gesamtnote1" :disabled="activeUser !== 1"
                            class="bc_create">
                            <option value="0">bitte auswählen</option>
                            <option value="1">unter 80%</option>
                            <option value="2">80%</option>
                            <option value="3">100%</option>
                            <option value="4">120%</option>
                        </select>
                    </td>
                    <td colspan="3"
                        class=" text-center @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        @if ($activeUser == 2)
                            <span class="font-bold"> Gesamtnote von Dir als Beurteiler 2:&nbsp; </span>
                            @if ($gesamtnote2Error ===true)<span class="text-red-500">(fehlt)</span> @endif
                        @else
                            Gesamtnote von Beurteiler 2: {{ $mbeurteiler2->anrede }} {{ $mbeurteiler2->vorname }}
                            {{ $mbeurteiler2->name }}
                        @endif

                        <select wire:model.live="gesamtnote2" id="gesamtnote2" :disabled="activeUser !== 2"
                            class="bc_create">
                            <option value="0">nicht gewählt</option>
                            <option value="1">unter 80%</option>
                            <option value="2">80%</option>
                            <option value="3">100%</option>
                            <option value="4">120%</option>
                        </select>

                    </td>
                </tr>

                <tr class=" border-l border-r border-t rounded-md border-sky-500"  x-show="beurteilungszeitpunkt == 0">
                    <td colspan="5"
                        class="px-2 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        <div class="flex flex-col">
                            <div class="text-lg font-bold">Beamtenrechtliche Probezeitbeurteilung</div>
                            <div>Die Beamtin/der Beamte ist auf der Grundlage der Einschätzung aus dem beurteilten Abschnitt der Probezeit für die Übernahme in das Beamtenverhältnis auf Lebenszeit</div>
                    </div>
                    </td>
                </tr>

                <tr class=" border-l border-r border-sky-500" x-show="beurteilungszeitpunkt == 0">
                    <td colspan="3"
                        class="px-2 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        <div class="flex flex-col w-1/2 m-auto">
                            <div class="flex flex-row items-center">
                                <label>
                                    <input type="radio" wire:model="geeignet1" id="geeignet1" value="0"
                                    :disabled="activeUser !== 1" class=" disabled:text-slate-200 be_create_radiogroup"> nach heutigem Stand geeignet.
                                </label>
                            </div>
                            <div class="flex flex-row">
                                <label>
                                    <input type="radio" wire:model="geeignet1" id="geeignet1" value="1"
                                    :disabled="activeUser !== 1" class=" disabled:text-slate-200 be_create_radiogroup"> nach heutigem Stand bedingt geeignet.
                                </label>
                            </div>
                            <div class="flex flex-row">
                                <label>
                                    <input type="radio" wire:model="geeignet1" id="geeignet1" value="2"
                                    :disabled="activeUser !== 1" class=" disabled:text-slate-200 be_create_radiogroup"> nach heutigem Stand nicht geeignet.
                                </label>
                            </div>
                        </div>

                    </td>
                    <td colspan="3"
                        class="px-2 @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        <div class="flex flex-col w-1/2 m-auto">
                            <div class="flex flex-row items-center">
                                <label>
                                    <input type="radio" wire:model="geeignet2" id="geeignet1" value="0"
                                    :disabled="activeUser !== 2" class=" disabled:text-slate-200 be_create_radiogroup"> nach heutigem Stand geeignet.
                                </label>
                            </div>
                            <div class="flex flex-row">
                                <label>
                                    <input type="radio" wire:model="geeignet2" id="geeignet1" value="1"
                                    :disabled="activeUser !== 2" class=" disabled:text-slate-200 be_create_radiogroup"> nach heutigem Stand bedingt geeignet.
                                </label>
                            </div>
                            <div class="flex flex-row">
                                <label>
                                    <input type="radio" wire:model="geeignet2" id="geeignet1" value="2"
                                    :disabled="activeUser !== 2" class=" disabled:text-slate-200 be_create_radiogroup"> nach heutigem Stand nicht geeignet.
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class=" border-l border-r border-sky-500">
                    <td colspan="3"
                        class="px-2 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">

                        Begründung der Gesamtnote von Beurteiler 1:

                    </td>
                    <td colspan="3"
                        class="px-2 @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        Begründung der Gesamtnote von Beurteiler 2:
                    </td>
                </tr>
                <tr class=" border-l border-r border-sky-500">
                    <td colspan="3"
                        class="px-2 be_create_k_trenner_blue @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        @if ($gesamtnote1begruendungError ?? false)
                        <div
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-1 rounded font-semibold">
                            Begründung muss angegeben werden
                        </div>
                        @endif

                        <!-- Edit Large -->
                        <div class="flex flex-row">
                            @if ($activeUser === 1)
                                <div class="w-10">
                                    <button type="button" wire:click="doShowTextarea('gesamtnote1begruendung')"><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                </div>
                            @endif
                            <div class="flex w-full">
                                <textarea :disabled="activeUser !== 1" class="ed_input_textbox" wire:model.live="gesamtnote1begruendung" type="text"
                                    id="gesamtnote1begruendung" rows="2"></textarea>
                            </div>
                        </div>
                        <!-- Edit Large Ende -->
                    </td>
                    <td colspan="3"
                        class="px-2 be_create_k_trenner_blue @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        @if ($gesamtnote2begruendungError ?? false)
                        <div
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-1 rounded font-semibold">
                            Begründung muss angegeben werden
                        </div>
                        @endif

                        <!-- Edit Large -->
                        <div class="flex flex-row">
                            @if ($activeUser === 2)
                                <div class="w-10">
                                    <button type="button" wire:click="doShowTextarea('gesamtnote2begruendung')"><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                </div>
                            @endif
                            <div class="flex w-full">
                                <textarea :disabled="activeUser !== 2" class="ed_input_textbox" wire:model.live="gesamtnote2begruendung" type="text"
                                id="gesamtnote2begruendung" rows="2"></textarea>
                            </div>
                        </div>
                        <!-- Edit Large Ende -->

                    </td>
                </tr>
                <tr class=" border-l border-r border-sky-500">
                    <td colspan="3"
                        class="px-2 @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        Zusatzbemerkung von Beurteiler 1: <span class="text-xs">(erscheint nicht im Ausdruck)</span>
                    </td>
                    <td colspan="3"
                        class="px-2 @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif disabled:text-slate-200">
                        Zusatzbemerkung von Beurteiler 2: <span class="text-xs">(erscheint nicht im Ausdruck)</span>
                    </td>
                </tr>
                <tr class=" border-l border-r border-sky-500">
                    <td colspan="3"
                        class="px-2 be_create_k_trenner_blue @if ($activeUser == 1) bc_create_col_active @else bc_create_col_inactive @endif pr-3">
                        <!-- Edit Large -->
                        <div class="flex flex-row">
                            @if ($activeUser === 1)
                                <div class="w-10">
                                    <button type="button" wire:click="doShowTextarea('zusatz1')"><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                </div>
                            @endif
                            <div class="flex w-full">
                                <textarea :disabled="activeUser !== 1" class="ed_input_textbox" wire:model="zusatz1" type="text" id="zusatz1"
                                    rows="2"></textarea>
                            </div>
                        </div>
                        <!-- Edit Large Ende -->
                    </td>
                    <td colspan="3"
                        class="px-2 be_create_k_trenner_blue @if ($activeUser == 2) bc_create_col_active @else bc_create_col_inactive @endif">
                        <!-- Edit Large -->
                        <div class="flex flex-row">
                            @if ($activeUser === 2)
                                <div class="w-10">
                                    <button type="button" wire:click="doShowTextarea('zusatz2')"><x-fluentui-desktop-edit-24-o class="w-6 h-6"/></button>
                                </div>
                            @endif
                            <div class="flex w-full">
                                <textarea :disabled="activeUser !== 2" class="ed_input_textbox" wire:model="zusatz2" type="text" id="zusatz2"
                                    rows="2"></textarea>
                            </div>
                        </div>
                        <!-- Edit Large Ende -->

                    </td>
                </tr>
                <tr class=" border-l border-r border-sky-500">
                    <td colspan="3"
                        class="text-center px-3 be_create_k_trenner_blue
                            @if ($activeUser == 1 && $beurteiler1Abgabebereit || $activeUser == 2 && $beurteiler2Abgabebereit ) bc_create_col_active @else bc_create_col_inactive @endif"

                            >
                        <span class="text-lg font-bold text-orange-500">Mit dem Eintippen des Kommandos bestätigen Sie
                            bewußt Ihre Entscheidung</span><br>
                        <span class="font-bold">Kommando: <input type="text" wire:model="kommando"
                                class="text-center text-lg"></span><br>
                        Geben Sie das Wort "abgeschlossen" ein, um die Bewertung abzuschliessen.<br>
                        @if ($activeUser === 2)
                        Geben Sie das Wort "zurueck" ein, um die Bewertung an Beurteiler 1 zurückzugeben.<br>
                        @endif
                        <br>

                        @if ($activeUser === 1)
                            <div class="{{ $beurteiler1Abgabebereit === true ? 'text-green-600' : 'text-orange-500' }} ">
                            {{ $beurteiler1AbgabebereitText }}
                            </div>
                        @else
                            <div class="{{ $beurteiler2Abgabebereit === true ? 'text-green-600' : 'text-orange-500' }} ">
                                {{ $beurteiler2AbgabebereitText }}
                            </div>
                        @endif
                    </td>
                    <td colspan="3" class="px-2 be_create_k_trenner_blue bc_create_col_active ">
                        <span class="text-lg">Bemerkung an Beurteiler 1 bzw. an Beurteiler 2</span><br>
                        <textarea class="ed_input_textbox" wire:model="meldungBemerkung" type="text" id="meldungBemerkung" rows="2"></textarea>
                        <div class="flex flex-row">


                                <x-heroicon-m-bars-arrow-down class="w-6 mr-4"/>

                            <span class="text-sm">Hinweis: Diese Bemerkung wird nur bei der Bearbeitung unter der Beurteilung angezeigt. </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        @php

            //dd($meldungen);
        @endphp
        <div id="bemerkung" class="w-4/5 flex flex-col  mt-6 p-4 m-auto border bg-sky-100 border-sky-500 rounded-md">
            <div class="flex flex-row">
                <div class="w-full mb-4 flex flex-row items-center">
                    <x-heroicon-m-bars-3-bottom-left class="w-6 mr-2" />
                    <span>
                        <span class="text-xl font-bold mr-10">Bemerkungen der Beurteiler</span>
                        @if (!empty($meldungen) && $meldungen->count() > 0)
                            <span class="text-xs">(neueste oben)</span>
                        @endif
                    </span>
                </div>
            </div>
            @if (!empty($meldungen) && $meldungen->count() > 0)
            @foreach ($meldungen as $meldung)
            <div class="flex flex-row mb-4">
                <div class="w-1/2 @if ($meldung->mitarbeiter === $this->mbeurteiler1->id ) mr-auto @else ml-auto @endif border border-sky-400 bg-sky-200 rounded-3xl p-4 flex flex-col shadow-md shadow-gray-600">
                    <div class="flex flex-row text-sm border-b border-b-sky-500 " >
                        <div class="mr-2">
                            {{ $meldung->created_at->format('Y-d-m H:i') }}
                        </div>
                        <div>
                            @if ($meldung->mitarbeiter === $this->mbeurteiler1->id )
                            {{ $meldung->vorname }} {{ $meldung->name }} >>>>>>>>>>>>>>
                            @else
                            <<<<<<<<<<<<<< {{ $meldung->vorname }} {{ $meldung->name }}
                            @endif

                        </div>
                    </div>
                    <div class="flex flex-row text-base">
                        <div class="mr-2">
                            {{ $meldung->nachricht }}
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
            @else
            Keine Bemerkungen vorhanden
            @endif
        </div>


        <div class="relative bg-gray-100 w-4/5 h-screen overflow-y-auto">
            <!-- Langer Inhalt, der scrollbar ist -->

            <!-- Container für die beiden Buttons -->
            <div class="fixed bottom-0 right-[10%] flex space-x-4 z-10 mb-1 opacity-50 hover:opacity-100">
                <!-- Zurück-Button -->
                <button type="button" onclick="window.location.href='{{ route('beurteilung') }}'"
                    class="w-32 bg-gray-500 text-white py-2 rounded-md text-center">
                    Zurück
                </button>

                <!-- Änderungen übernehmen-Button -->
                <button type="submit" class="w-52 bg-blue-500 text-white py-2 rounded-md">
                    Änderungen übernehmen
                </button>
            </div>
        </div>


        <x-my-textarea :editFld="$activeTextarea" :editFldHeader="$this->getLabelForField($activeTextarea)" x-show="showTextarea" />






</form>


    <!-- x-my-form
       <div class="flex flex-col items-center text-3xl text-center p-12 align-middle">
           <x-heroicon-m-check-circle class="w-36 h-36"/><br>Beurteilung wurde gespeichert!
       </div>
    /x-my-form  -->

    <script>
        document.addEventListener('doErrorMessage', (message) => {
            alert(message.detail);
        });
    </script>

<script>
    window.addEventListener('beforeunload', () => {
        console.log('beforeunload wurde ausgelöst');
        document.querySelectorAll('.xcloak').forEach(el => {
            console.log('❌ .xcloak-Element gefunden und entfernt');
            el.remove(); // oder: el.style.display = 'none';
        });
    });
</script>


</div>
