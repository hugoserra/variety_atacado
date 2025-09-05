<flux:modal name="editar-transacao" class="w-full md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Editando Transação</flux:heading>
            <flux:text class="mt-2">Preencha os detalhes da transação.</flux:text>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
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
            <div class="col-span-2">
                <flux:textarea wire:model="descricao" label="Descrição" placeholder="Descrição da transação..." rows="2"/>
            </div>
            <flux:input wire:model="valor" label="Valor (R$)" />
        </div>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary" wire:click="editar" class="cursor-pointer">Editar Transação</flux:button>
        </div>
    </div>
</flux:modal>