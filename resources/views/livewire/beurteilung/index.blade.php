<div class="w-content m-auto">
@php
    use App\Models\BeurtStatus;

@endphp

    @if(session('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-auto mt-4 w-1/2"
        role="alert"
    >
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif


    <div class="be_show_abschnitt">
        <h2 class="be_show_abschnitt_header">
            <span class="text-xs">1.</span> Zur Beurteilung fällige Mitarbeiter als Beurteiler 1
            <span class="text-sm">
                @if (count($faelligeMitarbeiterUnterbeurteiler1) == 0) - Keine fälligen Beurteilungen vorhanden. -
                @else
                    ( {{ count($faelligeMitarbeiterUnterbeurteiler1) }} )
                @endif
            </span>
        </h2>
        @if (count($faelligeMitarbeiterUnterbeurteiler1) > 0)
            <table class="betable">
                <tr>
                    <th class="be_col1">Zustand B-1</th>
                    <th class="be_col1">Zustand B-2</th>
                    <th class="be_col1">&nbsp;</th>
                    <th class="be_col2">Name, Vorname</th>
                    <th class="be_col1 text-left">Personalnr.</th>
                    <th class="be_col1 text-left">N-Beurteilung</th>
                    <th class="be_col5">Bemerkung</th>
                </tr>
                @foreach ($faelligeMitarbeiterUnterbeurteiler1 as $mitarbeiter)
                    <tr class="hover:bg-sky-100">
                        <td class="be_col1 " align="center">
                            <div><x-beurteilung-status :status="BeurtStatus::none" /> </div>
                        </td>

                        <td class="be_col1 " align="center">
                            <div><x-beurteilung-status :status="BeurtStatus::none" /> </div>
                        </td>
                        <td class="be_col1">
                            <a href="{{ route('beurteilung.create', ['mid' => $mitarbeiter->id]) }}"
                                class="hover:underline flex justify-end"
                                title="Beurteilung für {{ $mitarbeiter->anrede }} {{ $mitarbeiter->name }} anlegen.">
                                <x-heroicon-o-document-plus class="h-5 be_icon_color" />
                            </a>
                        </td>
                        <td class="be_col2">{{ $mitarbeiter->name }} {{ $mitarbeiter->vorname }}</td>
                        <td class="be_col1">{{ $mitarbeiter->personalnr }}</td>
                        <td class="be_col1">{{ $mitarbeiter->nbeurteilung }}</td>
                        <td class="be_col5">{{ $mitarbeiter->bemerkung }}</td>
                    </tr>
                @endforeach
            </table>

        @endif
    </div>

    <div class="be_show_abschnitt">
        <h2 class="be_show_abschnitt_header">
            <span class="text-xs">2.</span> Mitarbeiter mit offenen Beurteilungen als Beurteiler 1
            <span class="text-sm">
                @if (count($aktiveBeurteilungenBeurteiler1) == 0) - Keine offenen Beurteilungen vorhanden. -
                @else
                    ( {{ count($aktiveBeurteilungenBeurteiler1) }} )
                @endif
            </span>
        </h2>
        @if (count($aktiveBeurteilungenBeurteiler1) > 0)
            <table class="betable">
                <tr>
                    <th class="be_col1">Zustand B-1</th>
                    <th class="be_col1">Zustand B-2</th>
                    <th class="be_col1">&nbsp;</th>
                    <th class="be_col2">Name, Vorname</th>
                    <th class="be_col1 text-left">Personalnr.</th>
                    <th class="be_col1 text-left">N-Beurteilung</th>
                    <th class="be_col5">Bemerkung</th>
                </tr>
                @foreach ($aktiveBeurteilungenBeurteiler1 as $beurteilung)

                    <tr class="hover:bg-sky-100">
                        <td class="be_col1 " align="center">
                            <div><x-beurteilung-status :status="BeurtStatus::edit" /> </div>
                        </td>

                        <td class="be_col1 " align="center">
                            <div><x-beurteilung-status :status="BeurtStatus::wait" /> </div>
                        </td>
                        <td class="be_col1">
                            <a href="{{ route('beurteilung.create', ['mid' => $beurteilung->mMitarbeiter->id]) }}"
                                class="hover:underline flex justify-end"
                                title="Beurteilung von {{ $mitarbeiter->anrede }} {{ $mitarbeiter->name }} bearbeiten.">
                                <x-heroicon-o-document-plus class="h-5 be_icon_color" />

                            </a>
                        </td>
                        <td class="be_col2">{{ $beurteilung->mMitarbeiter->name }}, {{ $beurteilung->mMitarbeiter->vorname }}</td>
                        <td class="be_col1">{{ $beurteilung->mMitarbeiter->personalnr }}</td>
                        <td class="be_col1">{{ $beurteilung->mMitarbeiter->nbeurteilung }}</td>
                        <td class="be_col5">{{ $beurteilung->mMitarbeiter->bemerkung }}</td>
                    </tr>
                @endforeach
            </table>

    @endif
    </div>

    <div class="be_show_abschnitt">
        <h2 class="be_show_abschnitt_header">
            <span class="text-xs">3.</span> Alle Mitarbeiter in meinem Zuständigkeitsbereich als Beurteiler 1
            <span class="text-sm">
                @if (count($mitarbeiterUnterbeurteiler1) == 0) - Keine Mitarbeiter in meinem Zuständigkeitsbereich vorhanden. -
                @else
                ( {{ count($mitarbeiterUnterbeurteiler1) }} )
                @endif
            </span>
        </h2>
        @if (count($mitarbeiterUnterbeurteiler1) > 0)
        <table class="betable">

            <tr>
                <th class="be_col1">Zustand B-1</th>
                <th class="be_col1">Zustand B-2</th>
                <th class="be_col1">&nbsp;</th>
                <th class="be_col2">Name, Vorname</th>
                <th class="be_col1 text-left">Personalnr.</th>
                <th class="be_col1 text-left">N-Beurteilung</th>
                <th class="be_col5">Bemerkung</th>
            </tr>
            @foreach ($mitarbeiterUnterbeurteiler1 as $mitarbeiter)
                <tr class="hover:bg-sky-100">
                    <td class="be_col1 " align="center">
                        <div><x-beurteilung-status :status="BeurtStatus::none" /> </div>
                    </td>

                    <td class="be_col1 " align="center">
                        <div><x-beurteilung-status :status="BeurtStatus::none" /> </div>
                    </td>
                    <td class="be_col1">
                        @if (false)
                        <a href="{{ route('beurteilung.show', ['id' => $mitarbeiter->getLastBeurteilungID($mitarbeiter->id)]) }}"
                            class="hover:underline flex justify-end" title="Beurteilung anzeigen">
                            <x-carbon-document-view class="h-5 be_icon_color" />
                        </a>
                        @endif
                    </td>
                    <td class="be_col2">{{ $mitarbeiter->name }} {{ $mitarbeiter->vorname }}</td>
                    <td class="be_col1">{{ $mitarbeiter->personalnr }}</td>
                    <td class="be_col1">{{ $mitarbeiter->nbeurteilung }}</td>
                    <td class="be_col5">{{ $mitarbeiter->bemerkung }}</td>
                </tr>
            @endforeach
        </table>
        @endif
    </div>

    <div class="be_show_abschnitt">
        <h2 class="be_show_abschnitt_header">
            <span class="text-xs">4.</span> Alle Mitarbeiter in allen Ebenen unter meinem Zuständigkeitsbereich
            <span class="text-sm">
                @if (count($mitarbeiterAllerEbenenUnterBeurteiler1) == 0) - Keine Mitarbeiter gefunden. -
                @else
                ( {{ count($mitarbeiterAllerEbenenUnterBeurteiler1) }} )
                @endif
            </span>
        </h2>

        @if (count($mitarbeiterAllerEbenenUnterBeurteiler1) > 0)
            <table class="betable">
                <tr>
                    <th class="be_col1">Zustand B-1</th>
                    <th class="be_col1">Zustand B-2</th>
                    <th class="be_col1">&nbsp;</th>
                    <th class="be_col2">Name, Vorname</th>
                    <th class="be_col1 text-left">Personalnr.</th>
                    <th class="be_col1 text-left">N-Beurteilung</th>
                    <th class="be_col5">Bemerkung</th>
                </tr>
                @foreach ($mitarbeiterAllerEbenenUnterBeurteiler1 as $mitarbeiter)

                    <tr class="hover:bg-sky-100">
                        <td class="be_col1 " align="center">
                            <div><x-beurteilung-status :status="$mitarbeiter->TextB1Status()" /> </div>
                        </td>

                        <td class="be_col1 " align="center">
                            <div><x-beurteilung-status :status="$mitarbeiter->TextB2Status()" /> </div>
                        </td>
                        <td class="be_col1">
                            @if ($mitarbeiter->beurteilung)
                            <a href="{{ route('beurteilung.show', ['id' => $mitarbeiter->getLastBeurteilungID($mitarbeiter->id)]) }}"
                                class="hover:underline flex justify-end" title="Beurteilung anzeigen">
                                <x-carbon-document-view class="h-5 be_icon_color" />
                            </a>
                            @endif
                        </td>
                        <td class="be_col2">{{ $mitarbeiter->name }} {{ $mitarbeiter->vorname }}</td>
                        <td class="be_col1">{{ $mitarbeiter->personalnr }}</td>
                        <td class="be_col1">{{ $mitarbeiter->nbeurteilung }}</td>
                        <td class="be_col5">{{ $mitarbeiter->bemerkung }}</td>
                    </tr>
                @endforeach
            </table>


        @endif
    </div>

    <!-- REGION Beurteiler 2 -->


    <div class="be_show_abschnitt">
        <h2 class="be_show_abschnitt_header">
            <span class="text-xs">5.</span> Aktive Beurteilungen von Beurteiler 2

            <span class="text-sm">
                @if (count($aktiveBeurteilungenBeurteiler2)  == 0) - Keine aktiven Beurteilungen vorhanden. -
                @else
                ( {{ count($aktiveBeurteilungenBeurteiler2) }} )
                @endif
            </span>
        </h2>
            @if (count($aktiveBeurteilungenBeurteiler2) > 0)
                <table class="betable">
                    <tr>
                        <th class="be_col1">Zustand B-1</th>
                        <th class="be_col1">Zustand B-2</th>
                        <th class="be_col1">&nbsp;</th>
                        <th class="be_col2">Name, Vorname</th>
                        <th class="be_col1 text-left">Personalnr.</th>
                        <th class="be_col1 text-left">N-Beurteilung</th>
                        <th class="be_col5">Bemerkung</th>
                    </tr>
                    @foreach ($aktiveBeurteilungenBeurteiler2 as $beurteilung)
                        <tr class="hover:bg-sky-100">
                            <td class="be_col1 " align="center">
                                <div><x-beurteilung-status :status="$beurteilung->B1Status()" /> </div>
                            </td>

                            <td class="be_col1 " align="center">
                                <div><x-beurteilung-status :status="$beurteilung->B2Status()" /> </div>
                            </td>
                            <td class="be_col1">
                                <a href="{{ route('beurteilung.show', ['id' => $beurteilung->id]) }}"
                                    class="hover:underline flex justify-end" title="Beurteilung anzeigen">
                                    <x-carbon-document-view class="h-5 be_icon_color" />
                                </a>
                            </td>
                            <td class="be_col2">{{ $beurteilung->mMitarbeiter->name }}, {{ $beurteilung->mMitarbeiter->vorname }}</td>
                            <td class="be_col1">{{ $beurteilung->mMitarbeiter->personalnr }}</td>
                            <td class="be_col1">{{ $beurteilung->mMitarbeiter->nbeurteilung }}</td>
                            <td class="be_col5">{{ $beurteilung->mMitarbeiter->bemerkung }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

    </div>

    <div class="be_show_abschnitt my-20">
        <h2 class="be_show_abschnitt_header">
            <span class="text-xs">6.</span> Alle Mitarbeiter unter Beurteiler 2 <span class="text-sm">
                @if (count($mitarbeiterUnterbeurteiler2) == 0) - Keine Mitarbeiter gefunden. -
                @else
                ( {{ count($mitarbeiterUnterbeurteiler2) }} )
                @endif
            </span>
        </h2>

        @if (count($mitarbeiterUnterbeurteiler2) > 0)
            <table class="betable">
                <tr>
                    <th class="be_col1">Zustand B-1</th>
                    <th class="be_col1">Zustand B-2</th>
                    <th class="be_col1">&nbsp;</th>
                    <th class="be_col2">Name, Vorname</th>
                    <th class="be_col1 text-left">Personalnr.</th>
                    <th class="be_col1 text-left">N-Beurteilung</th>
                    <th class="be_col5">Bemerkung</th>
                </tr>

            @foreach ($mitarbeiterUnterbeurteiler2 as $mitarbeiter)

                <tr class="hover:bg-sky-100">
                    <td class="be_col1 " align="center">
                        <div><x-beurteilung-status :status="$mitarbeiter->TextB1Status()" /> </div>
                    </td>

                    <td class="be_col1 " align="center">
                        <div><x-beurteilung-status :status="$mitarbeiter->TextB2Status()" /> </div>
                    </td>
                    <td class="be_col1">
                        @if ($mitarbeiter->beurteilung)
                        <a href="{{ route('beurteilung.show', ['id' => $mitarbeiter->getLastBeurteilungID($mitarbeiter->id)]) }}"
                            class="hover:underline flex justify-end" title="Beurteilung anzeigen">
                            <x-carbon-document-view class="h-5 be_icon_color" />
                        </a>
                        @endif
                    </td>
                    <td class="be_col2">{{ $mitarbeiter->name }} {{ $mitarbeiter->vorname }}</td>
                    <td class="be_col1">{{ $mitarbeiter->personalnr }}</td>
                    <td class="be_col1">{{ $mitarbeiter->nbeurteilung }}</td>
                    <td class="be_col5">{{ $mitarbeiter->bemerkung }}</td>
                </tr>
            @endforeach

        @endif
    </div>


</div>
