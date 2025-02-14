<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = User::where('role', '!=', 'super_admin')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    return $row->username;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('role', function ($row) {
                    return ucfirst($row->role);
                })
                ->addColumn('action', function ($row) {
                    $data = '<div class="d-flex justify-content-center">
                                <button data-bs-toggle="modal" data-bs-target="#showModal' . $row->id . '" class="btn btn-success me-2"><i class="fas fa-eye"></i></button>
                                <button data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '" class="btn btn-info me-2"><i class="fas fa-edit"></i></button>
                                <form onsubmit="submitForm(this, `delete`)" action="' . route('users.destroy', [$row->id]) . '" method="POST">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>';

                    // Update Modal
                    $data .= '<form method="POST" onsubmit="submitForm(this, `update`)" action="' . route('users.update', [$row->id]) . '" class="modal fade text-start" id="editModal' . $row->id . '"
                                tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input name="_method" type="hidden" value="PUT">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editUserLabel">Edit User</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input required type="text" name="username" value="' . $row->username . '" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input required type="text" name="name" value="' . $row->name . '" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="text" name="password" class="form-control">
                                                <small class="text-xs text-danger">Empty this field if you don&apos;t wanna change the password</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select name="role" class="form-control">
                                                    <option value="user" ' . ($row->role == 'user' ? 'selected' : '') . '>User</option>
                                                    <option value="admin" ' . ($row->role == 'admin' ? 'selected' : '') . '>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>';

                    // Show Modal
                    $data .= '<div class="modal fade text-start" id="showModal' . $row->id . '"
                            tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="showUserLabel">Show User</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input required type="text" value="' . $row->username . '" class="form-control" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input required type="text" value="' . $row->name . '" class="form-control" disabled>
                                        </div>
    
                                        <div class="form-group">
                                            <label>Role</label>
                                            <select name="role" class="form-control" disabled>
                                                <option value="user" ' . ($row->role == 'user' ? 'selected' : '') . '>User</option>
                                                <option value="admin" ' . ($row->role == 'admin' ? 'selected' : '') . '>Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>';

                    return $data;
                })
                ->make(true);
        }

        return view('users.index');
    }

    public function store(Request $request)
    {
        try {
            User::create([
                'username' => $request->username,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);
            return redirect()->back()->with('message', 'Data stored!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);
            $user->username = $request->username;
            $user->name = $request->name;
            $user->role = $request->role;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->save();
            return redirect()->back()->with('message', 'Data updated!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function destroy($id)
    {
        try {
            User::find($id)->delete();
            return redirect()->back()->with('message', 'Data deleted!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }
}
