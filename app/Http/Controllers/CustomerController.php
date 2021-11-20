<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Customer\CreateCustomer;
use App\Http\Requests\Admin\Customer\UpdateCustomer;
use App\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $index_route = 'admin.customer.index';
    protected $show_route = 'admin.customer.show';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customers.listing.index');
    }

    /**
     * Returns a list of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $query = User::whereHas("roles", function ($q) {
            $q->where("name", "customer");
        })->with('orders');
        $query->when(request('search_key', false), function ($q, $search_key) {
            return $q->where('name', 'like', "%$search_key%")
                ->orWhere('email', 'like', "%$search_key%")
                ->orWhere('phone', 'like', "%$search_key%")
                ->orWhere('address', 'like', "%$search_key%");
        });
        $customers = $query->orderBy('created_at', 'desc')->paginate(5);
        return view('admin.customers.listing.list', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomer $request)
    {
        if ($user = User::create($request->validated())) {
            return redirect()->route($this->show_route, [$user])->with('success', "Customer has been created.");
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create customer.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(User $customer)
    {
        return view('admin.customers.show.index', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit.index', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomer $request, User $customer)
    {
        if ($customer->update($request->validated())) {
            return redirect()->route($this->show_route, [$customer])->with('success', "Customer has been updated.");
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update customer.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer)
    {
        if ($customer->delete()) {
            return redirect()->route($this->index_route)->with('success', "Customer has been deleted.");
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to delete customer.']);
    }
}
