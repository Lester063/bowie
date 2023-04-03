<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ProtoneMedia\Splade\SpladeTable;
use App\Models\UserRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\Facades\Toast;
use App\Models\Inventory;
use Auth;
class UserRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('user_requests.status', 'LIKE', "%{$value}%")
                        ->orWhere('users.name', 'LIKE', "%{$value}%");
                });
            });
        });
        $user_request = QueryBuilder::for(UserRequest::class)->join('inventories', 'inventories.id','=','user_requests.item_id')
        ->join('users', 'users.id','=','user_requests.user_id')
        ->select('*','user_requests.id as ur_id', 'user_requests.status as ur_status', 'users.name as u_name')
        ->defaultSort('-user_requests.created_at')
        ->allowedSorts(['user_requests.status', 'users.name'])
        ->allowedFilters(['user_requests.status', 'users.name', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('user_request.index', [
            'user_request' => SpladeTable::for($user_request)
            ->defaultSort('ur_status')
            ->withGlobalSearch()
            ->column('users.name', sortable : true, searchable: true)
            ->column('item_name')
            ->column('item_code')
            ->column('user_requests.status', sortable : true, searchable: true)
            ->column('action'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user_request.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'item_id'=>'required',
            'user_id'=>'required',
        ]);
        $verify = Inventory::find($request->item_id);
        if($verify['status']=='PROCESSED') {
            Toast::warning('Item has been added to other user.');
        }
        else {
            $user_request=UserRequest::create([
                'item_id'=>$request['item_id'],
                'user_id'=>$request['user_id'],
                'status'=>'PENDING',
            ]);
            Toast::title('Request added successfully');
        }
        return redirect()->route('request.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_request=UserRequest::find($id);
        return view('user_request.edit',compact('user_request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        request()->validate([
            'status' => 'required',
            'item_id' => 'required',
            'user_id' => 'required',
        ]);
        $isExist = Inventory::where('id', $request->item_id)->exists();
        $item=Inventory::find($request->item_id);
        $user_request=UserRequest::find($id);
        // $user_request['status']!='PROCESSED' -to be able to update the request that is currently on process on the item
        if ($isExist &&$item['status'] === 'PROCESSED' && $user_request['status']!='PROCESSED') {
            Toast::warning("Item not available at the moment.");
            return redirect()->route('request.index');
        }
        else if ($isExist && $item['status'] === 'PROCESSING' && $user_request['status'] != 'PROCESSING') {
            Toast::warning("Item is currently being processed with other user.");
            return redirect()->route('request.index');
        }
        else if ($item['is_deleted'] == '1') {
            Toast::warning("Item is deleted.");
            return redirect()->route('request.index');
        }
        else{
            $user_request->update($request->all());
            $item->update([
                'status'=>$request->status
            ]);
            if($request->is_returned=='1') {
                $item->update([
                    'status'=>'AVAILABLE'
                ]);
            }
            Toast::title('Request updated successfully.');
            return redirect()->route('request.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user_request=UserRequest::find($id);
        $user_request->delete();
        Toast::title('Request was deleted successfully.');
        return redirect()->route('request.index');
    }

    //user request controller
    public function userrequest(Request $request){
        $auth_id=Auth::id();

        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('status', 'LIKE', "%{$value}%")
                        ->orWhere('item_id', 'LIKE', "%{$value}%");
                });
            });
        });

        $user_request = QueryBuilder::for(UserRequest::where('user_id',$auth_id))->join('inventories', 'inventories.id','=','user_requests.item_id')
        ->select('*','user_requests.status as ur_status')
        ->defaultSort('user_requests.status')
        ->allowedSorts(['ur_status', 'item_name'])
        ->allowedFilters(['ur_status', 'item_name', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('user.index', [
            'user_request' => SpladeTable::for($user_request)
            ->defaultSort('status')
            ->withGlobalSearch()
            ->column('item_name', sortable : true, searchable: true)
            ->column('item_code')
            ->column('ur_status', sortable : true, searchable: true)
        ]);
    }

    public function requestitem(Request $request){
        $item_id=$request->item_id;
        $user_id=$request->user_id;

            $verify = Inventory::find($request->item_id);
            $verify2 = UserRequest::where('item_id', $request->item_id)->where('user_id', $request->user_id)->where('status', 'PENDING')->exists();
            if($verify['status']=='PROCESSED') {
                Toast::warning('Item has been added to other user.');
                return redirect()->route('user.availableitem');
            }
            else if($verify2){
                Toast::warning('You have requested this item already.');
                return redirect()->route('user.availableitem');
            }
            else {
                $user_request=UserRequest::create([
                    'item_id'=>$request['item_id'],
                    'user_id'=>$request['user_id'],
                    'status'=>'PENDING',
                    'is_returned'=>'0'
                ]);
                Toast::title('Request added successfully');
                return redirect()->route('user.userrequest');
            }
            
        

    }

}
