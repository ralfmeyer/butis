@props([ 'editFld' => null, 'editFldHeader' => 'header'])
<div class="flex fixed top-0 bg-opacity-60 item-center w-full h-full bg-slate-100 backdrop-blur-[2px] xcloak"
    x-show="showTextarea"
    x-on:click.self="showTextarea = false"
    x-on:keydown.escape.window="showTextarea = false"
    x-cloak > <!-- gesamtes Fenster backdrop-blur-[2px] -->

    <div {{ $attributes->merge(['class' => ' relative h-[70vh] w-[80vh] z-50 overflow-hidden m-auto  border-2 border-blue-100 bg-blue-200 shadow-slate-600  ring-4 ring-blue-200 rounded-md shadow-2xl']) }}
        x-data="{ isDisabled: true }"
        x-init="$watch('$wire.isModified', value => isDisabled = false);"> <!-- Abfragefenster Fenster -->

        <div class="m-2">
            <div class="flex flex-row items-center justify-between px-4 pt-4">
                <div class="text-xl font-bold">{{ $editFldHeader }}</div>
                <button type="button" @click="showTextarea = false;">
                    <x-fluentui-dismiss-square-20-o class="h-6" />
                </button>
            </div>

            <!-- Textarea absolut platziert -->
            <textarea
                class="absolute left-4 right-4 top-16 bottom-[10px] p-3 bg-blue-100 resize-none overflow-y-auto rounded-md"
                wire:model="{{ $editFld }}"
                id="{{ $editFld }}" autofocus
            ></textarea>
        </div>
    </div>
</div>
