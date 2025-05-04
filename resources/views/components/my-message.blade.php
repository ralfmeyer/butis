<div class="print:hidden z-50 flex fixed top-0 bg-opacity-60 items-center
    w-full h-full bg-slate-100 shadow-md backdrop-blur-[2px]"
    x-show="showMessage"
    x-cloak
    x-on:click.self="showMessage = false"
    x-on:keydown.escape.window="showMessage = false">
    <!-- gesamtes Fenster -->
    <div class="w-6/12 m-auto text-center bg-opacity-0">
        <!-- Nachricht -->
        <div class="m-4 p-4 border rounded-md border-sky-600 bg-blue-300 shadow-md">
            <div class="flex flex-row items-center w-full">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
