<div class="min-h-screen relative flex flex-col sm:justify-center items-center pt-10 pb-12 sm:pt-0 px-4 sm:px-6 overflow-hidden bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900">
    {{-- Ambient accents --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 -right-24 h-[28rem] w-[28rem] rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="absolute top-1/2 -left-32 h-80 w-80 rounded-full bg-teal-500/15 blur-3xl"></div>
        <div class="absolute -bottom-24 right-1/4 h-64 w-64 rounded-full bg-violet-500/10 blur-3xl"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center w-full max-w-lg">
        <div class="mb-8 sm:mb-10">
            {{ $logo }}
        </div>

        <div class="w-full px-1 sm:px-0">
            <div class="rounded-2xl border border-white/10 bg-white/95 dark:bg-gray-900/90 shadow-2xl shadow-indigo-950/50 backdrop-blur-sm overflow-hidden">
                <div class="px-6 py-8 sm:px-8 sm:py-10">
                    {{ $slot }}
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-slate-400/90">
                &copy; {{ date('Y') }} {{ $setting->company ?? config('app.name') }}
            </p>
        </div>
    </div>
</div>
