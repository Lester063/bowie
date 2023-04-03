
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <Link modal href="{{route('item.create')}}" style="background-color:#aeaadf;padding:10px;border-radius:5px;margin-right:15px;">
                    Create
        </Link>
        <Link href="{{route('deleteditem.index')}}" style="background-color:#aeaadf;padding:10px;border-radius:5px;">
                    Deleted Item
        </Link>
        <x-splade-table :for="$item" class="mt-5">
            @cell('action', $item)
                <Link modal href="{{ route('item.edit', $item->id)}}" class="btn btn-primary">
                    Edit
                </Link>
                <x-splade-form method="DELETE" :action="route('item.destroy',$item->id)" confirm>
                    <x-splade-submit style="background-color:red">Delete</x-splade-submit>
                </x-splade-form>

            @endcell
        </x-splade-table>
    </div>
</x-app-layout>