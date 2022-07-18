<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Resources\CartItemCollection as CartItemCollection;
use App\Models\products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }

        $cart = Cart::create([
            'id' => md5(uniqid(rand(), true)),
            'key' => md5(uniqid(rand(), true)),
            'userID' => isset($userID) ? $userID : null,

        ]);
        return response()->json([
            'Message' => 'A new cart have been created for you!',
            'cartToken' => $cart->id,
            'cartKey' => $cart->key,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cartKey = $request->input('cartKey');
        if ($cart->key == $cartKey) {
            return response()->json([
                'cart' => $cart->id,
                'Items in Cart' => new CartItemCollection($cart->items),
            ], 200);

        } else {

            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request

     */
    public function destroy(Cart $cart, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cartKey = $request->input('cartKey');

        if ($cart->key == $cartKey) {
            $cart->delete();
            return response()->json(null, 204);
        } else {

            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }
    }
    /**
     * Adds Products to the given Cart;
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     * @return void
     */
    public function add(Cart $cart, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
            'productID' => 'required',
            'quantity' => 'required|numeric|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $cartKey = $request->input('cartKey');
        $productID = $request->input('productID');
        $quantity = $request->input('quantity');

        //Check if the CarKey is Valid
        if ($cart->key == $cartKey) {
            //Check if the proudct exist or return 404 not found.
            try { $Product = products::findOrFail($productID);} catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'The Product you\'re trying to add does not exist.',
                ], 404);
            }

            //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
            $cartItem = CartItem::where(['cart_id' => $cart->getKey(), 'product_id' => $productID])->first();
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                CartItem::where(['cart_id' => $cart->getKey(), 'product_id' => $productID])->update(['quantity' => $quantity]);
            } else {
                CartItem::create(['cart_id' => $cart->getKey(), 'product_id' => $productID, 'quantity' => $quantity]);
            }

            return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);

        } else {

            return response()->json([
                'message' => 'The CarKey you provided does not match the Cart Key for this Cart.',
            ], 400);
        }

    }
}
