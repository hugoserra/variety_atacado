<flux:modal name="editar-produto" class="md:w-128">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Editando Produto</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do produto.</flux:text>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input wire:model="nome" label="Nome"/>

            <flux:select wire:model="tipo_frete" label="Tipo Frete">
                <flux:select.option value="pago pelo freteiro">Produto Pago Pelo Freteiro</flux:select.option>
                <flux:select.option value="pago pelo comprador">Produto Pago Pelo Comprador</flux:select.option>
            </flux:select>

            @if($pedido_id)
                <flux:input wire:model="quantidade_produto_pedido" type="number" label="Quantidade Pedido"/>
                <flux:input wire:model="preco_paraguai_dolar_pedido" type="number" label="Preço Paraguai (USD)"/>
                <flux:input wire:model="porcentagem_frete_pedido" type="number" label="Porc. Frete"/>
                <flux:input wire:model="porcentagem_lucro_pedido" type="number" label="Porc. Lucro"/>
            @endif
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="editar" class="cursor-pointer">Salvar Produto</flux:button>
        </div>
    </div>
</flux:modal>
