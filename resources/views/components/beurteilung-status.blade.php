@php
    use App\Models\BeurtStatus;
@endphp

@props(['status'])
@if ($status === BeurtStatus::closed)
    <x-heroicon-o-lock-closed class="w-5" title="abgeschlossen" />
@elseif ($status === BeurtStatus::edit)
    <x-heroicon-o-lock-open class="w-5" title="in Bearbeitung" />
@elseif ($status === BeurtStatus::wait)
    <x-fluentui-snooze-16-o  class="w-5" title="wartet" />
@elseif ($status === BeurtStatus::none)
    <x-fluentui-document-split-hint-off-16-o  class="w-5" title="nicht vorhanden" />
@endif


