
    <x-slot name="header">
        {{ __('Edit Request') }}
    </x-slot>

        <x-splade-modal>
                <x-splade-form :default="$user_request" method="PUT" :action="route('request.update',$user_request->id)">
                    <x-splade-select name="status" class="mt-3">
                        <option value="PENDING">PENDING</option>
                        <option value="PROCESSING">PROCESSING</option>
                        <option value="PROCESSED">PROCESSED</option>
                    </x-splade-select>
                    <x-splade-input name="item_id" label="Item id" />
                    <x-splade-input name="user_id" label="User id" />
                    
                    <x-splade-submit class="mt-3" />
                </x-splade-form>
        </x-splade-modal>
    
