<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" onload="window.print();">

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">


            <main class="mt-2">

                <div class="flex flex-row justify-between">
                    <div class="text-xl w-4/6">
                        Landkreis Cloppenburg
                    </div>
                    <div class="flex flex-col border border-black  w-2/6">
                        <div class="text-xs">
                                Beurteiler/in
                        </div>
                        <div class="text-sm">
                            {{ $data['beurteiler1']->anrede }} {{ $data['beurteiler1']->vorname }}, {{ $data['beurteiler1']->name }} - {{ $data['stellebeurteiler1'] }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-row justify-between mt-0.5">
                    <div class="text-md w-4/6">
                        Der Landrat
                    </div>
                    <div class="flex flex-col border border-black  w-2/6">
                        <div class="text-xs">
                                Zweitbeurteiler
                        </div>
                        <div class="text-sm">
                            {{ $data['beurteiler2']->anrede }} {{ $data['beurteiler2']->vorname }}, {{ $data['beurteiler2']->name }} - {{ $data['stellebeurteiler2'] }}
                        </div>
                    </div>
                </div>
                <div class="w-full border-2 border-black text-center text-3xl mt-2">
                    Beurteilung
                </div>

                <div class="text-lg font-bold">
                    Beurteilung vom {{ $data['datum'] }} für {{ $data['mitarbeiter']->vorname }}, {{ $data['mitarbeiter']->name }}
                </div>

                <table class="border border-black w-full">
                    <tr class="border border-black">
                        <td colspan="2"  class="border border-black w-5/6">
                            <div class="text-xs">
                                Vorname, Name
                            </div>
                            <div >
                                {{ $data['mitarbeiter']->vorname }}, {{ $data['mitarbeiter']->name }}
                            </div>
                        </td>
                        <td class="w-1/6">
                            <div class="text-xs">
                                Geburtstag
                            </div>
                            <div>
                                {{ $data['mitarbeiter']->gebdatum }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"  class="border border-black w-5/6">
                            <div class="text-xs">
                                Amts-/Dienstbezeichnung
                            </div>
                            <div >
                                {{ $data['stellebeurteilter'] }}
                            </div>
                        </td>
                        <td class="w-1/6">
                            <div class="text-xs">
                                Besoldungs-/Vergütungsgruppe
                            </div>
                            <div>
                                {{ $data['mitarbeiter']->besoldung }}
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td  class="border border-black w-4/6">
                            <div class="text-xs">
                                Beurteilungszeitraum
                            </div>
                            <div >
                                {{ $data['zeitraumvon'] }} - {{ $data['zeitraumbis'] }}
                            </div>
                        </td>
                        <td  class="border border-black w-1/6">
                            <div class="text-xs">
                                Personalnr
                            </div>
                            <div >
                                {{ $data['mitarbeiter']->personalnr }}
                            </div>

                        </td>
                        <td class="border border-black w-1/6">
                            <div class="text-xs">
                                Datum
                            </div>
                            <div>
                                {{ $data['mitarbeiter']->besoldung }}
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black w-full">
                            <div class="text-xs">
                                Kurze Beschreibung des Aufgabenbereiches:
                            </div>
                            <div>{{ $data['aufgabenbereich'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="border border-black w-full">
                            @if ($data['anlass'] != '')
                                <div class="flex flex-col mb-2">
                                    <div class="text-xs">
                                        Anlass der Beurteilung:
                                    </div>
                                    <div>
                                        {{ $data['anlass'] }}
                                    </div>
                                </div>
                            @else
                              &nbsp;
                            @endif
                        </td>
                    </tr>
                </table>

                @if ($data['beurteilungszeitpunkt'] != 0)
                <div class="text-lg font-bold mt-4">
                    I. Beurteilungsmerkmale
                </div>
                @endif

                <div class="flex flex-col">

                    @if ($data['beurteilungszeitpunkt'] != 0)
                        <div class="flex flex-col w-full">

                            @foreach ($data['details'] as $detail)
                                @if ($detail['k']->fuehrungsmerkmal === 0 || $detail['k']->fuehrungsmerkmal === $data['mitarbeiterfuehrung'])
                                <div >
                                    <div class="flex flex-row font-bold">
                                        <div class="w-2/3">{{ $detail['k']->ueberschrift }}</div>
                                        <div class="w-1/3 text-center">

                                        @if ($data['version'] == 2)
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
                                    <div class="text-xs">
                                        {{ $detail['k']['text1'] }}
                                    </div>
                                    <div class="border-b border-black text-xs">
                                        Bemerkung: {{ $detail['w']['beurteiler2bemerkung'] }}
                                    </div>

                                </div>
                                @endif
                            @endforeach

                        </div>
                    @endif

                </div>

                <div class="text-lg font-bold mt-4">
                    II. Gesamtbeurteilung
                </div>

                <div class="text-xs font-bold">
                    Zusammenfassende Würdigung von Eignung, Befähigung und fachlicher Leistung der Mitarbeiterin/des Mitarbeiters
                </div>
                @if ($data['beurteilungszeitpunkt'] == 0)
                    <div class="font-bold text-base">
                        <br>Die Beamtin/der Beamte ist auf der Grundlage der Einschätzung aus dem beurteilten
                        Abschnitt der Probezeit für die Übernahme in das Beamtenverhältnis auf Lebenszeit:<br>
                        <div class="text-center underline ">
                            @if ($data['geeignet2'] == 0)
                                Nach heutigem Stand geeignet.
                            @elseif ($data['geeignet2'] == 1)
                                Nach heutigem Stand bedingt geeignet.
                            @elseif ($data['geeignet2'] == 2)
                                Nach heutigem Stand nicht geeignet.
                            @endif
                        </div>
                    </div>
                @endif

                @if ($data['beurteilungszeitpunkt'] != 0)
                    <div class="mt-4 flex flex-row items-center font-bold">
                        <div class="w-48 text-left">Gesamtnote:</div>
                        <div class="text-center border-4 border-black p-4">

                            @if ($data['version'] == 2)
                                @if ($data['gesamtnote2'] == 1)
                                    &lt;80%
                                @elseif ($data['gesamtnote2'] == 2)
                                    80%
                                @elseif ($data['gesamtnote2'] == 3)
                                    100%
                                @elseif ($data['gesamtnote2'] == 4)
                                    120%
                                @else
                                    nicht benotet
                                @endif
                            @else
                                @if ($data['gesamtnote2'] === -1)
                                    nicht benotet 3 ::: {{ $data['gesamtnote2'] }}
                                @else
                                    {{ $data['gesamtnote2'] }}
                                @endif
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-2 flex flex-col border border-black w-full">
                    <div class="text-xs">Begründung der Gesamtnote:</div>
                    <div class="">{{ $data['gesamtnote2begruendung'] }}</div>
                </div>


                <div class="text-lg font-bold mt-4">
                    III. Ergänzende Bemerkungen
                </div>
                <div class="h-16 border border-black w-full mt-2">
                    &nbsp;
                </div>

                <div class="flex flex-row mt-2">
                    <div class="h-16 text-xs border border-black w-1/2 mr-6 pl-2">
                        Unterschrift Erstbeurteiler/in:
                    </div>
                    <div class="h-16 text-xs border border-black w-1/2  ml-6 pl-2">
                        Unterschrift Zweitbeurteiler/in:
                    </div>
                </div>

                <div class="mt-2 flex flex-row items-center">
                    <div class="h-16 text-md w-1/2 mr-6 text-right">
                        Beurteilerkonferenz ist erfolgt am:
                    </div>
                    <div class="h-16 text-xs border border-black w-1/2  ml-6 pl-2">
                        Datum:
                    </div>
                </div>

                <div class="mt-2"> Die Beurteilung wurde am ________________ mit der Mitarbeiterin/dem Mitarbeiter besprochen.</div>

                <div class="h-16 mt-8 border border-black w-full">
                    <div class="pl-2 text-xs">Datum, Unterschrift Erst- und ggf. Zweitbeurteiler/in</div>
                </div>

                <div class="text-base font-bold mt-6">
                    Von der Beurteilung habe ich Kenntnis genommen.
                </div>


                <div class="h-16 mt-2 border border-black w-full">
                    <div class="pl-2 text-xs">Datum, Unterschrift Beurteiler/in</div>

                </div>


                <script>
                    window.addEventListener("afterprint", function() {
                        window.close();
                        // window.location.href = "{{ route('startseite') }}"; // Leitet den Benutzer nach dem Druck zurück
                    });
                </script>

            </main>
        </div>
    </body>
</html>
