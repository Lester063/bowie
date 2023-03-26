
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <Link modal href="{{route('request.create')}}" class="btn btn-primary mb-2">
                    Create
        </Link>
        <x-splade-table :for="$user_request">

        </x-splade-table>
    </div>
</x-app-layout>