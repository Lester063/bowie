
    <x-slot name="header">
        {{ __('Edit Request') }}
    </x-slot>

        <x-splade-modal>
                <x-splade-form :default="$user_request" method="PUT" :action="route('request.update',$user_request->id)">
                    <x-splade-input name="status" label="Status" />
                    <x-splade-input name="item_id" label="Item id" />
                    <x-splade-input name="user_id" label="User id" />
                    
                    <x-splade-submit class="mt-3" />
                </x-splade-form>
        </x-splade-modal>
    
