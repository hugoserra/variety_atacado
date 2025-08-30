<x-layouts.app title="Fornecedores">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:maker.button modal="novo-fornecedor" label="Novo Fornecedor" />
        <livewire:maker.fornecedor-maker />
        <livewire:editer.fornecedor-editer />
        <livewire:table.fornecedor-table />
    </div>
</x-layouts.app>
