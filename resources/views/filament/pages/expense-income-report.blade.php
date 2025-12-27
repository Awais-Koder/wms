<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::fieldset>
            <x-slot name="label">
                Filters
            </x-slot>
            <label for="date_from" style="font-size: 13px">
                From Date
            </label>
            <x-filament::input.wrapper style="margin: 10px 0px">
                <x-filament::input type="date" wire:model.live="filters.date_from" id="date_from" />
            </x-filament::input.wrapper>
                <label for="date_to" style="font-size: 13px">
                    To Date
                </label>
            <x-filament::input.wrapper style="margin: 10px 0px">
                <x-filament::input type="date" wire:model.live="filters.date_to" id="date_to" />
            </x-filament::input.wrapper>
            <label for="month" style="font-size: 13px">
                Month
            </label>
            <x-filament::input.wrapper style="margin: 10px 0px">
                <x-filament::input.select wire:model.live="filters.month" id="month">
                    <option value="">All Months</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </x-filament::input.select>
            </x-filament::input.wrapper>
            <label for="year" style="font-size: 13px">
                Year
            <x-filament::input.wrapper style="margin: 10px 0px">
                <x-filament::input.select wire:model.live="filters.year" id="year">
                    <option value="">All Years</option>
                        @for ($i = now()->year - 5; $i <= now()->year + 1; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                </x-filament::input.select>
            </x-filament::input.wrapper>
            <x-filament::button color="warning" wire:click="resetFilters" wire:loading.attr="disabled">
                Reset Filters
            </x-filament::button>
        </x-filament::fieldset>
        {{-- <div
            class="rounded-xl border border-gray-200/80 bg-white p-6 shadow-sm dark:border-gray-700/60 dark:bg-gray-900">
            <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">Filters</div>

            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="flex flex-col gap-1">
                    <label for="date_from" class="text-sm font-medium text-gray-700 dark:text-gray-200">From
                        Date</label>
                    <input id="date_from" type="date" wire:model.live="filters.date_from"
                        class="fi-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="date_to" class="text-sm font-medium text-gray-700 dark:text-gray-200">To
                        Date</label>
                    <input id="date_to" type="date" wire:model.live="filters.date_to"
                        class="fi-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="month" class="text-sm font-medium text-gray-700 dark:text-gray-200">Month</label>
                    <select id="month" wire:model.live="filters.month"
                        class="fi-select-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">All Months</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="year" class="text-sm font-medium text-gray-700 dark:text-gray-200">Year</label>
                    <select id="year" wire:model.live="filters.year"
                        class="fi-select-input block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">All Years</option>
                        @for ($i = now()->year - 5; $i <= now()->year + 1; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <x-filament::button color="gray" wire:click="resetFilters" wire:loading.attr="disabled">
                    Reset Filters
                </x-filament::button>
            </div>
        </div> --}}
        <div class="mt-4 flex items-center gap-3">

        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
