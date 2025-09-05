<flux:modal name="novo-pedido" class="w-full md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Novo Pedido</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes do pedido.</flux:text>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-2">
            <div class="flex items-center gap-x-1 w-full max-w-[300px]">
                <div class="w-full">
                    <flux:select wire:model="fornecedor_id" label="Fornecedor">
                        <flux:select.option value="null">Nenhum</flux:select.option>
                        @foreach ($fornecedores as $fornecedor)
                            @if($fornecedor['id'] == $fornecedor_id)
                                <flux:select.option selected value="{{$fornecedor['id']}}">{{$fornecedor['nome']}}</flux:select.option>
                            @else
                                <flux:select.option value="{{$fornecedor['id']}}">{{$fornecedor['nome']}}</flux:select.option>
                            @endif
                        @endforeach
                    </flux:select>
                </div>
                <flux:button x-on:click="$dispatch('novo-fornecedor');" class="mt-6 cursor-pointer">+</flux:button>
            </div>
            
            <div class="flex items-center gap-x-1 w-full max-w-[300px]">
                <div class="w-full">
                    <flux:select wire:model="cliente_id" label="Cliente">
                        <flux:select.option value="null">Nenhum</flux:select.option>
                        @foreach ($clientes as $cliente)
                            @if($cliente['id'] == $cliente_id)
                                <flux:select.option selected value="{{$cliente['id']}}">{{$cliente['nome']}}</flux:select.option>
                            @else
                                <flux:select.option value="{{$cliente['id']}}">{{$cliente['nome']}}</flux:select.option>
                            @endif
                        @endforeach
                    </flux:select>
                </div>
                <flux:button x-on:click="$dispatch('novo-cliente');" class="mt-6 cursor-pointer">+</flux:button>
            </div>
            
            <div class="flex items-center justify-start">
                <flux:input wire:model="cotacao_dolar" label="Cot. Dólar" />
                <svg wire:loading wire:target="set_cotacao_dolar" class="animate-spin h-3 w-3 absolute mt-7 ml-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>
            
            <flux:select wire:model="status" label="Status">
                <flux:select.option value="pendente">Pendente</flux:select.option>
                <flux:select.option value="em andamento">Em Andamento</flux:select.option>
                <flux:select.option value="finalizado">Finalizado</flux:select.option>
                <flux:select.option value="cancelado">Cancelado</flux:select.option>
            </flux:select>
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="criar" class="cursor-pointer">Criar Pedido</flux:button>
        </div>
    </div>

        @script
            <script>
            document.addEventListener("novo-pedido", async () => {
                setTimeout(() => {
                    $wire.set_cotacao_dolar();
                }, 200);
            }, { once: true });
            </script>
        @endscript
</flux:modal>