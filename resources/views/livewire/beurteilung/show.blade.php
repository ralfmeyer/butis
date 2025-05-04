<div>
    <div  class="w-4/5 print:w-full m-auto" id="oben">

    <div class="print:hidden border-b-8  p-4 text-sm  mb-5">
        <div class="flex flex-col">
            <div class="font-bold text-xl mb-4">
                Liste der Beurteilungen:
            </div>

            @if (count($beurteilungen) >0 )
                <div class="flex flex-col font-bold border-b border-sky-400 bg-sky-100">
                    <div class="flex flex-row items-center ">
                        <div class="w-2/12 p-2">
                            Vom
                        </div>
                        <div class="w-2/12 p-2">
                            Gesamtnote
                        </div>
                        <div class="w-2/12 p-2">
                            Beurteilung
                        </div>
                        <div class="w-3/12 p-2">
                            Beurteiler 1
                        </div>
                        <div class="w-3/12 p-2">
                            Beurteiler 2
                        </div>
                    </div>
                </div>
                @php
                    $first = true ;

                @endphp
                @foreach ($beurteilungen as  $be )
                <a href="#" wire:click="selectBeurteilung({{ $be->id }})">
                    <div class="flex flex-col border-t mt-1 @if ($selectedBId == $be->id) bg-sky-100 @endif hover:bg-sky-200">
                        <div class="flex flex-row items-center">
                            <div class="w-2/12">
                                {{ $be->datum}}
                            </div>
                            <div class="w-2/12">
                                {{ $be->gesamtnote2 }}
                            </div>
                            <div class="w-2/12">
                                @if ($be->regelbeurteilung == 1)
                                    Regelbeurteilung
                                @elseif ($be->regelbeurteilung == 0)
                                    <span class="text-red-500">Bedarfsbeurteilung</span>
                                @elseif ($be->regelbeurteilung == 2)
                                    Probezeitbeurteilung
                                    @if ($be->beurteilungszeitpunkt == 0)
                                    - zur Hälfte
                                    @elseif ($be->beurteilungszeitpunkt == 1)
                                    - zum Ende
                                    @elseif ($be->beurteilungszeitpunkt == -1)
                                    - nicht definiert
                                    @endif
                                    <br>Eignung:
                                    <span class="underline ">
                                        @if ($be->geeignet2 == 0)
                                            geeignet.
                                        @elseif ($be->geeignet2 == 1)
                                            bedingt geeignet.
                                        @elseif ($be->geeignet2 == 2)
                                            nicht geeignet
                                        @endif
                                    </span>
                                @endif
                            </div>
                            <div class="w-3/12">
                                {{ $be->beurteiler1_user->vorname }}, {{ $be->beurteiler1_user->name }}
                            </div>

                            <div class="w-3/12 flex flex-row items-center">

                                <div class="mr-2">
                                    {{ $be->beurteiler2_user->vorname }}, {{ $be->beurteiler2_user->name }}
                                </div>
                                @if ($first && ($be->beurteiler2 === $userId) && ($be->abgeschlossen2 === 1))
                                <button type="button"
                                class="block text-center border rounded-md border-red-800 bg-red-300 w-10 px-2 text-red-800"
                                x-data
                                @click="if (confirm('Beurteilung wirklich wieder öffnen?')) { $wire.beurteilungWiederOeffnen({{ $be->id }}) }">
                                <x-heroicon-o-lock-closed class="w-5" title="Beurteilung öffnen" />
                            </button>

                                @endif
                            </div>
                            @php
                                $first = false;
                            @endphp



                        </div>
                        @if ( !empty($be->gesamtnote2begruendung) )
                            <div class="flex flex-row items-center">
                                <div class="w-2/12 items-start">
                                    Begründung:
                                </div>
                                <div class="w-10/12">
                                    {{ $be->gesamtnote2begruendung }}
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </div>

    <div wire:loading.delay.shortest>
        <div class="z-50 w-full h-full fixed inset-0 flex items-center justify-center">
            <svg class="w-20 h-20 text-[#318ba7] animate-spin"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="100"
                    stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
    </div>



    <div class="border print:border-none  p-4 rounded">
        <div class=" mb-6 flex flex-row justify-between">
            <div class="text-lg font-bold">
            Beurteilung vom {{ $datum }} für {{ $mitarbeiter->vorname }}, {{ $mitarbeiter->name }}
            </div>
            <div class="print:hidden text-xl text-sky-600 font-bold">
                <button onclick="history.back()">zurück</button> | <button wire:click="doPrint">drucken</button>
            </div>
        </div>

        <div class="flex flex-col">

            <div class="flex flex-row">
                <div class="w-1/2 text-right mr-2">
                    Beurteiler 1:
                </div>
                <div>
                    {{ $beurteiler1->anrede }} {{ $beurteiler1->vorname }} {{ $beurteiler1->name }} -
                    @if (!empty( $beurteilung->stelleBeurteiler1 )) {{ $beurteilung->stelleBeurteiler1 }} @else @if (!empty($stelleB1)) {{ $stelleB1->bezeichnung }} @else @endif @endif
                </div>
            </div>

            <div class="flex flex-row mb-4">
                <div class="w-1/2 text-right mr-2">
                    Beurteiler 2:
                </div>
                <div>
                    {{ $beurteiler2->anrede }} {{ $beurteiler2->vorname }} {{ $beurteiler2->name }} -
                    @if (!empty( $beurteilung->stelleBeurteiler2 )) {{ $beurteilung->stelleBeurteiler2 }} @else @if (!empty($stelleB2)) {{ $stelleB2->bezeichnung }} @else @endif @endif
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
                        @elseif ($beurteilung->beurteilungszeitpunkt == 1)
                        - zum Ende
                        @elseif ($beurteilung->beurteilungszeitpunkt == -1)
                        - nicht definiert
                        @endif

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
                    @endif

                        @if ($beurteilung->bemerkung2 != '')
                            <br>Bemerkung: {{ $beurteilung->bemerkung2 }}
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
                        @if ( $detail['k']->fuehrungsmerkmal === 0 || $detail['k']->fuehrungsmerkmal === $beurteilung->mitarbeiterfuehrung )
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
                                            nicht benotet 1 :::  {{ $detail['w']['beurteiler1note'] }}
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
                                            nicht benotet 2 ::: {{ $detail['w']['beurteiler2note'] }}
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
                        @endif
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
                                    nicht benotet 3 ::: {{ $beurteilung->gesamtnote1 }}
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
                                    nicht benotet 3 ::: {{ $beurteilung->gesamtnote2 }}
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
                <div class=" my-6 flex flex-row justify-between print:hidden">
                    <div class="text-lg font-bold text-sky-600">
                        <a href="{{ route('beurteilung') }}">zurück</a>
                    </div>
                    <div class="text-lg font-bold text-sky-600">

                        <a href="#oben">nach oben</a>

                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        window.addEventListener('seiteNeuLaden', () => {
            location.reload(); // Seite neu laden
        });


        document.addEventListener("DOMContentLoaded", function () {
            Livewire.on('openPrintWindow', function (url) {
                window.open(url, '_blank', 'width=800,height=600');
            });
        });

    </script>
    </div>
