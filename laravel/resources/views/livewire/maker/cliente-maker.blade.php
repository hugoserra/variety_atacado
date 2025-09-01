<flux:modal name="novo-cliente" class="w-full md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Novo Cliente</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do cliente.</flux:text>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input wire:model="nome" label="Nome" />
            <flux:input wire:model="telefone" label="Telefone" mask="(99) 99999-9999" />
            <flux:input wire:model="endereco" label="Endereço" />
            <flux:input wire:model="porcentagem_frete" label="Porc. Frete" />
            <flux:input wire:model="porcentagem_lucro" label="Porc. Lucro" />
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="criar" class="cursor-pointer">Criar Cliente</flux:button>
        </div>
    </div>
</flux:modal>