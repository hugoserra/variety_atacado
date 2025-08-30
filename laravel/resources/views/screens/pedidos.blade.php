<x-layouts.app title="Pedidos">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:maker.button modal="novo-pedido" label="Novo Pedido" />
        <livewire:maker.pedido-maker />
        <livewire:editer.pedido-editer />
        <livewire:table.pedido-table />
        <livewire:maker.cliente-maker />
        <livewire:maker.produto-maker />
        <livewire:editer.produto-editer />
    </div>
</x-layouts.app>
