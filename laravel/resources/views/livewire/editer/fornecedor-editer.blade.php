
<flux:modal name="editar-fornecedor" class="w-full md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Editando Fornecedor</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do fornecedor.</flux:text>
        </div>

        <flux:input wire:model="nome" label="Nome" />
        <flux:input wire:model="telefone" label="Telefone" mask="(99) 99999-9999"/>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="editar" class="cursor-pointer">Salvar Fornecedor</flux:button>
        </div>
    </div>
</flux:modal>
