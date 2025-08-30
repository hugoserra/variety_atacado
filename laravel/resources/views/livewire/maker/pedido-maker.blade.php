<flux:modal name="novo-pedido" class="w-full md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Novo Pedido</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do pedido.</flux:text>
        </div>

        <div class="flex items-center gap-x-1 w-full max-w-[300px]">
            <div class="w-full">
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
            </div>
            <flux:button x-on:click="$dispatch('novo-cliente');" class="mt-6 cursor-pointer">+</flux:button>
        </div>

        <flux:select wire:model="status" label="Status">
            <flux:select.option value="pendente">Pendente</flux:select.option>
            <flux:select.option value="em andamento">Em Andamento</flux:select.option>
            <flux:select.option value="finalizado">Finalizado</flux:select.option>
            <flux:select.option value="cancelado">Cancelado</flux:select.option>
        </flux:select>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="criar" class="cursor-pointer">Criar Pedido</flux:button>
        </div>
    </div>
</flux:modal>