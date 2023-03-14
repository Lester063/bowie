
    <x-slot name="header">
        {{ __('Create new Item') }}
    </x-slot>

        <x-splade-modal>
                <x-splade-form method="POST" :action="route('item.store')">
                    <x-splade-input name="item_name" label="Item name" />
                    <x-splade-input name="item_code" label="Item code" />
                    
                    <x-splade-submit class="mt-3" />
                </x-splade-form>
        </x-splade-modal>
    
