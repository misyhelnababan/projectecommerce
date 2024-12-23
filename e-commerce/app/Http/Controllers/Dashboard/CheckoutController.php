<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\DashboardController;
use App\Models\Product;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CheckoutController extends DashboardController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setTitle('Checkout');

        $this->addBreadcrumb('Dashboard', route('dashboard.index'));
        $this->addBreadcrumb('Checkout');

        $this->data['products'] = Product::all();

        return view('dashboard.checkout.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SANDBOX_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_SANDBOX_CLIENT_KEY');
        Config::$isProduction = false;
        Config::$is3ds = false;

        try {
            // Untuk menambahkan produk dari database.
            $productId = $request->product_id;
            $productQuantity = $request->product_quantity;
            $product = Product::whereIn('id', $productId)->get();
            
            // filter produk yang quantity nya lebih dari 0
            $product = $product->filter(function ($p) use ($productId, $productQuantity) {
                $index = array_search($p->id, $productId);
                return $productQuantity[$index] > 0;
            });

            $courier = explode('-', $request->courier_service);
            $courierType = $request->courier_type;
            $courierService = $courier[0];
            $courierDescription = $courier[1];
            $courierServicePrice = $courier[2];

            // Struktur request body ke midtrans
            $orderId = rand();
            $transaction_details = array(
                'order_id' => $orderId,
            );
            $item_details = array();

            $total = 0;
            foreach ($product as $p) {
                $total += $p->price;
                $item_details[] = array(
                    'id' => $p->id,
                    'price' => $p->price,
                    'quantity' => $productQuantity[array_search($p->id, $productId)],
                    'name' => $p->name,
                );
            }
            $total += $courierServicePrice;
            $item_details[] = array(
                'id' => 'courier',
                'price' => $courierServicePrice,
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . strtoupper($courierType) . ' - ' . $courierDescription . ')',
            );

            $transaction_details['gross_amount'] = $total;

            $customer_details = array(
                'first_name' => "Nicky",
                'last_name' => "Erlangga",
                'email' => "nickyerlanggaz@gmail.com",
                "phone" => "+628123456",
            );

            $json = [
                'transaction_details' => $transaction_details,
                'item_details' => $item_details,
                'customer_details' => $customer_details,
            ];

            DB::beginTransaction();
            $transaction = Transaction::create([
                'order_id' => $orderId,
                'customer_name' => $customer_details['first_name'] . ' ' . $customer_details['last_name'],
                'gross_amount' => $transaction_details['gross_amount'],
                'courier' => strtoupper($courierType),
                'courier_service' => $courierService . '-' . $courierDescription,
                'transaction_status' => 'pending',
                'transaction_time' => now(),
            ]);

            $transaction->details()->createMany(
                array_map(function ($item) {
                    return [
                        'product_id' => $item['id'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                    ];
                }, array_filter($item_details, function ($item) {
                    return $item['id'] !== 'courier';
                }))
            );
            DB::commit();

            $snap = Snap::createTransaction($json);
            return redirect($snap->redirect_url);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
