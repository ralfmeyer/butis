<div class=" px-2 flex border border-gray-400 rounded shadow-lg w-full">
    <ul>
        @foreach ($stellen as $stelle)
            <li x-data="{ open: true }" class="w-full bg-blue-50 px-2 pt-1 ">
                <div class="flex h-5">
                    <div @click="open = ! open" class="cursor-pointer">
                        @if ($stelle->children->isNotEmpty())
                            <span x-show="!open">
                                <x-heroicon-s-plus-circle class="w-5 h-5 be_icon_color" />&nbsp;
                            </span>

                            <span x-show="open">
                                <x-heroicon-o-minus-circle class="w-5 h-5 be_icon_color" />&nbsp;
                            </span>
                        @else
                            <span class="w-5 h-5">&nbsp;</span>
                        @endif
                    </div>

                    <div class=" lex-grow ml-2 whitespace-nowrap overflow-hidden">

                            <a href="#" wire:click.prevent="triggerEdit({{ $stelle->id }})" class="hover:underline">
                            {{ $stelle->bezeichnung }}

                            </a>
                            - ( {{ $stelle->mitarbeiter->anrede ?? '' }} {{ $stelle->mitarbeiter->name ?? 'Kein Mitarbeiter zugeordnet' }} )

                    </div>
                </div>

                @if ($stelle->children->isNotEmpty())
                    <ul x-show="open" class="ml-4 mb-2">
                        @livewire('stellen-baum', ['parent_id' => $stelle->id], key($stelle->id))
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>


</div>
