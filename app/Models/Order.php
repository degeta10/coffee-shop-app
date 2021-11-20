<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = ['order_no', 'customer_id', 'product_id', 'quantity', 'amount', 'type', 'status', 'date_of_order', 'date_of_cancellation', 'date_of_delivery'];

    protected $casts = [
        'date_of_order' => 'datetime',
        'date_of_cancellation' => 'datetime',
        'date_of_delivery' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->date_of_order = Carbon::now();
        });
    }

    public function scopeListForCustomer($query)
    {
        return $query->select(
            'id',
            'product_id',
            'date_of_order',
            'order_no',
            'quantity',
            'type',
            'amount',
            'status',
        );
    }

    public function scopeListForAdmin($query)
    {
        return $query->select(
            'id',
            'product_id',
            'order_no',
            'quantity',
            'type',
            'amount',
            'status',
            'date_of_order',
            'date_of_delivery',
            'date_of_cancellation',
        );
    }

    /**
     * Get the customer that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    /**
     * Get the product that owns the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
