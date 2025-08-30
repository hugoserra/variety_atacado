<x-layouts.app title="Produtos">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:maker.button modal="novo-produto" label="Novo Produto" />
        <livewire:maker.produto-maker />
        <livewire:editer.produto-editer />
        <livewire:table.produto-table />
    </div>
</x-layouts.app>
