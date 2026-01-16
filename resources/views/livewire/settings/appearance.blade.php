<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <flux:radio.group
    x-data="{
        theme: localStorage.getItem('theme') ?? 'system'
    }"
    x-init="
        let setTheme = (value) => {
            localStorage.setItem('theme', value)

            if (value === 'dark') {
                document.documentElement.classList.add('dark')
            } else if (value === 'light') {
                document.documentElement.classList.remove('dark')
            } else {
                // Untuk 'system', selalu set ke mode terang
                document.documentElement.classList.remove('dark')
            }
        }

        $watch('theme', setTheme)
        setTheme(theme)
    "
    x-model="theme"
    variant="segmented"
>
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="monitor-speaker">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>

