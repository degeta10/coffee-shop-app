<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\PlaceOrderRequest;
use App\Http\Requests\Api\Customer\UpdateOrderRequest;
use App\Http\Resources\Customer\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Order::ListForCustomer()->with(['product' => function ($q) {
            $q->select('id', 'title');
        }])->where('customer_id', auth()->user()->id);
        $query->when(request('search_key', false), function ($q, $search_key) {
            return $q->whereHas('product', function ($q1) use ($search_key) {
                $q1->where('title', "like", "%$search_key%")
                    ->orWhere('quantity', 'like', "%$search_key%")
                    ->orWhere('amount', 'like', "%$search_key%");
            });
        });
        $orders = $query->orderBy('date_of_order', 'desc')->paginate(10);
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceOrderRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();
            $product = Product::find($data['product_id']);
            $data = $data + ['amount' => $product->price * $data['quantity']];
            if ($order = auth()->user()->orders()->create($data)) {
                $order->order_no = "ORD-" . strtoupper(Str::random(8)) . $order->id;
                $order->update();
                if ($request['type'] == 'online') {
                    auth()->user()->withdrawFloat($data['amount']);
                }
                return response()->json([
                    'message' => 'Your order has been placed.'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Failed to place order.'
                ], Response::HTTP_BAD_REQUEST);
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Order $order, UpdateOrderRequest $request)
    {
        return DB::transaction(function () use ($request, $order) {
            $data = $request->validated();
            $product = Product::find($data['product_id']);
            $data = $data + ['amount' => $product->price * $data['quantity']];

            if ($order->type == 'cod' && $data['type'] == 'cod') {
                if ($order->update($data)) {
                    return response()->json([
                        'message' => 'Your order has been updated.'
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => 'Failed to update order.'
                    ], Response::HTTP_BAD_REQUEST);
                }
            } else {
                if ($data['type'] == 'cod') {
                    auth()->user()->depositFloat($order->amount);
                    if ($order->update($data)) {
                        auth()->user()->withdrawFloat($data['amount']);
                        return response()->json([
                            'message' => 'Your order has been updated.'
                        ], Response::HTTP_OK);
                    } else {
                        return response()->json([
                            'message' => 'Failed to update order.'
                        ], Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    if ($order->amount + auth()->user()->balanceFloat >= $data['amount']) {
                        auth()->user()->depositFloat($order->amount);
                        if ($order->update($data)) {
                            auth()->user()->withdrawFloat($data['amount']);
                            return response()->json([
                                'message' => 'Your order has been updated.'
                            ], Response::HTTP_OK);
                        } else {
                            return response()->json([
                                'message' => 'Failed to update order.'
                            ], Response::HTTP_BAD_REQUEST);
                        }
                    } else {
                        return response()->json([
                            'message' => 'Insufficient wallet balance for updation'
                        ], Response::HTTP_NOT_IMPLEMENTED);
                    }
                }
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Order $order)
    {
        return DB::transaction(function () use ($order) {
            if ($order->update(['status' => 'cancelled'])) {
                if ($order->type == 'online') {
                    auth()->user()->depositFloat($order->amount);
                }
                return response()->json([
                    'message' => "Your order no:{$order->order_no} has been cancelled."
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'Failed to cancel order.'
                ], Response::HTTP_BAD_REQUEST);
            }
        });
    }
}
