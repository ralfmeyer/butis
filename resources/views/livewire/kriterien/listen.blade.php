<div class="" x-data="{ showForm: @entangle('showForm') }" x-cloak x-on:click.self="showForm = false"
    x-on:keydown.escape.window="showForm = false">

{{-- #region Formular Anfang *************************************************** --}}
    <x-my-form>
        <form wire:submit.prevent="save"  class="">
            @csrf
            <table class="table-fixed w-full">
                <colgroup>
                    <col class="w-1/5 text-right">
                    <col class="w-6/5">
                </colgroup>
                    <tr>
                        <td colspan="2" class="font-semibold">
                            Kriterium ändern
                        </td>
                    </tr>
                    <tr class="">
                        <td class="">
                            <x-input-label>Führungsmerkmal</x-input-label>
                        </td>
                        <td class="">

                            <input id="fuehrungsmerkmal" name="fuehrungsmerkmal" wire:model="fuehrungsmerkmal" type="checkbox" @if ( $fuehrungsmerkmal === 1) checked @endif />
                        </td>
                    </tr>
                    <tr class="">
                        <td class="">
                            <input hidden wire:model="id" type="text" id="id">
                            <x-input-label>Bereich</x-input-label>
                        </td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="bereich" type="text" id="bereich" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class="">
                            <x-input-label>Nummer</x-input-label>
                        </td>
                        <td class="">
                            <x-text-input class="ed_input" wire:model="nummer" type="text" id="nummer" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Überschrift</x-input-label></td>
                        <td class="">
                            <x-text-input class="ed_input w-full" wire:model="ueberschrift" type="text" id="ueberschrift" />
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Text1</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="text1" type="text" id="text1" rows="3"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Text2</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox_small" wire:model="text2" type="text" id="text2" rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Text3</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox_small" wire:model="text3" type="text" id="text3" rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Text4</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox_small" wire:model="text4" type="text" id="text4" rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Text5</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox_small" wire:model="text5" type="text" id="text5" rows="1"></textarea>
                        </td>
                    </tr>
                    <!--
                    <tr class="">
                        <td class=""><x-input-label>Art mit Min/Max</x-input-label></td>
                        <td class="">
                            <input wire:model="art" type="checkbox" id="art" min="1" max="4">
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Hinweistext1</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="hinweistext1" type="text" id="hinweistext1"
                                rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Hinweistext2</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="hinweistext2" type="text" id="hinweistext2"
                                rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Hinweistext3</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="hinweistext3" type="text" id="hinweistext3"
                                rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Hinweistext4</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="hinweistext4" type="text" id="hinweistext4"
                                rows="1"></textarea>
                        </td>
                    </tr>
                    <tr class="">
                        <td class=""><x-input-label>Hinweistext5</x-input-label></td>
                        <td class="">
                            <textarea class="ed_input_textbox" wire:model="hinweistext5" type="text" id="hinweistext5"
                                rows="1"></textarea>
                        </td>
                    </tr>
                -->
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <button type="submit" :disabled="isDisabled"
                                class="mt-2 px-4 py-2 bg-blue-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed">
                                Änderungen übernehmen
                            </button>
                        </td>
                    </tr>

            </table>
            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-200 text-green-800">
                    {{ session('message') }}
                </div>
            @endif
        </form>
    </x-my-form>
    {{-- #endregion Formular Ende ***************************************************** --}}

    {{-- #region Tabelle Anfang *************************************************** --}}
    <div class="overflow-hidden shadow-2xl ring-2 ring-black ring-opacity-5 rounded-lg bg-blue-50 w-5/6 m-auto">
        <x-my-title>
            {{ $kopfueberschrift }}
        </x-my-title>
        <div class="m-2 border border-gray-500 rounded-t-md">

            <div class="grid grid-cols-6 gap-x-4 bg-slate-200 rounded-t-md font-bold">
                <div class="col-span-2 ml-2">Bereich</div>
                <div class="">Nummer</div>
                <div class="col-span-2">Überschrift</div>
                <div class="">Art</div>

            </div>

            <div class="grid grid-cols-6 gap-x-4 gap-y-1">
                @foreach ($kriterien as $kriterium)
                    <div class="col-span-2 ml-2">

                            <div class="flex flex-row">
                                <div class="h-6 w-8 text-gray-600">
                                    @if ( $kriterium->fuehrungsmerkmal === 1)
                                        <x-heroicon-c-users role="img" class="h-6" alt="Stelle mit Führungsverantwortung" aria-label="Stelle mit Führungsverantwortung" title="Dieses Kriterium erfordert Führungsverantwortung." />
                                    @endif
                                </div>
                                <div>
                                        <a href="#" wire:click.prevent="edit({{ $kriterium->id }})">{{ $kriterium->bereich }}</a>
                                </div>
                            </div>

                    </div>
                    <div class="">{{ $kriterium->nummer }}</div>
                    <div class="col-span-2">{{ $kriterium->ueberschrift }}</div>
                    <div class="">
                        @if ($kriterium->art == 0)
                            Version 1
                        @elseif ($kriterium->art == 1)
                            V1 Überschrift
                        @else
                            Version 2
                        @endif
                    </div>

                @endforeach
            </div>
        </div>
    </div>
    {{-- #endregion Tabelle Ende **************************************************** --}}

</div>
