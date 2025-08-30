<style>
    .tabs-nav 
    {
        display: flex;
        border-bottom: 1px solid #ddd;
    }
    .tab-item 
    {
        padding: 12px 20px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
        font-family: var(--font);
        font-weight: 300;
        font-size: 15px;
        user-select: none;
    }
    .tab-item:focus 
    {
        outline: none;
    }
    .tab-item-active
    {
        border-bottom: solid 1px #007BFF;
    }
    .tabs-content 
    {
        padding: 16px;
    }
    .tab-panel 
    {
        display: flex;
        flex-direction: column;
    }
</style>

<div x-data="{ activeTab: '{{ key($slots) }}' }" class="w100p">
    <div class="tabs-nav">
        @foreach($slots as $tab => $content)
            <div x-on:click="activeTab = '{{ $tab }}'" :class="(activeTab == '{{ $tab }}') ? 'tab-item tab-item-active dark:bg-zinc-800 dark:text-gray-200' : 'tab-item dark:bg-zinc-800 dark:text-gray-200'" >{{ $tab }}</div>
        @endforeach
    </div>

    <div class="tabs-content">
        @foreach($slots as $tab => $content)
            <div x-show="activeTab == '{{ $tab }}'" class="tab-panel">
                {{ $content }}
            </div>
        @endforeach
    </div>
</div>
