<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'total', 'status', 'payment_id', 'identify'];

    //retorna a ordem com os produtos
    public function products()
    {
    	//especifico os pivot
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }
    
    //atualiza o status
    public function changeStatus($paymentId, $status)
    {
    	//recupero a pro´pria módel 
        $this->where('payment_id', $paymentId)
                ->update(['status' => $status]);
    }
    
    //cadastra a ordem e os produtos dessa ordem
    public function newOrderProducts($totalCart, $paymentId, $identify, $itemsCart)
    {
        $order = $this->create([
            'user_id'       => auth()->user()->id,
            'total'         => $totalCart,
            'status'        => 'started',
            'payment_id'    => $paymentId,
            'identify'      => $identify,
        ]);
        
        
        $productsOrder = [];
        foreach($itemsCart as $item) {
            $idProduct = $item['item']->id;
            $productsOrder[$idProduct] = [
                'quantity'  => $item['qtd'],
                'price'     => $item['item']->price,
            ];
        }

        $order->products()->attach($productsOrder);
    }
}
