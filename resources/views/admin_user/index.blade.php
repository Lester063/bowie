
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Users') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <x-splade-table :for="$user" class="mt-5">
            @cell('action', $user)
            <Link href="{{route('viewuser.request', $user->id)}}" class="genButton" style="margin-right:15px;">
                    View
            </Link>

            @endcell
        </x-splade-table>
    </div>
</x-app-layout>