<div 
    x-data="selectComponent{{$model}}(@js($options))" class="relative w-full"
    @searchable_select_clear_{{$model}}.window="search = ''; $wire.{{$model}} = null";
>
    <!-- Campo de Pesquisa -->
    <div @click.away="open = false" class="relative">
        <div class="flex">
            <input type="text" style="display: block; opacity:0; width:0;"> 
            <div class="w-full">
                <flux:input 
                    wire:model.live="{{$model}}" 
                    x-model="search"
                    type="text" 
                    label="{{$title}}" 
                    @focus="open = true"
                    @keydown.arrow-down.prevent="navigate(1)"
                    @keydown.arrow-up.prevent="navigate(-1)"
                    @keydown.enter.prevent="$wire.{{$model}} = filteredOptions[selectedIndex].value; selectOption(filteredOptions[selectedIndex]);"
                    @change="$wire.{{$model}} = null;"
                    @input="open = true"
                    placeholder="Pesquisar..."
                    autocomplete="off"
                    name="{{Str::random(6)}}"
                />
            </div>
        </div>
        <div x-show="open"
             class="absolute min-w-96 w-full mt-2 bg-white border-2 rounded-md shadow-md z-10 max-h-64 overflow-auto text-base bg-white dark:bg-zinc-700 dark:text-white">
            <template x-for="(option, index) in filteredOptions" :key="option.value">
                <div @click="$wire.set('{{$model}}', option.value); selectOption(option);"
                     @mouseover="selectedIndex = index"
                     :class="{'bg-gray-100 dark:bg-zinc-600 dark:text-white': index === selectedIndex}"
                     class="px-3 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-zinc-600 dark:hover:text-whitew-full">
                    <span x-text="option.label"></span>
                </div>
            </template>
        </div>
    </div>
</div>
<script>
    function selectComponent{{$model}}(options) {
        return {
            open: false,
            search: '',
            selectedIndex: -1,
            options: options, // Recebe os dados do Livewire
            get filteredOptions() {
                if (!this.search) return this.options;
                const queryWords = this.search.toLowerCase().split(" ").filter(word => word.trim() !== "");
                return this.options.filter(option => {
                    const label = option.label.toLowerCase();
                    return queryWords.every(word => label.includes(word));
                });
            },
            navigate(step) {
                console.log('oi');
                this.selectedIndex = (this.selectedIndex + step + this.filteredOptions.length) % this.filteredOptions.length;
            },
            selectOption(option) {
                setTimeout(() => {
                    this.search = option.label;
                    this.open = false;
                }, 20);
            }
        };
    }
</script>
