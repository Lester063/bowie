<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ProtoneMedia\Splade\SpladeTable;
use App\Models\Inventory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\Facades\Toast;
//use Response;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('item_name', 'LIKE', "%{$value}%")
                        ->orWhere('item_code', 'LIKE', "%{$value}%")
                        ->orWhere('status', 'LIKE', "%{$value}%");
                });
            });
        });

        $item = QueryBuilder::for(Inventory::class)->where('is_deleted','0')
        ->defaultSort('-created_at')
        ->allowedSorts(['item_name', 'item_code','status'])
        ->allowedFilters(['item_name', 'item_code','status', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('item.index', [
            'item' => SpladeTable::for($item)
            ->defaultSort('item_name')
            ->withGlobalSearch()
            ->column('item_name', sortable : true, searchable: true)
            ->column('item_code', sortable : true, searchable: true)
            ->column('status', sortable : true, searchable: true)
            ->column('action'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $this->validate($request,[
            'item_name'=>'required',
            'item_code'=>'required',
        ]);
        $verify = Inventory::where('item_code', $request->item_code)->where('is_deleted','0')->exists();
        if($verify) {
            Toast::warning('Item code already exist.');
        }
        else {
            $item=Inventory::create([
                'item_name'=>$request['item_name'],
                'item_code'=>$request['item_code'],
                'status'=>'AVAILABLE',
                'is_deleted'=>'0',
            ]);
            Toast::title('Item added successfully.');
            //return Response::json($item);
            
        }
        
        return redirect()->route('item.index');
        
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
    public function edit(Inventory $item)
    {
        return view('item.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        request()->validate([
            'item_name' => 'required',
            'item_code' => 'required',
        ]);
        $verify = Inventory::where('item_code', $request->item_code)->exists();
        $item=Inventory::find($id);
        if ($verify && $item['item_code'] !== $request['item_code']) {
            Toast::warning("Code already exist.");
            return redirect()->route('item.index');
        }
        else{
            $item->update($request->all());
            Toast::title('Item updated successfully.');
            return redirect()->route('item.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $item=Inventory::find($id);
        $item->update([
            'is_deleted'=>'1'
        ]);
        Toast::title('Item was deleted successfully.');
        return redirect()->route('item.index');
    }


    //#user
    public function availableitem(Request $request){
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('item_name', 'LIKE', "%{$value}%")
                        ->orWhere('item_code', 'LIKE', "%{$value}%");
                });
            });
        });

        $item = QueryBuilder::for(Inventory::where('status','AVAILABLE')->where('is_deleted','0'))
        ->defaultSort('item_name')
        ->allowedSorts(['item_name', 'item_code'])
        ->allowedFilters(['item_name', 'item_code', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('user.useritem', [
            'item' => SpladeTable::for($item)
            ->defaultSort('item_name')
            ->withGlobalSearch()
            ->column('item_name', sortable : true, searchable: true)
            ->column('item_code', sortable : true, searchable: true)
            ->column('action'),
        ]);
    }

}
