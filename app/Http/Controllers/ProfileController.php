<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\SpladeTable;
use App\Models\User;
use App\Models\UserRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        //$user= User::all();
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('name', 'LIKE', "%{$value}%")
                        ->orWhere('created_at', 'LIKE', "%{$value}%");
                });
            });
        });
        $user = QueryBuilder::for(User::class)->where('is_admin', false)
        ->defaultSort('-created_at')
        ->allowedSorts(['name', 'email'])
        ->allowedFilters(['name', 'email', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('admin_user.index', [
            'user' => SpladeTable::for($user)
            ->defaultSort('-created_at')
            ->withGlobalSearch()
            ->column('name', sortable : true, searchable: true)
            ->column('email', sortable : true, searchable: true)
            ->column('action'),
        ]);

    }
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function show(string $id) {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('user_requests.status', 'LIKE', "%{$value}%")
                        ->orWhere('inventories.item_name', 'LIKE', "%{$value}%");
                });
            });
        });
        $userdata=User::find($id);
        $user_request = QueryBuilder::for(UserRequest::class)->where('user_id',$id)->join('inventories', 'inventories.id','=','user_requests.item_id')
        ->join('users', 'users.id','=','user_requests.user_id')
        ->select('*','user_requests.id as ur_id', 'user_requests.status as ur_status', 'users.name as u_name')
        ->defaultSort('-user_requests.created_at')
        ->allowedSorts(['user_requests.status'])
        ->allowedFilters(['user_requests.status', $globalSearch])
        ->paginate(5)
        ->withQueryString();

        return view('admin_user.showrequest', [
            'user_request' => SpladeTable::for($user_request)
            ->defaultSort('ur_status')
            ->withGlobalSearch()
            ->column('item_name', sortable : true, searchable: true)
            ->column('item_code')
            ->column('user_requests.status', sortable : true, searchable: true)
            ->column('action')
        ])->with('userdata',$userdata);
    }

    /**
     * Update the user's profile information.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
