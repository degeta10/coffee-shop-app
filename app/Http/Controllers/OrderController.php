<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Order\CreateOrder;
use App\Http\Requests\Admin\Order\UpdateOrder;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $index_route = 'admin.order.index';
    protected $show_route = 'admin.order.show';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.orders.listing.index');
    }

    /**
     * Returns a list of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $query = Order::ListForadmin()->with(['product' => function ($q) {
            $q->select('id', 'title');
        }]);
        $query->when(request('search_key', false), function ($q, $search_key) {
            return $q->whereHas('product', function ($q1) use ($search_key) {
                $q1->where('title', "like", "%$search_key%");
            })->orWhere('quantity', 'like', "%$search_key%")
                ->orWhere('amount', 'like', "%$search_key%");
        });
        $orders = $query->orderBy('date_of_order', 'desc')->paginate(5);
        return view('admin.orders.listing.list', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = User::whereHas("roles", function ($q) {
            $q->where("name", "customer");
        })->get();
        $products = Product::CommonDropdown()->get();
        return view('admin.orders.create.index', compact('products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrder $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $product = Product::find($data['product_id']);
            $data = $data + ['amount' => $product->price * $data['quantity']];

            if ($order = Order::create($data)) {
                $order->order_no = "ORD-" . strtoupper(Str::random(8)) . $order->id;
                $order->update();
                if ($request['type'] == 'online') {
                    $order->customer->withdrawFloat($data['amount']);
                }
                return redirect()->route($this->show_route, [$order])->with('success', "Order has been created.");
            } else {
                return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create order.']);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('admin.orders.show.index', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $customers = User::whereHas("roles", function ($q) {
            $q->where("name", "customer");
        })->get();
        $products = Product::CommonDropdown()->get();
        return view('admin.orders.edit.index', compact('order', 'products', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrder $request, Order $order)
    {
        return DB::transaction(function () use ($request, $order) {
            $data = $request->validated();
            $product = Product::find($data['product_id']);
            $data = $data + ['amount' => $product->price * $data['quantity']];

            if ($order->type == 'cod' && $data['type'] == 'cod') {
                if ($order->update($data)) {
                    return redirect()->route($this->show_route, [$order])->with('success', "Order no:{$order->order_no} has been updated.");
                }
            } else {
                if ($data['type'] == 'cod') {
                    $order->customer->depositFloat($order->amount);
                    if ($order->update($data)) {
                        $order->customer->withdrawFloat($data['amount']);
                        return redirect()->route($this->show_route, [$order])->with('success', "Order no:{$order->order_no} has been updated.");
                    }
                } else {
                    if ($order->amount + $order->customer->balanceFloat >= $data['amount']) {
                        $order->customer->depositFloat($order->amount);
                        if ($order->update($data)) {
                            $order->customer->withdrawFloat($data['amount']);
                            return redirect()->route($this->show_route, [$order])->with('success', "Order no:{$order->order_no} has been updated.");
                        }
                    } else {
                        return redirect()->back()->withInput()->withErrors(['error' => 'Insufficient wallet balance for updation.']);
                    }
                }
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update order.']);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if ($order->type == 'online' && $order->status == 'in-progress') {
            $order->customer->depositFloat($order->amount);
            if ($order->delete()) {
                return redirect()->route($this->index_route)->with('success', "Order has been deleted.");
            }
        } else {
            if ($order->delete()) {
                return redirect()->route($this->index_route)->with('success', "Order has been deleted.");
            }
        }
        return redirect()->back()->withInput()->withErrors(['error' => 'Failed to delete order.']);
    }

    /**
     * Cancel the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cancel(Order $order)
    {
        if ($order->update(['status' => 'cancelled'])) {
            if ($order->type == 'online' && $order->status == 'in-progress') {
                $order->customer->depositFloat($order->amount);
            }
            return redirect()->route($this->index_route)->with('success', "Order no:{$order->order_no} has been cancelled.");
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to cancel order.']);
        }
    }

    /**
     * Cancel the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function deliver(Order $order)
    {
        if ($order->update(['status' => 'delivered'])) {
            return redirect()->route($this->index_route)->with('success', "Order no:{$order->order_no} has been set as delivered.");
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to set order as delivered.']);
        }
    }
}
