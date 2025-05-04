<div>
    <div class="w-3/12 m-auto z-0 relative">
        <form wire:submit="update">
            <!-- Email Address -->
            <div class="flex flex-row items-center">
                <div class="w-1/3 mr-2 text-right">
                    <x-input-label for="personalnr" :value="__('auth.personalnr')" />
                </div>
                <div class="w-2/3">
                    <div class="flex flex-col">
                        <x-text-input wire:model="personalnr" id="personalnr" class="block mt-1 w-full" type="number" name="personalnr"
                        required autofocus autocomplete="personalnr"/>
                        <x-input-error :messages="$errors->get('form.personalnr')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex flex-row  items-center mt-4">
                <div class="w-1/3  mr-2 text-right">
                    <x-input-label for="datum" :value="'Datum'" />
                </div>
                <div class="w-2/3">
                    <div class=" flex flex-col">
                        <x-text-input wire:model="datum" id="datum" class="block mt-1 w-full" type="date" name="datum" />
                        <x-input-error :messages="$errors->get('datum')" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3">
                    Speichern
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
