<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Product\CreateProduct;
use App\Http\Requests\Admin\Product\UpdateProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $index_route = 'admin.product.index';
    protected $show_route = 'admin.product.show';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.products.listing.index');
    }

    /**
     * Returns a list of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $query = Product::query();
        $query->when(request('search_key', false), function ($q, $search_key) {
            return $q->where('title', 'like', "%$search_key%")
                ->orWhere('price', 'like', "%$search_key%");
        });
        $products = $query->orderBy('created_at', 'desc')->paginate(5);
        return view('admin.products.listing.list', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProduct $request)
    {
        if ($product = Product::create($request->validated())) {
            return redirect()->route($this->show_route, [$product])->with('success', "Product has been created.");
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create product.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.products.show.index', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit.index', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProduct $request, Product $product)
    {
        if ($product->update($request->validated())) {
            return redirect()->route($this->show_route, [$product])->with('success', "Product has been updated.");
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update product.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->delete()) {
            return redirect()->route($this->index_route)->with('success', "Product has been deleted.");
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to delete product.']);
    }
}
