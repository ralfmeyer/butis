<div class=""
    x-data="{ showForm: @entangle('showForm'), showAbgabeForm: @entangle('showAbgabeForm'), showMessage: true }"
    x-cloak
    x-on:click.self="showForm = false; showAbgabeForm = false"
    x-on:keydown.escape.window="showForm = false; showAbgabeForm = false">


    @php
        $message = session('message');
    @endphp

    @if ($message)

        <x-my-message
        x-init="
            showMessage = true;
            setTimeout(() => showMessage = false, 1000)"
        x-show="showMessage">
        <div class="flex flex-col w-5/6">
            <div class="text-3xl font-bold items-center text-slate-50">
                {{ $message }}
            </div>
        </div>
        <div class="text-right w-1/6">
            <button x-on:click="showMessage = false" class="py-2 px-4 border border-gray-600 bg-blue-500 text-white rounded-md shadow-md">
                Schließen
            </button>
        </div>
    </x-my-message>

    @endif


    @if(session('notsuccess'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
            <strong class="font-bold">Fehler! </strong>
            <span class="block sm:inline">{{ session('notsuccess') }}</span>
        </div>
    @endif




    <!-- Tabelle Anfang  *************************************************** -->
    <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50 w-5/6 m-auto z-0 relative">



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

        <div class="m-2 border border-gray-500 rounded-lg ">

            <!-- Kopfzeile der Tabelle -->
            <div class="grid grid-cols-11 gap-x-4 bg-slate-200 rounded-t-lg font-bold text-gray-600 align-top">
                <div class="col-span-2 pl-2">

                    <div class="flex flex-row items-center text-sky-600 space-x-1">
                        <div class="">
                            <a href="#" wire:click="sort('name')" class=" hover:underline">Name</a>
                        </div>
                        <div>
                            @if ($sortField === 'name' && $sortDirection === 'asc')
                                <x-fluentui-text-sort-ascending-16-o class="h-4" />
                            @elseif ($sortField === 'name' && $sortDirection === 'desc')
                            <svg class="h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                <path d="M5.46159 1.30769C5.38396 1.12137 5.20191 1 5.00007 1C4.79822 1 4.61617 1.12137 4.53854 1.30769L2.03859 7.30769C1.93238 7.56259 2.05292 7.85533 2.30782 7.96154C2.56271 8.06775 2.85544 7.94721 2.96165 7.69231L3.66684 5.99982H6.33329L7.03848 7.69231C7.14469 7.94721 7.43742 8.06775 7.69231 7.96154C7.94721 7.85533 8.06775 7.56259 7.96154 7.30769L5.46159 1.30769ZM4.08349 4.99982L5.00007 2.8L5.91664 4.99982H4.08349ZM2.50048 9.49982C2.50048 9.22367 2.72433 8.99982 3.00047 8.99982H6.50059C6.68702 8.99982 6.85797 9.10355 6.94407 9.26892C7.03017 9.43429 7.0171 9.63382 6.91019 9.78656L3.96081 13.9998H6.50039C6.77653 13.9998 7.00038 14.2237 7.00038 14.4998C7.00038 14.776 6.77653 14.9998 6.50039 14.9998H3.00047C2.81403 14.9998 2.64308 14.8961 2.55699 14.7307C2.47089 14.5653 2.48395 14.3658 2.59087 14.2131L5.54025 9.99982H3.00047C2.72433 9.99982 2.50048 9.77596 2.50048 9.49982ZM12.5 15C12.2239 15 12 14.7761 12 14.5V2.70711L10.8536 3.85355C10.6583 4.04882 10.3417 4.04882 10.1464 3.85355C9.95118 3.65829 9.95118 3.34171 10.1464 3.14645L12.1464 1.14645C12.3417 0.951184 12.6583 0.951184 12.8536 1.14645L14.8536 3.14645C15.0488 3.34171 15.0488 3.65829 14.8536 3.85355C14.6583 4.04882 14.3417 4.04882 14.1464 3.85355L13 2.70711V14.5C13 14.7761 12.7761 15 12.5 15Z" fill="currentColor"/>
                            </svg>

                            @endif
                        </div>
                    </div>
                    <input type="text" wire:model.lazy="nameFilter" class="suchFilter" placeholder="(Suche)">
                </div>
                <div class="col-span-1 text-right">
                    <div class="flex flex-row items-center text-sky-600 space-x-1">
                        <div>
                            <a href="#" wire:click="sort('personalnr')" class="text-sky-600 hover:underline">Personalnr</a>
                        </div>
                        <div>
                            @if ($sortField === 'personalnr' && $sortDirection === 'asc')
                            <x-fluentui-text-sort-ascending-16-o class="h-4" />
                            @elseif ($sortField === 'personalnr' && $sortDirection === 'desc')
                            <svg class="h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                <path d="M5.46159 1.30769C5.38396 1.12137 5.20191 1 5.00007 1C4.79822 1 4.61617 1.12137 4.53854 1.30769L2.03859 7.30769C1.93238 7.56259 2.05292 7.85533 2.30782 7.96154C2.56271 8.06775 2.85544 7.94721 2.96165 7.69231L3.66684 5.99982H6.33329L7.03848 7.69231C7.14469 7.94721 7.43742 8.06775 7.69231 7.96154C7.94721 7.85533 8.06775 7.56259 7.96154 7.30769L5.46159 1.30769ZM4.08349 4.99982L5.00007 2.8L5.91664 4.99982H4.08349ZM2.50048 9.49982C2.50048 9.22367 2.72433 8.99982 3.00047 8.99982H6.50059C6.68702 8.99982 6.85797 9.10355 6.94407 9.26892C7.03017 9.43429 7.0171 9.63382 6.91019 9.78656L3.96081 13.9998H6.50039C6.77653 13.9998 7.00038 14.2237 7.00038 14.4998C7.00038 14.776 6.77653 14.9998 6.50039 14.9998H3.00047C2.81403 14.9998 2.64308 14.8961 2.55699 14.7307C2.47089 14.5653 2.48395 14.3658 2.59087 14.2131L5.54025 9.99982H3.00047C2.72433 9.99982 2.50048 9.77596 2.50048 9.49982ZM12.5 15C12.2239 15 12 14.7761 12 14.5V2.70711L10.8536 3.85355C10.6583 4.04882 10.3417 4.04882 10.1464 3.85355C9.95118 3.65829 9.95118 3.34171 10.1464 3.14645L12.1464 1.14645C12.3417 0.951184 12.6583 0.951184 12.8536 1.14645L14.8536 3.14645C15.0488 3.34171 15.0488 3.65829 14.8536 3.85355C14.6583 4.04882 14.3417 4.04882 14.1464 3.85355L13 2.70711V14.5C13 14.7761 12.7761 15 12.5 15Z" fill="currentColor"/>
                            </svg>
                            @endif
                        </div>
                    </div>

                    <input type="text" wire:model.lazy="personalnrFilter" class="suchFilter w-24 text-right" placeholder="(Suche)">


                </div>
                <div class="col-span-3">

                    Stelle<br>
                    <input type="text" wire:model.lazy="stelleFilter" class="suchFilter" placeholder="(Suche)">
                </div>
                <div class="col-span-1">

                    <div class="flex flex-row items-center text-sky-600 space-x-1">
                        <div>
                            <a href="#" wire:click="sort('nbeurteilung')" class="text-sky-600 hover:underline">Nächste<br>Beurteilung</a>
                        </div>
                        <div>
                            @if ($sortField === 'nbeurteilung' && $sortDirection === 'asc')
                            <x-fluentui-text-sort-ascending-16-o class="h-4" />
                            @elseif ($sortField === 'nbeurteilung' && $sortDirection === 'desc')
                                <svg class="h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                    <path d="M5.46159 1.30769C5.38396 1.12137 5.20191 1 5.00007 1C4.79822 1 4.61617 1.12137 4.53854 1.30769L2.03859 7.30769C1.93238 7.56259 2.05292 7.85533 2.30782 7.96154C2.56271 8.06775 2.85544 7.94721 2.96165 7.69231L3.66684 5.99982H6.33329L7.03848 7.69231C7.14469 7.94721 7.43742 8.06775 7.69231 7.96154C7.94721 7.85533 8.06775 7.56259 7.96154 7.30769L5.46159 1.30769ZM4.08349 4.99982L5.00007 2.8L5.91664 4.99982H4.08349ZM2.50048 9.49982C2.50048 9.22367 2.72433 8.99982 3.00047 8.99982H6.50059C6.68702 8.99982 6.85797 9.10355 6.94407 9.26892C7.03017 9.43429 7.0171 9.63382 6.91019 9.78656L3.96081 13.9998H6.50039C6.77653 13.9998 7.00038 14.2237 7.00038 14.4998C7.00038 14.776 6.77653 14.9998 6.50039 14.9998H3.00047C2.81403 14.9998 2.64308 14.8961 2.55699 14.7307C2.47089 14.5653 2.48395 14.3658 2.59087 14.2131L5.54025 9.99982H3.00047C2.72433 9.99982 2.50048 9.77596 2.50048 9.49982ZM12.5 15C12.2239 15 12 14.7761 12 14.5V2.70711L10.8536 3.85355C10.6583 4.04882 10.3417 4.04882 10.1464 3.85355C9.95118 3.65829 9.95118 3.34171 10.1464 3.14645L12.1464 1.14645C12.3417 0.951184 12.6583 0.951184 12.8536 1.14645L14.8536 3.14645C15.0488 3.34171 15.0488 3.65829 14.8536 3.85355C14.6583 4.04882 14.3417 4.04882 14.1464 3.85355L13 2.70711V14.5C13 14.7761 12.7761 15 12.5 15Z" fill="currentColor"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-span-1">

                    <div class="flex flex-row items-center text-sky-600 space-x-1">
                        <div>
                            <a href="#" wire:click="sort('abgabedatum')" class="text-sky-600 hover:underline">Abgabedatum</a>
                        </div>
                        <div>
                            @if ($sortField === 'abgabedatum' && $sortDirection === 'asc')
                            <x-fluentui-text-sort-ascending-16-o class="h-4" />
                            @elseif ($sortField === 'abgabedatum' && $sortDirection === 'desc')
                                <svg class="h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                                    <path d="M5.46159 1.30769C5.38396 1.12137 5.20191 1 5.00007 1C4.79822 1 4.61617 1.12137 4.53854 1.30769L2.03859 7.30769C1.93238 7.56259 2.05292 7.85533 2.30782 7.96154C2.56271 8.06775 2.85544 7.94721 2.96165 7.69231L3.66684 5.99982H6.33329L7.03848 7.69231C7.14469 7.94721 7.43742 8.06775 7.69231 7.96154C7.94721 7.85533 8.06775 7.56259 7.96154 7.30769L5.46159 1.30769ZM4.08349 4.99982L5.00007 2.8L5.91664 4.99982H4.08349ZM2.50048 9.49982C2.50048 9.22367 2.72433 8.99982 3.00047 8.99982H6.50059C6.68702 8.99982 6.85797 9.10355 6.94407 9.26892C7.03017 9.43429 7.0171 9.63382 6.91019 9.78656L3.96081 13.9998H6.50039C6.77653 13.9998 7.00038 14.2237 7.00038 14.4998C7.00038 14.776 6.77653 14.9998 6.50039 14.9998H3.00047C2.81403 14.9998 2.64308 14.8961 2.55699 14.7307C2.47089 14.5653 2.48395 14.3658 2.59087 14.2131L5.54025 9.99982H3.00047C2.72433 9.99982 2.50048 9.77596 2.50048 9.49982ZM12.5 15C12.2239 15 12 14.7761 12 14.5V2.70711L10.8536 3.85355C10.6583 4.04882 10.3417 4.04882 10.1464 3.85355C9.95118 3.65829 9.95118 3.34171 10.1464 3.14645L12.1464 1.14645C12.3417 0.951184 12.6583 0.951184 12.8536 1.14645L14.8536 3.14645C15.0488 3.34171 15.0488 3.65829 14.8536 3.85355C14.6583 4.04882 14.3417 4.04882 14.1464 3.85355L13 2.70711V14.5C13 14.7761 12.7761 15 12.5 15Z" fill="currentColor"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-span-3 flex flex-row justify-between pt-1">
                    <div>Bemerkung</div>
                    <div class="text-xs font-normal pr-1"><label for="filterGesperrt">Ausgeschiedene anzeigen </label><input id="filterGesperrt" type="checkbox" wire:model.live="filterGesperrt"></div>
                </div>
            </div>

            <!-- Tabelleninhalt -->
            <div class="grid grid-cols-11 gap-x-4 gap-y-1">
                @foreach ($mitarbeiterliste as $mitarbeiter)

                    <div class="col-span-2 pl-2 @if ($mitarbeiter->ausgeschieden) line-through @endif">
                        <div class="flex flex-row">
                            <div class="pr-2">
                                <a href="{{ route('beurteilung.showlast', ['id' => $mitarbeiter->id]) }}"
                                    class="hover:underline flex justify-end" title="Beurteilung anzeigen">
                                    <x-carbon-document-view class="h-5 be_icon_color" />
                                </a>
                            </div>
                            <div>
                                <a href="#" wire:click.prevent="edit({{ $mitarbeiter->id }})" class="hover:underline">
                                    {{ $mitarbeiter->name }}, {{ $mitarbeiter->vorname }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1 text-right">
                        {{ $mitarbeiter->personalnr }}
                    </div>
                    <div class="col-span-3 truncate ...">
                        {{ $mitarbeiter->stelleBezeichnung ? $mitarbeiter->stelleBezeichnung->bezeichnung : 'keine' }}
                    </div>
                    <div class="col-span-1">{{ $mitarbeiter->nbeurteilung }}</div>
                    <div class="col-span-1">
                        <div class="flex flex-row items-center">
                            <div class="text-sky-600 mr-2">
                                <a href="#" wire:click.prevent="editAbgabe({{ $mitarbeiter->id }})" class="hover:underline">
                                    <x-fluentui-edit-16-o class="w-4" />
                                </a>
                            </div>
                            <div>
                                @if ($mitarbeiter->abgabedatum != '0000-00-00')
                                    {{ $mitarbeiter->abgabedatum }}
                                @else
                                    &nbsp;
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3 pr-2"><p class="truncate ... " title="{{ $mitarbeiter->bemerkung }}">{{ $mitarbeiter->bemerkung }}</p></div>
                @endforeach
                <div class="col-span-8 pl-2">
                    {{ $mitarbeiterliste->links() }}
                </div>
            </div>

        </div>
    </div>




    <x-my-abgabeform class="w-2/6 z-50 relative">
        <form wire:submit.prevent="saveAbgabe">
            @csrf
            <div class="font-bold text-2xl">
                Abgabedatum eintragen:
            </div>
            <div class="flex flex-row items-center w-full mt-3">

                    <input hidden wire:model="id" type="text" id="id1">
                    <div class=" flex flex-col m-auto">

                        <div class="">{{ $vorname }} {{ $name }} abgegeben am:&nbsp;&nbsp;
                            <x-text-input wire:model="abgabedatum" id="abgabedatum1" class="h-7" type="date" name="abgabedatum1" autofocus />
                        </div>

                    </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3">
                    Speichern
                </x-primary-button>
            </div>
        </form>
    </x-my-abgabeform>

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

                                @error('personalnr')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>
                    <tr class="">

                        <td class="">
                            <x-input-label class="ed_label">Anrede:</x-input-label>
                        </td>
                        <td class="">
                            <x-text-input class="ed_input"  wire:model="anrede" type="text" id="anrede" />

                                @error('anrede')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Vorname - Name:</x-input-label></td>
                        <td class="w-full">
                            <x-text-input class="ed_input"  wire:model="vorname" type="text" id="vorname" /> - <x-text-input class="ed_input"  wire:model="name" type="text" id="name" />
                                <div>
                                    @error('vorname')
                                        <span class="text-red-500 px-4 text-xs">Vorname: {{ $message }} </span>
                                    @enderror
                                    @error('name')
                                    <span class="text-red-500 px-4 text-xs">Name: {{ $message }}</span>
                                    @enderror
                                </div>
                            <div>
                            </div>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Geburtsdatum:</x-input-label></td>
                        <td class="w-full">
                            <x-text-input class="ed_input"  wire:model="gebdatum" type="date" id="gebdatum" />

                                @error('gebdatum')
                                    <div class="float-end">
                                        <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                    </div>
                                @enderror

                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Passwort:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input"  wire:model="password" type="password" id="password" />

                                @error('password')
                                    <div class="float-end">
                                        <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                    </div>
                                @enderror

                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Amt:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="amt" type="text" id="amt" />

                                @error('amt')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Stelle:</x-input-label></td>
                        <td class="">
                            <select id="stelle" wire:model="stelle" class="ed_select">
                                <option value="">-- Bitte wählen --</option> <!-- Platzhalter für keine Auswahl -->



                                @foreach ($dataList as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['bezeichnung'] }}</option>
                                @endforeach
                            </select>

                                @error('stelle')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>



                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Letzte Regelbeurteilung:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input"  wire:model="lregelbeurteilung" type="date" id="lregelbeurteilung" />

                                @error('lregelbeurteilung')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Letzte Sonstbeurteilung</x-input-label>
                        </td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="lsonstbeurteilung" type="date" id="lsonstbeurteilung" />

                                @error('lsonstbeurteilung')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>

                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Nächste-Beurteilung</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="nbeurteilung" type="date" id="nbeurteilung" />

                                @error('nbeurteilung')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>





                    <tr class="">
                        <td class=""><x-input-label class="ed_label">E-Mail:</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input px-2" wire:model="email" type="mail" id="email" />

                                @error('email')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Anstellung - Besoldung:</x-input-label></td>
                        <td class="">
                            <select wire:model="anstellung"  class="ed_select">
                                <option value="">-- Bitte wählen --</option>
                                @foreach($anstellungTypes as $id => $label)
                                    <option value="{{ $id }}">{{ $label }}</option>
                                @endforeach
                            </select>

                                @error('anstellung')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror
                                -
                            <x-text-input class="ed_input"  wire:model="besoldung" type="text" id="besoldung" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label class="ed_label">Ausgeschieden - Vertragsende:</x-input-label></td>
                        <td class="">
                            <input wire:model="ausgeschieden" type="checkbox" id="ausgeschieden" @if ($ausgeschieden) checked="true" @endif /> -
                            <x-text-input class="ed_input" wire:model="vertragsende" type="date" id="vertragsende" />

                                @error('vertragsende')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

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

                                @error('abgabedatum')
                                <div class="float-end">
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                </div>
                                @enderror

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
                            <div>
                                @error('berechtigung')
                                    <span class="text-red-500 px-4 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
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

</div>

