<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Category::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('action', function ($row) {
                    $data = '<div class="d-flex justify-content-center">
                                <button data-bs-toggle="modal" data-bs-target="#showModal' . $row->id . '" class="btn btn-success me-2"><i class="fas fa-eye"></i></button>
                                <button data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '" class="btn btn-info me-2"><i class="fas fa-edit"></i></button>
                                <form onsubmit="submitForm(this, `delete`)" action="' . route('categories.destroy', [$row->id]) . '" method="POST">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>';

                    // Update Modal
                    $data .= '<form method="POST" onsubmit="submitForm(this, `update`)" action="' . route('categories.update', [$row->id]) . '" class="modal fade text-start" id="editModal' . $row->id . '"
                                tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input name="_method" type="hidden" value="PUT">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editCategoryLabel">Edit Category</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input required type="text" name="name" value="' . $row->name . '" class="form-control">
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
                                        <h1 class="modal-title fs-5" id="showCategoryLabel">Show Category</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input required type="text" value="' . $row->name . '" class="form-control" disabled>
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

        return view('categories.index');
    }

    public function store(Request $request)
    {
        try {
            Category::create([
                'name' => $request->name,
            ]);
            return redirect()->back()->with('message', 'Data stored!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Category::find($id)->update([
                'name' => $request->name
            ]);
            return redirect()->back()->with('message', 'Data updated!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function destroy($id)
    {
        try {
            Category::find($id)->delete();
            return redirect()->back()->with('message', 'Data deleted!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }
}
