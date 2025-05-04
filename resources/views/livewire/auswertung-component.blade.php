<div>
    <!-- div class="p-6 bg-gray-100 rounded-lg space-y-4" -->
    <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50 w-5/6 m-auto">

        <x-my-title>
            {{ $kopfueberschrift }}
        </x-my-title>

        <div class="p-4">

            <!-- Filterbereich -->
            <form wire:submit.prevent="applyFilters" class="space-y-6">
                <!-- Bewertungsfilter -->
                <div class="flex flex-col space-y-2 w-full">
                    <div class="flex flex-row space-x-4 w-full">
                        <div class="w-2/12 pt-1">
                            Nur aktuell Beurteilungen:
                        </div>
                        <div class="w-1/12 pt-1">
                            <label>
                                <input type="checkbox" wire:model="nurAktuelle">

                            </label>
                        </div>
                    </div>

                    <div class="flex flex-row space-x-4 w-full">
                        <div class="w-2/12 pt-1">
                            Art der Beurteilung:
                        </div>
                        <div class="w-1/12 pt-1">
                            <label>
                                <input type="radio" wire:model="sRegelbeurteilung" value="-1">
                                Alle
                            </label>
                        </div>
                        <div class="w-1/12 pt-1">
                            <label>
                                <input type="radio" wire:model="sRegelbeurteilung" value="1">
                                Regelbeurteilung
                            </label>
                        </div>
                        <div class="w-1/12 pt-1">
                            <label>
                                <input type="radio" wire:model="sRegelbeurteilung" value="0">
                                Bedarfbeurteilung
                            </label>
                        </div>
                        <div class="flex flex-col w-3/12 border rounded p-1">

                            <div class="w-full">
                                <label>
                                    <input type="radio" wire:model="sRegelbeurteilung" value="2">
                                    Probezeit
                                </label>
                            </div>
                            <div class="flex flex-row w-full text-sm">
                                <div class="w-1/3">
                                    <label>
                                        <input type="radio" wire:model="sBeurteilungszeitpunkt" value="-99">
                                        Alle
                                    </label>
                                </div>
                                <div class="w-1/3">
                                    <label>
                                        <input type="radio" wire:model="sBeurteilungszeitpunkt" value="0">
                                        Zur Hälfte
                                    </label>
                                </div>
                                <div class="w-1/3">
                                    <label>
                                        <input type="radio" wire:model="sBeurteilungszeitpunkt" value="1">
                                        Am Ende
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- Geschlechtsfilter -->

                    <div class="flex flex-row space-x-4">
                        <div class="w-2/12">
                            Geschlecht:
                        </div>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sGeschlecht" value="all">
                            Alle
                        </label>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sGeschlecht" value="Frau">
                            Weiblich
                        </label>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sGeschlecht" value="Herr">
                            Männlich
                        </label>
                    </div>


                    <!-- Beschäftigungsart -->
                    <div class="flex flex-row space-x-4">
                        <div class="w-2/12">
                            Beschäftigungsart:
                        </div>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sTeilzeit" value="-1">
                            Alle
                        </label>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sTeilzeit" value="0">
                            Vollzeit
                        </label>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sTeilzeit" value="1">
                            Teilzeit
                        </label>
                    </div>

                    <!-- Führungskompetenz-Filter -->
                    <div class="flex flex-row space-x-4">
                        <div class="w-2/12">
                            Führungskompetenz:
                        </div>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sFuehrungskompetenz" value="-1">
                            Alle
                        </label>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sFuehrungskompetenz" value="1">
                            Ja
                        </label>
                        <label class="w-1/12">
                            <input type="radio" wire:model="sFuehrungskompetenz" value="0">
                            Nein
                        </label>
                    </div>


                    <!-- Zeitrahmen -->
                    <div class="flex flex-row w-full items-center space-x-3">
                        <div class="w-2/12">
                            Im Zeitraum von:
                        </div>
                        <div class="w-1/12">
                            <input type="date" wire:model="startDate" class="border rounded-md px-2 py-1 w-full">
                        </div>

                        <div class="w-1/12">Bis zum: </div>
                        <div class="w-1/12">
                            <input type="date" wire:model="endDate" class="border rounded-md px-2 py-1 w-full">
                        </div>

                    </div>

                    <!-- Abschnitte: Ebenentiefe und Grunddaten -->
                    <div class="flex flex-row w-full space-x-4">
                        <div class="flex flex-col w-1/2 space-y-2">
                            <h2 class="font-semibold text-lg">1. Ebenentiefe und Grunddaten</h2>
                            <div>
                                <div class="">Ebenen: </div>
                                <div class="border border-gray-600 rounded-md p-1 bg-gray-50 h-24 w-full">
                                    <div class="w-full overflow-y-auto h-full">
                                        @foreach ($sEbenen as $ebene)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="selectedEbenen"
                                                    value="{{ $ebene['id'] }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span>{{ $ebene['name'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="">Stellen: </div>
                                <div class="border border-gray-600 rounded-md p-1 bg-gray-50 h-24 w-full">
                                    <div class="w-full overflow-y-auto h-full">
                                        @foreach ($sStellen as $stelle)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="selectedStellen"
                                                    value="{{ $stelle['id'] }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span>{{ $stelle['name'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="">Beurteilerinnen/Beurteiler: </div>
                                <div class="border border-gray-600 rounded-md p-1 bg-gray-50 h-24 w-full">
                                    <div class="w-full overflow-y-auto h-full">
                                        @foreach ($sBeurteiler as $beurteiler)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="selectedBeurteiler"
                                                    value="{{ $beurteiler['id'] }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span>{{ $beurteiler['name'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col w-1/2 space-y-2">
                            <h2 class="font-semibold text-lg">2. Mitarbeiterauswahl verfeinern</h2>
                            <div>
                                <div class="">Anstellungsart: </div>
                                <div class="border border-gray-600 rounded-md p-1 bg-gray-50 h-24 w-full">
                                    <div class="w-full overflow-y-auto h-full">
                                        @foreach ($sAnstellungsart as $anstellungsart)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="selectedAnstellungsarten"
                                                    value="{{ $anstellungsart['id'] }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span>{{ $anstellungsart['name'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="">Besoldung: </div>
                                <div class="border border-gray-600 rounded-md p-1 bg-gray-50 h-24 w-full">
                                    <div class="w-full overflow-y-auto h-full">
                                        @foreach ($sBesoldung as $besoldung)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="selectedBesoldungen"
                                                    value="{{ $besoldung['id'] }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span>{{ $besoldung['name'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="">Beurteilte/r: </div>
                                <div class="border border-gray-600 rounded-md p-1 bg-gray-50 h-24 w-full">
                                    <div class="w-full overflow-y-auto h-full">
                                        @foreach ($sBeurteilter as $beurteilter)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="selectedBeurteilter"
                                                    value="{{ $beurteilter['id'] }}"
                                                    class="form-checkbox h-5 w-5 text-blue-600">
                                                <span>{{ $beurteilter['name'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex space-x-4">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Ausführen</button>

                    </div>
            </form>
        </div>

        @if ($beurteilungen)
        <div class="flex flex-col space-x-2 space-y-2 mt-6">
            <div class="flex flex-row items-center">
                <div>
                    {{ count($beurteilungen) }} Ergebnisse
                </div>
                <div>
                    <button type="button" wire:click="downloadCSV"
                                class="ml-4 px-4 py-1 bg-gray-500 text-white rounded-lg">Exportieren</button>
                </div>

            </div>
            <div class="flex flex-row space-x-2 border-b border-gray-500 bg-blue-100">
                <div class="w-2/12">
                    Name
                </div>
                <div class="w-2/12">
                    Vorname
                </div>
                <div class="w-2/12">
                    Stelle
                </div>
                <div class="w-2/12">
                    Amt
                </div>
                <div class="w-2/12">
                    Besoldung
                </div>
                <div class="w-2/12">
                    Anstellung
                </div>
                <div class="w-1/12">
                    Gesamtnote
                </div>



            </div>

                @foreach ($beurteilungen as $beurteilung)
                <div class="flex flex-row space-y-2">
                    <div class="w-2/12">
                        {{ $beurteilung->mitarbeiter->name }}
                    </div>
                    <div class="w-2/12">
                        {{ $beurteilung->mitarbeiter->vorname }}
                    </div>
                    <div class="w-2/12">
                        {{ $beurteilung->stelle->bezeichnung }}
                    </div>
                    <div class="w-2/12">
                        {{ $beurteilung->amt }}
                    </div>
                    <div class="w-2/12">
                        {{ $beurteilung->mitarbeiter->besoldung }}
                    </div>
                    <div class="w-2/12">
                        {{ $beurteilung->anstellungStr() }}
                    </div>
                    <div class="w-1/12">
                        {{ $beurteilung->gesamtNoteStr() }}
                    </div>
                </div>
                @endforeach


        </div>
        @endif

      <div class="hidden">

            {{ $sql }}


        <div>



    </div>




</div>
