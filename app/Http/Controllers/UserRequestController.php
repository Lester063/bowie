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
                        ->orWhere('status', 'LIKE', "%{$value}%")
                        ->orWhere('item_id', 'LIKE', "%{$value}%");
                });
            });
        });

        $user_request = QueryBuilder::for(UserRequest::class)
        ->defaultSort('status')
        ->allowedSorts(['status', 'item_id'])
        ->allowedFilters(['status', 'item_id', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('user_request.index', [
            'user_request' => SpladeTable::for($user_request)
            ->defaultSort('status')
            ->withGlobalSearch()
            ->column('status', sortable : true, searchable: true)
            ->column('item_id', sortable : true, searchable: true)
            ->column('user_id', sortable : true, searchable: true)
            ->column('action'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $item=Inventory::all();
        // ->with('item',$item)
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
        $verify = UserRequest::where('item_id', $request->item_id)->exists();
        if($verify) {
            Toast::warning('Item does not exist.');
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
        $verify = Inventory::where('id', $request->item_id)->exists();
        $item=Inventory::find($request->item_id);
        $user_request=UserRequest::find($id);
        if ($verify && $item['status'] === 'PROCESSED') {
            Toast::warning("Item not available at the moment.");
            return redirect()->route('request.index');
        }
        else{
            $user_request->update($request->all());
            $item->update([
                'status'=>$request->status
            ]);
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
}
