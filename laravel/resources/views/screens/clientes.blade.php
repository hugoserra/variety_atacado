<x-layouts.app title="Clientes">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:maker.button modal="novo-cliente" label="Novo Cliente" />
        <livewire:maker.cliente-maker />
        <livewire:editer.cliente-editer />
        <livewire:table.cliente-table />
    </div>
</x-layouts.app>
