<div class="flex flex-row border border-gray-400 rounded m-2 bg-blue-100 px-2 py-4 text-2xl text-gray-800">
    {{ $slot->isEmpty() ? 'xxx' : $slot }}
</div>
