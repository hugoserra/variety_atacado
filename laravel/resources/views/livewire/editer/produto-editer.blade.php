<flux:modal name="editar-produto" class="md:w-128">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Editando Produto</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do produto.</flux:text>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input wire:model="nome" label="Nome"/>

            <flux:select wire:model="tipo" label="Tipo">
                <flux:select.option value="importado">Importado</flux:select.option>
                <flux:select.option value="nacional">Nacional</flux:select.option>
            </flux:select>

            <flux:select wire:model="tipo_frete" label="Tipo Frete">
                <flux:select.option value="pago pelo freteiro">Produto Pago Pelo Freteiro</flux:select.option>
                <flux:select.option value="pago pelo comprador">Produto Pago Pelo Comprador</flux:select.option>
            </flux:select>

            <flux:input wire:model="preco_produto_dolar" type="number" label="Preço Em Dólar"/>
            <flux:input wire:model="porcentagem_frete" type="number" label="Porcentagem Frete"/>
            <flux:input wire:model="porcentagem_lucro" type="number" label="Porcentagem Lucro"/>
            <flux:input wire:model="quantidade_estoque" type="number" label="Quantidade no Estoque"/>
            <flux:input wire:model="link_compras_paraguai" type="text" label="Link Compras Paraguai"/>
            @if($ordem_id)
                <flux:input wire:model="quantidade_produto_ordem" type="number" label="Quantidade Ordem"/>
            @endif
            @if($pedido_id)
                <flux:input wire:model="quantidade_produto_pedido" type="number" label="Quantidade Pedido"/>
                <flux:input wire:model.live="preco_final_pedido" type="number" label="Valor Final Padrão Vendedor"/>
                <flux:input wire:model.live="comissao_vendedor" type="number" label="Comissão Padrão Vendedor"/>
            @endif
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="editar" class="cursor-pointer">Salvar Produto</flux:button>
        </div>
    </div>
</flux:modal>
