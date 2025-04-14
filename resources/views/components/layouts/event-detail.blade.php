<x-layouts.organisation.event-detail-sidebar :eventId="$eventId" :event="$event ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.organisation.event-detail-sidebar>
