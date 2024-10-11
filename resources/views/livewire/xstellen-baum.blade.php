<div class="bg-red-300">

    <ul>
        @foreach($stellen as $stelle)
            <li x-data="{ open: false }">
                <div @click="open = ! open" class="cursor-pointer">
                    {{ $stelle->bezeichnung }} @if ($stelle->children->isNotEmpty()) > @endif
                </div>

                @if($stelle->children->isNotEmpty())
                    <ul x-show="open" class="ml-4">
                        @if (!is_null($stelle))
                            @livewire('stellen-baum', ['parent_id' => $stelle->id], key($stelle->id))
                        @endif
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
