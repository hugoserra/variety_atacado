<div>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.x.x/dist/cdn.min.js"></script>
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
                        
                        <div class="flex space-x-1 w-full">
                            <flux:select wire:model.live="fornecedor_id" placeholder="Fornecedor" class="w-max">
                                <flux:select.option value="todos">Todos</flux:select.option>
                                @foreach ($fornecedores as $fornecedor)
                                    <flux:select.option value="{{$fornecedor->id}}">{{$fornecedor->nome}}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:button class="cursor-pointer p-4" icon="arrow-down-tray" wire:click="gerar_relatorio_fornecedor()"></flux:button>
                        </div>

                        <div class="flex space-x-1 w-full">
                            <flux:select wire:model.live="cliente_id" placeholder="Cliente"  class="w-max">
                                <flux:select.option value="todos">Todos</flux:select.option>
                                @foreach ($clientes as $cliente)
                                    <flux:select.option value="{{$cliente->id}}">{{$cliente->nome}}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:button class="cursor-pointer p-4" icon="arrow-down-tray" wire:click="gerar_relatorio_cliente()"></flux:button>
                        </div>

                        <flux:select wire:model.live="status" placeholder="Status Do Pedido">
                            <flux:select.option value="todos">Todos</flux:select.option>
                            <flux:select.option value="pendente">Pendente</flux:select.option>
                            <flux:select.option value="em andamento">Em Andamento</flux:select.option>
                            <flux:select.option value="finalizado">Finalizado</flux:select.option>
                            <flux:select.option value="cancelado">Cancelado</flux:select.option>
                        </flux:select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-200 uppercase bg-gray-50 dark:bg-zinc-800 ">
                            <tr>
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'id',
                                    'displayName' => 'ID'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'fornecedor.nome',
                                    'displayName' => 'Fornecedor'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'cliente.nome',
                                    'displayName' => 'Cliente'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'status',
                                    'displayName' => 'Status'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'preco_total_chegada',
                                    'displayName' => 'Preço Chegada (R$)'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'preco_total_venda',
                                    'displayName' => 'Preço Venda (R$)'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'created_at',
                                    'displayName' => 'Data Criação'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'created_at',
                                    'displayName' => 'Última Atualização'
                                ])
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody x-sort>
                            @foreach ($pedidos as $pedido)
                                <tr wire:key="tr-pedido-{{ $pedido->id }}" class="border-b dark:border-gray-700" >
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">#{{$pedido->id}}</th>
                                    <td class="px-4 py-3 @if($fornecedor_id && $fornecedor_id != 'todos') underline @endif">{{ $pedido->fornecedor->nome }}</td>
                                    <td class="px-4 py-3 @if($cliente_id && $cliente_id != 'todos') underline @endif">{{ $pedido->cliente->nome }}</td>
                                    <td class="px-4 py-3 @if($status && $status != 'todos') underline @endif ">{{ $pedido->status }}</td>
                                    <td class="px-4 py-3">R$ {{ $pedido->preco_total_chegada }}</td>
                                    <td class="px-4 py-3">R$ {{ $pedido->preco_total_venda }}</td>
                                    <td class="px-4 py-3">{{ $pedido->created_at->format('d/m H:i') }}</td>
                                    <td class="px-4 py-3">{{ $pedido->updated_at->format('d/m H:i') }}</td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        <flux:checkbox.group wire:model.live="pedidos_selecionados">
                                            <flux:checkbox value="{{ $pedido->id }}" class="mr-4" />
                                        </flux:checkbox.group>
                                        <flux:button x-sort:item icon="arrows-pointing-in" class="cursor-pointer mr-2"></flux:button>
                                        <flux:button x-on:click="$dispatch('editar-pedido', {pedido_id: {{$pedido->id }}})" variant="primary" class="cursor-pointer mr-1">Editar</flux:button>
                                        <flux:modal.trigger name="deletar-pedido-{{$pedido->id}}">
                                            <flux:button variant="danger" class="cursor-pointer">Apagar</flux:button>
                                        </flux:modal.trigger>

                                        <flux:modal name="deletar-pedido-{{$pedido->id}}" class="w-full md:w-96">
                                            <div class="space-y-6">
                                                <div>
                                                    <flux:heading size="lg">Apagar Pedido</flux:heading>
                                                    <flux:text class="mt-2">Tem certeza que deseja apagar este pedido?</flux:text>
                                                </div>

                                                <div class="flex">
                                                    <flux:spacer />
                                                    <flux:button variant="danger" wire:click="delete({{ $pedido->id }})" class="cursor-pointer">Apagar Pedido</flux:button>
                                                </div>
                                            </div>
                                        </flux:modal>
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
                    {{ $pedidos->links() }}
                </div>
            </div>
        </div>
    </section>
</div>