@php
    // Define background and text color classes based on the toast type.
    $backgroundClasses = match ($type) {
        'success' => 'bg-green-100 dark:bg-green-800',
        'error'   => 'bg-red-100 dark:bg-red-800',
        default   => 'bg-blue-100 dark:bg-blue-800',
    };

    $textClasses = match ($type) {
        'success' => 'text-green-900 dark:text-green-100',
        'error'   => 'text-red-900 dark:text-red-100',
        default   => 'text-blue-900 dark:text-blue-100',
    };

    // Define positioning classes.
    $positionClasses = match ($position) {
        'top-right'    => 'top-4 right-4',
        'top-left'     => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left'  => 'bottom-4 left-4',
        default        => 'top-4 right-4',
    };
@endphp

<div
    x-data="{
        show: @entangle('show'),
        progress: 100,
        timer: null,
        startTimer() {
            // Reset progress
            this.progress = 100;
            // Clear any previous interval
            clearInterval(this.timer);
            // Decrement progress by 1 every 30ms (roughly 3000ms total)
            this.timer = setInterval(() => {
                this.progress -= 100 / (3000 / 30);
                if (this.progress <= 0) {
                    this.progress = 0;
                    clearInterval(this.timer);
                    this.show = false;
                }
            }, 30);
        }
    }"
    x-init="
        $watch('show', value => {
            if(value) {
                startTimer();
            } else {
                clearInterval(timer);
                progress = 100;
            }
        });
    "
    x-show="show"
    x-transition.opacity.duration.300ms
    style="display: none;"
    class="fixed {{ $positionClasses }} z-50 max-w-xs w-full shadow-lg rounded-lg overflow-hidden transition-all duration-300 {{ $backgroundClasses }}"
>
    <div class="p-4 relative">
        <!-- Close button -->
        <button type="button" class="absolute top-3 right-3 text-md font-bold cursor-pointer" @click="show = false">
            &times;
        </button>

        <div class="flex items-center">
            <!-- Icon based on type -->
            @if($type === 'success')
                <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
            @elseif($type === 'error')
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            @else
                <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M13 16h-1v-4h-1m1-4h.01" />
                </svg>
            @endif

            <div class="ml-3">
                <p class="text-sm font-medium {{ $textClasses }}">
                    {{ $message }}
                </p>
            </div>
        </div>
    </div>

    <!-- Timeout progress indicator -->
    <div class="w-full bg-gray-200 dark:bg-gray-700 h-0.5">
        <div class="h-full bg-current transition-all duration-0" :style="'width: ' + progress + '%'" ></div>
    </div>
</div>
