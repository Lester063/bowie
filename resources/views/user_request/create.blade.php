
<x-slot name="header">
        {{ __('Create Request') }}
    </x-slot>

        <x-splade-modal>
                <x-splade-form method="POST">
                    <x-splade-input id="Item ID" name="item_id" label="Item ID" />
                    <x-splade-input id="User ID" name="user_id" label="User ID" />
                    
                    <x-splade-submit class="mt-3"/>
                </x-splade-form>
        </x-splade-modal>

    
