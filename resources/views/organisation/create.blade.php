<x-layouts.user :title="__('Create Organization')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            @php
                $breadcrumbs = getBreadcrumbs();
            @endphp
            @foreach ($breadcrumbs as $index => $crumb)
                @if ($index < count($breadcrumbs) - 1)
                    <flux:breadcrumbs.item href="{{ $crumb['url'] }}">
                        {{ $crumb['title'] }}
                    </flux:breadcrumbs.item>
                @else
                    <flux:breadcrumbs.item>
                        {{ $crumb['title'] }}
                    </flux:breadcrumbs.item>
                @endif
            @endforeach
        </flux:breadcrumbs>

        @livewire('organisation.create')
    </div>
</x-layouts.user>
