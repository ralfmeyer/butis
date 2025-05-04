    {{-- #region Formular Anfang *************************************************** --}}
    <div class="flex fixed top-0 bg-opacity-60 item-center w-full h-full bg-slate-100 backdrop-blur-[2px]"
        x-show="showAbgabeForm"
        x-on:click.self="showAbgabeForm = false"
        x-on:keydown.escape.window="showAbgabeForm = false"> <!-- gesamtes Fenster backdrop-blur-[2px] -->

        <div {{ $attributes->merge(['class' => 'w-5/12 m-auto  border-2 border-blue-100 bg-blue-200 shadow-slate-600  ring-4 ring-blue-200 rounded-md shadow-2xl']) }}
            x-data="{ isDisabled: true }"
            x-init="$watch('$wire.isModified', value => isDisabled = false);"> <!-- Abfragefenster Fenster -->

            <div class="m-2">
                {{ $slot->isEmpty() ? 'Saved.' : $slot }}
            </div>
        </div>
    </div>
