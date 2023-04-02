
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request -Admin') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 mt-5">
        <Link modal href="{{route('request.create')}}" class="btn btn-primary mb-2">
                    Create
        </Link>
        <x-splade-table :for="$user_request">
            @cell('users.name', $user_request)
                <p>{{$user_request['name']}}</p>
            @endcell
            @cell('user_requests.status', $user_request)
                @if($user_request['ur_status'] == 'PROCESSED')
                    <p style='background-color:green;color:#fff;width:fit-content;padding:5px;border-radius:5px;'>{{$user_request['ur_status']}}</p>
                @else
                    <p style='background-color:red;color:#fff;width:fit-content;padding:5px;border-radius:5px;'>{{$user_request['ur_status']}}</p>
                @endif
    
            @endcell
            @cell('action', $user_request)
                <Link modal href="{{ route('request.edit', $user_request->ur_id)}}" class="btn btn-primary">
                    Edit {{$user_request->ur_id}}
                </Link>
                <x-splade-form method="DELETE" :action="route('request.destroy',$user_request->id)" confirm>
                    <x-splade-submit style="background-color:red">Delete</x-splade-submit>
                </x-splade-form>

            @endcell
        </x-splade-table>
    </div>
</x-app-layout>