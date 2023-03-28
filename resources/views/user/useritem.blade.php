
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Item') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <x-splade-table :for="$item">
            @cell('action', $item)
            <form action="{{ route('user.request') }}" method="POST">
            @csrf
                <input type="hidden" name="item_id" value="{{$item->id}}" />
                <input type="hidden" name="user_id" value="{{Auth::id()}}" />
                <button type="submit" class="btn btn-primary" style="background-color:#aeaadf;padding:5px;border-radius:5px;">Request</button>
            </form>
            @endcell
        </x-splade-table>
    </div>
</x-app-layout>