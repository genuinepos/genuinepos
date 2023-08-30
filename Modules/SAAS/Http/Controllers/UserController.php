<?php

namespace Modules\SAAS\Http\Controllers;

use App\Models\User;
use App\Utils\FileUploader;
use Illuminate\Http\Request;
use Modules\SAAS\Entities\Role;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Contracts\Support\Renderable;
use Modules\SAAS\Http\Requests\UserStoreRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('status', 1);
        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('saas.users.edit', $row->id) . '" class="px-2 edit-btn" id="editUser" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('saas.users.destroy', $row->id) . '" class="px-2 delete-btn" id="deleteUser" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';
                    return $html;
                })
                ->make(true);
        }
        return view('saas::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('saas::users.create', [
            'roles' => Role::all(),
        ]);
    }


    public function store(UserStoreRequest $request, FileUploader $fileUploader)
    {
        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $fileUploader->upload($request->file('photo'), 'uploads/saas/users/');
        }
        $user = User::create([
            ...$request->except(['role_id', 'photo']),
            'photo' => $photo
        ]);
        $role = Role::find($request->role_id);
        if ($user && $role) {
            $user->assignRole();
            return redirect(route('saas.users.index'))->with('success', 'User created successfully!');
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        // return view('saas::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        // return view('saas::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
