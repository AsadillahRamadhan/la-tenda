<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BudgetController extends Controller
{
    private function idrFormat($number)
    {
        return "Rp " . number_format($number, 0, ',', '.');
    }
    public function index()
    {
        if (request()->ajax()) {
            $data = Budget::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($row) {
                    return $this->idrFormat($row->price);
                })
                ->addColumn('detail', function ($row) {
                    return $row->detail;
                })
                ->addColumn('date', function ($row) {
                    return $row->date;
                })
                ->addColumn('action', function ($row) {
                    $data = '<div class="d-flex justify-content-center">
                                <button data-bs-toggle="modal" data-bs-target="#showModal' . $row->id . '" class="btn btn-success me-2"><i class="fas fa-eye"></i></button>
                                <button data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '" class="btn btn-info me-2"><i class="fas fa-edit"></i></button>
                                <form onsubmit="submitForm(this, `delete`)" action="' . route('budgets.destroy', [$row->id]) . '" method="POST">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>';

                    // Update Modal
                    $data .= '<form method="POST" onsubmit="submitForm(this, `update`)" action="' . route('budgets.update', [$row->id]) . '" class="modal fade text-start" id="editModal' . $row->id . '"
                                tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input name="_method" type="hidden" value="PUT">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="editProductLabel">Edit Product</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp.</span>
                                                    <input required type="number" name="price" value="' . $row->price . '" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pasword">Details</label>
                                                <div class="input-group">
                                                    <input required type="textarea" id="detail" value="' . $row->detail . '" name="detail" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pasword">Date</label>
                                                <div class="input-group">
                                                    <input required type="date" id="date" value="' . $row->date . '" name="date" class="form-control">
                                                </div>
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
                                        <h1 class="modal-title fs-5" id="showProductLabel">Show Product</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp.</span>
                                                    <input required type="number" name="price" value="' . $row->price . '" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pasword">Details</label>
                                                <div class="input-group">
                                                    <input required type="textarea" id="detail" value="' . $row->detail . '" name="detail" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pasword">Date</label>
                                                <div class="input-group">
                                                    <input required type="date" id="date" value="' . $row->date . '" name="date" class="form-control" disabled>
                                                </div>
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

        return view('budgets.index');
    }

    public function store(Request $request)
    {
        try {
            Budget::create([
                'detail' => $request->detail,
                'price' => $request->price,
                'date' => $request->date
            ]);
            return redirect()->back()->with('message', 'Data stored!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Budget::find($id)->update([
                'detail' => $request->detail,
                'price' => $request->price,
                'date' => $request->date
            ]);
            return redirect()->back()->with('message', 'Data updated!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function destroy($id)
    {
        try {
            Budget::find($id)->delete();
            return redirect()->back()->with('message', 'Data deleted!')->with('status', 'success');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }
}
