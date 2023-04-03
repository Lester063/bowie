
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Deleted Item') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <x-splade-table :for="$item">
            @cell('action', $item)
                <x-splade-form method="PUT" :default="$item" :action="route('deleteditem.update',$item->id)" confirm="Are you sure you want to restore it?">
                    <x-splade-input name="is_deleted" type="hidden"/>
                    <x-splade-submit>Restore</x-splade-submit>
                </x-splade-form>

            @endcell
        </x-splade-table>
    </div>
</x-app-layout>