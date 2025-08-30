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
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-200 uppercase bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'nome',
                                    'displayName' => 'Nome'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'telefone',
                                    'displayName' => 'Telefone'
                                ])
                                @include('livewire.includes.table-sortable-th',[
                                    'name' => 'endereco',
                                    'displayName' => 'Endereço'
                                ])
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr wire:key="tr-cliente-{{ $cliente->id }}" class="border-b dark:border-gray-700">
                                    <td scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a target="_blank" href="https://web.whatsapp.com/send?autoload=1&app_absent=0&phone={{$cliente->telefone}}&text">
                                            {{ $cliente->nome }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a target="_blank" href="https://web.whatsapp.com/send?autoload=1&app_absent=0&phone={{$cliente->telefone}}&text">
                                            {{ $cliente->telefone }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a target="_blank" href="https://www.google.com.br/maps/place/{{$cliente->endereco}}">
                                            {{ $cliente->endereco }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        <flux:button x-on:click="$dispatch('editar-cliente', {cliente_id: {{$cliente->id }}})" variant="primary" class="cursor-pointer mr-1">Editar</flux:button>
                                        <flux:modal.trigger name="deletar-cliente-{{$cliente->id}}">
                                            <flux:button variant="danger" class="cursor-pointer">Apagar</flux:button>
                                        </flux:modal.trigger>

                                        <flux:modal name="deletar-cliente-{{$cliente->id}}" class="w-full md:w-96">
                                            <div class="space-y-6">
                                                <div>
                                                    <flux:heading size="lg">Apagar Cliente</flux:heading>
                                                    <flux:text class="mt-2">Tem certeza que deseja apagar este cliente?</flux:text>
                                                </div>

                                                <div class="flex">
                                                    <flux:spacer />
                                                    <flux:button variant="danger" wire:click="delete({{ $cliente->id }})" class="cursor-pointer">Apagar Cliente</flux:button>
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
                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </section>
</div>