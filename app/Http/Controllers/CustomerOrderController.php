<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\PlaceOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('orders.listing.index');
    }

    /**
     * Returns a list of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $query = Order::ListForCustomer()->with(['product' => function ($q) {
            $q->select('id', 'title');
        }])->where('customer_id', auth()->user()->id);
        $query->when(request('search_key', false), function ($q, $search_key) {
            return $q->whereHas('product', function ($q1) use ($search_key) {
                $q1->where('title', "like", "%$search_key%");
            })->orWhere('quantity', 'like', "%$search_key%")
                ->orWhere('amount', 'like', "%$search_key%");
        });
        $orders = $query->orderBy('date_of_order', 'desc')->paginate(5);
        return view('orders.listing.list', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::CommonDropdown()->get();
        return view('orders.create.index', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceOrder $request)
    {
        $data = $request->validated();
        $product = Product::find($data['product_id']);
        $data = $data + ['amount' => $product->price * $data['quantity']];

        if ($order = auth()->user()->orders()->create($data)) {
            $order->order_no = "ORD-" . strtoupper(Str::random(8)) . $order->id;
            $order->update();
            if ($request['type'] == 'online') {
                auth()->user()->withdrawFloat($data['amount']);
            }
            return redirect()->route('orders')->with('success', "Your order has been placed.");
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to place order.']);
        }
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
            if ($order->type == 'online') {
                auth()->user()->depositFloat($order->amount);
            }
            return redirect()->route('orders')->with('success', "Your order no:{$order->order_no} has been cancelled.");
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to cancel order.']);
        }
    }
}
