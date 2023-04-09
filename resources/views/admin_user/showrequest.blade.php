
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$userdata->name}}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <Link modal href="{{route('request.create')}}" class="genButton">
                    Create
        </Link>
        <x-splade-table :for="$user_request" class="mt-3">
            @cell('users.name', $user_request)
                <p>{{$user_request['name']}}</p>
            @endcell
            @cell('user_requests.status', $user_request)
                @if($user_request['ur_status'] == 'PROCESSED')
                    <p class="greenButton">{{$user_request['ur_status']}}</p>
                @else
                    <p class="redButton">{{$user_request['ur_status']}}</p>
                @endif
    
            @endcell
            @cell('action', $user_request)
                <Link modal href="{{ route('request.edit', $user_request->ur_id)}}" class="genButton">
                    Edit
                </Link>
                <x-splade-form method="DELETE" :action="route('request.destroy',$user_request->id)" confirm>
                    <x-splade-submit style="background-color:red" class="mt-3">Delete</x-splade-submit>
                </x-splade-form>

            @endcell
        </x-splade-table>
    </div>
</x-app-layout>