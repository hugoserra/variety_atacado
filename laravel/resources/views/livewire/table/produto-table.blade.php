<div>
    <section>
        <div>
            <!-- Start coding here -->
            <div class="bg-white dark:bg-zinc-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex items-center justify-between d p-4">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                    fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                label="Search" required="">
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900 dark:text-gray-200">Tipo Frete:</label>
                            <select wire:model.live="tipo_frete"
                                class="appearance-none w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-none rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[9%] text-zinc-700 dark:text-zinc-300 has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400 dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border border-zinc-200 border-b-zinc-300/80 dark:border-white/10">
                                <option value="">Todos</option>
                                <option value="pago pelo freteiro">Pago Pelo Freteiro</option>
                                <option value="pago pelo comprador">Pago Pelo Comprador</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-200 uppercase bg-gray-50 dark:bg-zinc-800 ">
                            <tr>
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'nome',
                                    'displayName' => 'Nome'
                                ])
                                @if($pedido_id)
                                    @include('livewire.includes.table-sortable-th',[
                                        'name' => 'observacao',
                                        'displayName' => 'Observações'
                                    ])
                                @endif
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'tipo',
                                    'displayName' => 'Tipo'
                                ])
                                @if($pedido_id)
                                    @include('livewire.includes.table-sortable-th',[
                                        'name' => 'quantidade_produto',
                                        'displayName' => 'Qtd Pedido'
                                    ])
                                    @include('livewire.includes.table-sortable-th',[
                                        'name' => 'preco_paraguai',
                                        'displayName' => 'Preco Paraguai'
                                    ])
                                    @include('livewire.includes.table-sortable-th',[
                                        'name' => 'preco_chegada',
                                        'displayName' => 'Preco de Chegada'
                                    ])
                                    @include('livewire.includes.table-sortable-th',[
                                        'name' => 'preco_venda',
                                        'displayName' => 'Preco de Venda'
                                    ])
                                @endif
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produtos as $produto)
                                <tr wire:key="tr-produto-{{ $produto->id }}" class="border-b dark:border-gray-700">
                                    <td>
                                        <a target="_blank" href="{{$produto->link_compras_paraguai}}" class="px-4 py-3 font-medium text-gray-900 dark:text-white h-[50px] max-h-[50px] overflow-hidden line-clamp-2 break-words w-full min-w-80 max-w-96">{{ $produto->nome }}</a>
                                    </td>
                                    @if($pedido_id)
                                        <td wire:key="{{time()}}">
                                            <input type="text" class="p-2 text-gray-900 dark:text-white" placeholder="Observação" x-on:blur="$wire.setObservacaoProdutoPedido({{$produto->id}}, $el.value)" value="{{$produto->pivot->observacao}}">
                                        </td>
                                    @endif
                                    <td class="px-4 py-3">{{ $produto->tipo_frete }}</td>
                                    @if($pedido_id)
                                        <td class="px-4 py-3">{{ $produto->pivot->quantidade_produto }}</td>
                                        <td class="px-4 py-3">R$ {{ $produto->pivot->preco_paraguai }}</td>
                                        <td class="px-4 py-3">R$ {{ $produto->pivot->preco_chegada }}</td>
                                        <td class="px-4 py-3">R$ {{ $produto->pivot->preco_venda }}</td>
                                    @endif
                                   
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        <div class="flex">
                                            @if($pedido_id)
                                                <flux:modal.trigger name="desvincular-pedido-produto-{{$produto->id}}">
                                                    <flux:button  class="cursor-pointer mr-1">Desvincular</flux:button>
                                                </flux:modal.trigger>
                                                <flux:modal name="desvincular-pedido-produto-{{$produto->id}}" class="w-full md:w-96">
                                                    <div class="space-y-6">
                                                        <div>
                                                            <flux:heading size="lg">Desvincular Produto</flux:heading>
                                                            <flux:text class="mt-2">Tem certeza que deseja desvincular este produto do pedido?</flux:text>
                                                        </div>
    
                                                        <div class="flex">
                                                            <flux:spacer />
                                                            <flux:button variant="danger" wire:click="desvincularPedido({{$produto->id}})" class="cursor-pointer">Desvincular Produto</flux:button>
                                                        </div>
                                                    </div>
                                                </flux:modal>
                                            @endif
                                            <flux:button x-on:click="$dispatch('editar-produto', {produto_id: {{$produto->id }}})" variant="primary" class="cursor-pointer mr-1">Editar</flux:button>
                                            @if (!$pedido_id)
                                                <flux:modal.trigger name="deletar-produto-{{$produto->id}}">
                                                    <flux:button variant="danger" class="cursor-pointer">Apagar</flux:button>
                                                </flux:modal.trigger>
    
                                                <flux:modal name="deletar-produto-{{$produto->id}}" class="w-full md:w-96">
                                                    <div class="space-y-6">
                                                        <div>
                                                            <flux:heading size="lg">Apagar Produto</flux:heading>
                                                            <flux:text class="mt-2">Tem certeza que deseja apagar este produto?</flux:text>
                                                        </div>
    
                                                        <div class="flex">
                                                            <flux:spacer />
                                                            <flux:button variant="danger" wire:click="delete({{ $produto->id }})" class="cursor-pointer">Apagar Produto</flux:button>
                                                        </div>
                                                    </div>
                                                </flux:modal>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="py-4 px-3">
                    <div class="flex ">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900 dark:text-gray-200">Per Page</label>
                            <select wire:model.live='perPage'
                                class="appearance-none w-full ps-3 pe-10 block h-10 py-2 text-base sm:text-sm leading-none rounded-lg shadow-xs border bg-white dark:bg-white/10 dark:disabled:bg-white/[9%] text-zinc-700 dark:text-zinc-300 has-[option.placeholder:checked]:text-zinc-400 dark:has-[option.placeholder:checked]:text-zinc-400 dark:[&>option]:bg-zinc-700 dark:[&>option]:text-white disabled:shadow-none border border-zinc-200 border-b-zinc-300/80 dark:border-white/10">
                                <option value="5">5</option>
                                <option value="7">7</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $produtos->links() }}
                </div>
            </div>
        </div>
    </section>
</div>