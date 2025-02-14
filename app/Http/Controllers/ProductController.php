<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    private function idrFormat($number)
    {
        return "Rp " . number_format($number, 0, ',', '.');
    }

    private function getCategoriesOptions($row, $categories)
    {
        $string = "";
        foreach ($categories as $category) {
            $string .= '<option value="' . $category->id . '" ' . ($row->category_id == $category->id ? 'selected' : '') . '>' . $category->name . '</option>';
        }

        return $string;
    }

    public function index()
    {
        $categories = Category::all();
        if (request()->ajax()) {
            $data = Product::with('category')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('base_price', function ($row) {
                    return $this->idrFormat($row->base_price);
                })
                ->addColumn('price', function ($row) {
                    return $this->idrFormat($row->price);
                })
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('action', function ($row) {
                    $categories = Category::all();
                    $data = '<div class="d-flex justify-content-center">
                                <button data-bs-toggle="modal" data-bs-target="#showModal' . $row->id . '" class="btn btn-success me-2"><i class="fas fa-eye"></i></button>
                                <button data-bs-toggle="modal" data-bs-target="#editModal' . $row->id . '" class="btn btn-info me-2"><i class="fas fa-edit"></i></button>
                                <form onsubmit="submitForm(this, `delete`)" action="' . route('products.destroy', [$row->id]) . '" method="POST">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>';

                    // Update Modal
                    $data .= '<form method="POST" onsubmit="submitForm(this, `update`)" action="' . route('products.update', [$row->id]) . '" class="modal fade text-start" id="editModal' . $row->id . '"
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
                                                <label for="name">Name</label>
                                                <input required type="text" id="name" name="name" value="' . $row->name . '" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp.</span>
                                                    <input required type="number" name="price" value="' . $row->price . '" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="pasword">Base Price</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp.</span>
                                                    <input required type="number" id="base_price" value="' . $row->base_price . '" name="base_price" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="category">Category</label>
                                                <select name="category_id" id="category" class="form-control" value="' . $row->category->name . '">' .
                        $this->getCategoriesOptions($row, $categories)
                        . '</select>
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
                                            <label for="name">Name</label>
                                            <input disabled type="text" id="name" name="name" value="' . $row->name . '" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp.</span>
                                                <input disabled type="number" name="price" value="' . $row->price . '" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="pasword">Base Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp.</span>
                                                <input disabled type="number" id="base_price" value="' . $row->base_price . '" name="base_price" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select name="category_id" id="category" disabled class="form-control" value="' . $row->category->name . '">
                                                    <option value="' . $row->category->id . '">' . $row->category->name . '</option>
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

        return view('products.index', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        try {
            Product::create([
                'name' => $request->name,
                'base_price' => $request->base_price,
                'price' => $request->price,
                'category_id' => $request->category_id
            ]);
            return redirect()->back()->with('message', 'Data stored!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Product::find($id)->update([
                'name' => $request->name,
                'base_price' => $request->base_price,
                'price' => $request->price,
                'category_id' => $request->category_id
            ]);
            return redirect()->back()->with('message', 'Data updated!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }

    public function destroy($id)
    {
        try {
            Product::find($id)->delete();
            return redirect()->back()->with('message', 'Data deleted!')->with('status', 'success');
        } catch (Exception $e) {
            return redirect()->back()->with('message', $e->getMessage())->with('status', 'error');
        }
    }
}
