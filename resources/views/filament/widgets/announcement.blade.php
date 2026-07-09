<x-filament-widgets::widget>
    @php
        $announcements = $this->getAnnouncements();
    @endphp

    @if($announcements->isEmpty())
        {{-- Show nothing when no active announcements --}}
    @else
        <div class="flex flex-col gap-3">
            @foreach($announcements as $announcement)
                @php
                    $styles = match($announcement->priority) {
                        'danger'  => 'bg-red-50 dark:bg-red-950/30 border-red-300 dark:border-red-700 text-red-800 dark:text-red-200',
                        'warning' => 'bg-amber-50 dark:bg-amber-950/30 border-amber-300 dark:border-amber-700 text-amber-800 dark:text-amber-200',
                        'info'    => 'bg-blue-50 dark:bg-blue-950/30 border-blue-300 dark:border-blue-700 text-blue-800 dark:text-blue-200',
                        default   => 'bg-gray-50 dark:bg-gray-900/30 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300',
                    };

                    $icon = match($announcement->priority) {
                        'danger'  => 'heroicon-o-exclamation-circle',
                        'warning' => 'heroicon-o-exclamation-triangle',
                        'info'    => 'heroicon-o-information-circle',
                        default   => 'heroicon-o-megaphone',
                    };
                @endphp

                <div class="flex gap-3 p-4 rounded-xl border {{ $styles }}">
                    <div class="shrink-0 mt-0.5">
                        <x-dynamic-component :component="$icon" class="w-5 h-5" />
                    </div>
                    <div class="flex flex-col gap-1 min-w-0">
                        <span class="font-semibold text-sm">{{ $announcement->title }}</span>
                        <div class="text-sm prose prose-sm max-w-none dark:prose-invert">
                            {!! $announcement->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament-widgets::widget>
