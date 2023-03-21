
    <x-slot name="header">
        {{ __('Create new Item') }}
    </x-slot>

        <x-splade-modal>
                <x-splade-form method="POST">
                    <x-splade-input id="item_name" name="item_name" label="Item name" />
                    <x-splade-input id="item_code" name="item_code" label="Item code" />
                    
                    <x-splade-submit class="mt-3"/>
                </x-splade-form>
        </x-splade-modal>

    
