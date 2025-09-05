<x-layouts.app title="Transações">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:maker.button modal="nova-transacao" label="Nova Transação" />
        <livewire:maker.transacao-maker />
        <livewire:editer.transacao-editer />
        <livewire:table.transacao-table />
    </div>
</x-layouts.app>
