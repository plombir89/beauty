<form wire:submit="submit" noValidate class="border-sand-100 shadow-soft rounded-4xl border bg-white p-6 sm:p-9">
    <div class="flex flex-col gap-5">
        <div class="flex flex-col gap-2">
            <label for="booking-service" class="text-ink text-xs font-semibold uppercase tracking-[0.12em]">
                {{ __('site.booking.service') }}
            </label>
            <div class="relative">
                <select id="booking-service" wire:model.live="serviceId" aria-invalid="@error('serviceId') true @else false @enderror" class="w-full cursor-pointer appearance-none rounded-2xl border bg-white/90 px-4 py-3.5 pr-11 text-sm text-ink transition-colors duration-200 focus:bg-white focus:outline-none @error('serviceId') border-blush-500 focus:border-blush-500 @else border-sand-200 hover:border-sand-300 focus:border-plum-400 @enderror">
                    <option value="">{{ __('site.booking.select_service') }}</option>
                    @foreach ($services as $service)
                        <option wire:key="booking-service-{{ $service->id }}" value="{{ $service->id }}">{{ $service->title }}</option>
                    @endforeach
                </select>
                <span aria-hidden="true" class="border-ink-soft pointer-events-none absolute right-5 top-1/2 size-2 -translate-y-2/3 rotate-45 border-b border-r"></span>
            </div>
            @error('serviceId')
                <p role="alert" class="text-blush-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="flex flex-col gap-2">
                <label for="booking-name" class="text-ink text-xs font-semibold uppercase tracking-[0.12em]">
                    {{ __('site.booking.name') }} <span class="text-blush-500" aria-hidden="true">*</span>
                </label>
                <input id="booking-name" wire:model="name" type="text" autocomplete="name" required class="w-full rounded-2xl border bg-white/90 px-4 py-3.5 text-sm text-ink placeholder:text-ink-faint transition-colors duration-200 focus:bg-white focus:outline-none @error('name') border-blush-500 focus:border-blush-500 @else border-sand-200 hover:border-sand-300 focus:border-plum-400 @enderror">
                @error('name')
                    <p role="alert" class="text-blush-500 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label for="booking-phone" class="text-ink text-xs font-semibold uppercase tracking-[0.12em]">
                    {{ __('site.booking.phone') }} <span class="text-blush-500" aria-hidden="true">*</span>
                </label>
                <input id="booking-phone" wire:model="phone" type="tel" inputmode="tel" autocomplete="tel" required class="w-full rounded-2xl border bg-white/90 px-4 py-3.5 text-sm text-ink placeholder:text-ink-faint transition-colors duration-200 focus:bg-white focus:outline-none @error('phone') border-blush-500 focus:border-blush-500 @else border-sand-200 hover:border-sand-300 focus:border-plum-400 @enderror">
                @error('phone')
                    <p role="alert" class="text-blush-500 text-xs">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <label for="booking-email" class="text-ink text-xs font-semibold uppercase tracking-[0.12em]">
                {{ __('site.booking.email') }} <span class="text-blush-500" aria-hidden="true">*</span>
            </label>
            <input id="booking-email" wire:model="email" type="email" inputmode="email" autocomplete="email" required class="w-full rounded-2xl border bg-white/90 px-4 py-3.5 text-sm text-ink placeholder:text-ink-faint transition-colors duration-200 focus:bg-white focus:outline-none @error('email') border-blush-500 focus:border-blush-500 @else border-sand-200 hover:border-sand-300 focus:border-plum-400 @enderror">
            @error('email')
                <p role="alert" class="text-blush-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-2">
            <label for="booking-message" class="text-ink text-xs font-semibold uppercase tracking-[0.12em]">{{ __('site.booking.message') }}</label>
            <textarea id="booking-message" wire:model="message" rows="5" placeholder="{{ __('site.booking.message_placeholder') }}" class="w-full resize-y rounded-2xl border bg-white/90 px-4 py-3.5 text-sm text-ink placeholder:text-ink-faint transition-colors duration-200 focus:bg-white focus:outline-none @error('message') border-blush-500 focus:border-blush-500 @else border-sand-200 hover:border-sand-300 focus:border-plum-400 @enderror"></textarea>
            @error('message')
                <p role="alert" class="text-blush-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div aria-hidden="true" class="hidden">
            <label for="company">Company</label>
            <input id="company" wire:model="company" name="company" tabindex="-1" autocomplete="off">
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn-primary w-full px-8 py-4 text-base disabled:pointer-events-none disabled:opacity-55">
            <span wire:loading.remove>{{ __('site.booking.submit') }}</span>
            <span wire:loading>{{ __('site.booking.submitting') }}</span>
        </button>

        @if ($submitted)
            <p role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ __('site.booking.success') }}
            </p>
        @endif
    </div>
</form>
