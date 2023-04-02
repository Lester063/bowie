
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
                    @if($user_request['status'] == 'PROCESSED') 
                    <x-splade-select name="is_returned" class="mt-3">
                        <option value="0">NOT YET</option>
                        <option value="1">YES</option>
                    </x-splade-select>
                    @endif
                    <x-splade-input name="item_id" label="Item id" type="hidden"/>
                    <x-splade-input name="user_id" label="User id" type="hidden" />
                    
                    <x-splade-submit class="mt-3" />
                </x-splade-form>
        </x-splade-modal>
    
