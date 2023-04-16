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

class DeletedItemController extends Controller
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

        $item = QueryBuilder::for(Inventory::class)->where('is_deleted','1')
        ->defaultSort('-created_at')
        ->allowedSorts(['item_name', 'item_code','status'])
        ->allowedFilters(['item_name', 'item_code','status', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('deleted_item.index', [
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        request()->validate([
            'is_deleted' => 'required',
        ]);
        $item=Inventory::find($id);
        $isExist=$item->where('item_code',$item['item_code'])->where('is_deleted','0')->exists();
        if($isExist) {
            Toast::warning('Item with the same code does exist on database.');
        }
        else {
            if($item['item_code'])
            $item->update([
                'is_deleted'=>'0'
            ]);
            Toast::title('Item restored successfully.');
        }
        return redirect()->route('deleteditem.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
