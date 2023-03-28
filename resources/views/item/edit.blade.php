
    <x-slot name="header">
        {{ __('Item Edit') }}
    </x-slot>

        <x-splade-modal>
                <x-splade-form :default="$item" method="PUT" :action="route('item.update',$item->id)">
                    <x-splade-input name="item_name" label="Item name" />
                    <x-splade-input name="item_code" label="Item code" />
                    
                    <x-splade-select name="status" class="mt-3">
                        <option value="{{$item->status}}">{{$item->status}}</option>
                        @if($item->status!='AVAILABLE')
                        <option value="AVAILABLE">AVAILABLE</option>
                        @endif
                    </x-splade-select>
                    
                    <x-splade-submit class="mt-3" />
                </x-splade-form>
        </x-splade-modal>
    
