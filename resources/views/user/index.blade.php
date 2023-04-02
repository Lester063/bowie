
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Request') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <x-splade-table :for="$user_request">
            @cell('ur_status', $user_request)
                @if($user_request['ur_status'] == 'PROCESSED')
                    <p style='background-color:green;color:#fff;width:fit-content;padding:5px;border-radius:5px;'>{{$user_request['ur_status']}}</p>
                @else
                    <p style='background-color:red;color:#fff;width:fit-content;padding:5px;border-radius:5px;'>{{$user_request['ur_status']}}</p>
                @endif
            @endcell
        </x-splade-table>
    </div>
</x-app-layout>