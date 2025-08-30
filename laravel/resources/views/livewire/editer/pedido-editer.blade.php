
<flux:modal name="editar-pedido" class="w-full md:w-[90%] md:max-w-[90%]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Editando Pedido</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do pedido.</flux:text>
        </div>

        <div class="flex items-start gap-x-4 mt-4 mb-6">
            <div class="flex items-center gap-x-1 max-w-[300px]">
                <flux:select wire:model="cliente_id" label="Cliente">
                    <flux:select.option value="null">Nenhum</flux:select.option>
                    @foreach ($clientes as $cliente)
                        @if($cliente['id'] == $cliente_id)
                            <flux:select.option selected value="{{$cliente['id']}}">{{$cliente['nome']}} - {{$cliente['telefone']}} - {{$cliente['endereco']}} </flux:select.option>
                        @else
                            <flux:select.option value="{{$cliente['id']}}">{{$cliente['nome']}} - {{$cliente['telefone']}} - {{$cliente['endereco']}} </flux:select.option>
                        @endif
                    @endforeach
                </flux:select>
                <flux:button x-on:click="$dispatch('novo-cliente');" class="mt-6 cursor-pointer">+</flux:button>
            </div>

            <flux:select wire:model="status" label="Status">
                <flux:select.option value="pendente">Pendente</flux:select.option>
                <flux:select.option value="em andamento">Em Andamento</flux:select.option>
                <flux:select.option value="finalizado">Finalizado</flux:select.option>
                <flux:select.option value="cancelado">Cancelado</flux:select.option>
            </flux:select>

            <div class="w-[40%]">
                <flux:textarea wire:model="observacao" label="Observações Pedido" placeholder="Observações do pedido.." rows="2"/>
            </div>
        </div>

        <div class="grid grid-cols-1">
            <div class="flex h-full w-full flex-1 flex-col rounded-xl">
                <flux:separator />
                <div class="flex items-center gap-x-4 mt-4 mb-6">
                    <div class="flex gap-x-1">
                        <x-searchable-select :collection="$produtos" value="id" label="nome" model="produto_id" title="Produto"/>
                        <flux:button x-on:click="$dispatch('novo-produto');" class="cursor-pointer mt-[25px]">+</flux:button>
                    </div>
                    <flux:input wire:model="quantidade_produto_pedido" type="number" label="Quantidade Pedido" />
                    <flux:button wire:click="vincular_produto_pedido" variant="primary" class="cursor-pointer mt-[25px]">Vincular ao Pedido</flux:button>
                </div>
                <flux:separator class="mb-6"/>
                <flux:heading class="mb-6" size="lg">Produtos Vinculados ao Pedido</flux:heading>
                <livewire:table.produto-table />
            </div>
        </div>

        <div class="flex justify-end gap-x-4 items-center">
            <flux:button type="submit" variant="primary" wire:click="editar" class="cursor-pointer">Salvar Pedido</flux:button>
        </div>
    </div>
</flux:modal>
