<div>
    <div  class="w-4/5 m-auto" id="oben">
    <div class="border p-4 rounded">
        <div class=" mb-6 flex flex-row justify-between">
            <div class="text-lg font-bold">
            Beurteilung vom {{ $datum }} für {{ $mitarbeiter->vorname }}, {{ $mitarbeiter->name }}
            </div>
            <div>
                <a href="{{ route('beurteilung') }}">zurück</a>
            </div>
        </div>

        <div class="flex flex-col">

            <div class="flex flex-row">
                <div class="w-1/2 text-right mr-2">
                    Beurteiler 1:
                </div>
                <div>
                    {{ $beurteiler1->anrede }} {{ $beurteiler1->vorname }} {{ $beurteiler1->name }} -
                    {{ $beurteiler1->stelle }} {{ $stelleB1->bezeichnung }}
                </div>
            </div>

            <div class="flex flex-row mb-4">
                <div class="w-1/2 text-right mr-2">
                    Beurteiler 2:
                </div>
                <div>
                    {{ $beurteiler2->anrede }} {{ $beurteiler2->vorname }} {{ $beurteiler2->name }} -
                    {{ $beurteiler2->stelle }} {{ $stelleB2->bezeichnung }}
                </div>
            </div>

            <div class="flex flex-row mb-2">
                <div class="w-1/2 text-right mr-2">
                    Vorname, Name:
                </div>
                <div>
                    {{ $mitarbeiter->vorname }}, {{ $mitarbeiter->name }}
                    @if (!$mitarbeiter->fuehrungskompetenz)
                        <br>(ohne Führungsverantwortung)
                    @endif

                </div>
            </div>

            <div class="flex flex-row mb-2">
                <div class="w-1/2 text-right mr-2">
                    Amts-/Dienstbezeichnung:
                </div>
                <div>
                    {{ $beurteilung->stellebeurteilter }}
                </div>
            </div>

            <div class="flex flex-row mb-2">
                <div class="w-1/2 text-right mr-2">
                    Besoldung:
                </div>
                <div>
                    {{ $mitarbeiter->besoldung }}
                </div>
            </div>

            <div class="flex flex-row">
                <div class="w-1/2 text-right mr-2">
                    Beurteilungszeitraum - Datum von:
                </div>
                <div>
                    {{ $beurteilung->zeitraumvon }}
                </div>
            </div>

            <div class="flex flex-row mb-2">
                <div class="w-1/2 text-right mr-2">
                    Datum bis:
                </div>
                <div>
                    {{ $beurteilung->zeitraumbis }}
                </div>
            </div>

            <div class="flex flex-row mb-2">
                <div class="w-1/2 text-right mr-2">
                    Kurze Beschreibung des Aufgabenbereichs:
                </div>
                <div>
                    {{ $beurteilung->aufgabenbereich }}
                </div>
            </div>

            <div class="flex flex-row mb-2">
                <div class="w-1/2 text-right mr-2">
                    Beurteilungsart:
                </div>
                <div class="w-1/2">
                    @if ($beurteilung->regelbeurteilung == 1)
                        Regelbeurteilung
                    @elseif ($beurteilung->regelbeurteilung == 0)
                        <span class="text-red-500">Bedarfsbeurteilung</span>
                    @elseif ($beurteilung->regelbeurteilung == 2)
                        Probezeitbeurteilung
                        @if ($beurteilung->beurteilungszeitpunkt == 0)
                            - zur Hälfte
                            <br>Die Beamtin/der Beamte ist auf der Grundlage der Einschätzung aus dem beurteilten
                            Abschnitt der Probezeit für die Übernahme in das Beamtenverhältnis auf Lebenszeit:<br>
                            <span class="underline ">
                                @if ($beurteilung->geeignet2 == 0)
                                    Nach heutigem Stand geeignet.
                                @elseif ($beurteilung->geeignet2 == 1)
                                    Nach heutigem Stand bedingt geeignet.
                                @elseif ($beurteilung->geeignet2 == 2)
                                    Nach heutigem Stand nicht geeignet.
                                @endif
                            </span>
                        @elseif ($beurteilung->beurteilungszeitpunkt == 1)
                            - zum Ende
                        @endif
                        @if ($beurteilung->bemerkung2 != '')
                            <br>Bemerkung: {{ $beurteilung->bemerkung2 }}
                        @endif
                    @endif
                </div>
            </div>

            @if ($beurteilung->anlass != '')
                <div class="flex flex-row mb-2">
                    <div class="w-1/2 text-right mr-2">
                        Anlass der Beurteilung:
                    </div>
                    <div>
                        {{ $beurteilung->anlass }}
                    </div>
                </div>
            @endif

            @if ($beurteilung->beurteilungszeitpunkt != 0)
                <div class="flex flex-col   w-full ">
                    <div class="flex flex-row font-bold border-b border-sky-400 bg-sky-100">
                        <div class="w-1/3 p-2">Merkmal</div>
                        <div class="w-1/3 p-2 text-center">Note Beurteiler 1 ({{ $beurteiler1->anrede }} {{ $beurteiler1->name }})
                        </div>
                        <div class="w-1/3 p-2  text-center">Note Beurteiler 2 ({{ $beurteiler2->anrede }} {{ $beurteiler2->name }})
                        </div>
                    </div>


                    @foreach ($details as $detail)
                        <div class="border-b border-sky-400">
                            <div class="flex flex-row mb-2">
                                <div class="w-1/3 px-2">
                                    {{ $detail['k']->ueberschrift }}
                                </div>
                                <div class="w-1/3 text-center">
                                    @if ($version == 2)
                                        @if ($detail['w']['beurteiler1note'] == 1)
                                            &lt;80%
                                        @elseif ($detail['w']['beurteiler1note'] == 2)
                                            80%
                                        @elseif ($detail['w']['beurteiler1note'] == 3)
                                            100%
                                        @elseif ($detail['w']['beurteiler1note'] == 4)
                                            120%
                                        @else
                                            nicht benotet

                                        @endif
                                    @else

                                        @if ($detail['w']['beurteiler1note'] === -1)
                                            nicht benotet
                                        @else
                                            {{ $detail['w']['beurteiler1note'] }}
                                        @endif

                                    @endif
                                </div>
                                <div class="w-1/3 text-center">
                                    @if ($version == 2)
                                        @if ($detail['w']['beurteiler2note'] == 1)
                                            &lt;80%
                                        @elseif ($detail['w']['beurteiler2note'] == 2)
                                            80%
                                        @elseif ($detail['w']['beurteiler2note'] == 3)
                                            100%
                                        @elseif ($detail['w']['beurteiler2note'] == 4)
                                            120%
                                        @else
                                            nicht benotet
                                        @endif
                                    @else
                                        @if ($detail['w']['beurteiler2note'] === -1)
                                            nicht benotet
                                        @else
                                            {{ $detail['w']['beurteiler2note'] }}
                                        @endif
                                    @endif
                                </div>
                            </div>

                            @if (trim($detail['w']['beurteiler1bemerkung']) != '' || trim($detail['w']['beurteiler2bemerkung']) != '')
                                <div class="flex flex-row py-2 border-b">
                                    <div class="w-1/3 text-right p-2  border-t border-r border-sky-200">
                                        @if ($version == 2)
                                            Begründung:
                                        @else
                                            Begründung bei Abweichung von den Noten 3 und 4:
                                        @endif
                                    </div>
                                    <div class="w-1/3 border-t border-r border-sky-200 p-2">
                                        {{ $detail['w']['beurteiler1bemerkung'] }}
                                    </div>
                                    <div class="w-1/3 border-t border-sky-200 p-2">
                                        {{ $detail['w']['beurteiler2bemerkung'] }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="flex flex-row py-2 border-t border-sky-400 bg-sky-100 font-bold">
                        <div class="w-1/3 text-left px-2 pr-4">Gesamtnote</div>
                        <div class="w-1/3 text-center">
                            @if ($version == 2)
                                @if ($beurteilung->gesamtnote1 == 1)
                                    &lt;80%
                                @elseif ($beurteilung->gesamtnote1 == 2)
                                    80%
                                @elseif ($beurteilung->gesamtnote1 == 3)
                                    100%
                                @elseif ($beurteilung->gesamtnote1 == 4)
                                    120%
                                @else
                                    nicht benotet
                                @endif
                            @else
                                @if ($beurteilung->gesamtnote1 === -1)
                                    nicht benotet
                                @else
                                    {{ $beurteilung->gesamtnote1 }}
                                @endif
                            @endif
                        </div>
                        <div class="w-1/3 text-center">
                            @if ($version == 2)
                                @if ($beurteilung->gesamtnote2 == 1)
                                    &lt;80%
                                @elseif ($beurteilung->gesamtnote2 == 2)
                                    80%
                                @elseif ($beurteilung->gesamtnote2 == 3)
                                    100%
                                @elseif ($beurteilung->gesamtnote2 == 4)
                                    120%
                                @else
                                    nicht benotet
                                @endif
                            @else
                                @if ($beurteilung->gesamtnote2 === -1)
                                    nicht benotet
                                @else
                                    {{ $beurteilung->gesamtnote2 }}
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            @endif
            <div class="flex flex-row py-2 border-t border-sky-400 bg-sky-100 ">
                <div class="w-1/3 text-right p-2 border-r border-sky-400">
                    Begründung der Gesamtnote:
                </div>
                <div class="w-1/3 text-left p-2 border-r border-sky-400">
                    {{ $beurteilung->gesamtnote1begruendung }}
                </div>
                <div class="w-1/3 text-left p-2">
                    {{ $beurteilung->gesamtnote2begruendung }}
                </div>
            </div>
            @if ($beurteilung->beurteilungszeitpunkt != 0)
                <div class=" my-6 flex flex-row justify-between">
                    <div class="">
                        <a href="{{ route('beurteilung') }}">zurück</a>
                    </div>
                    <div>

                        <a href="#oben">nach oben</a>

                    </div>
                </div>
            @endif

        </div>
    </div>
    </div>
