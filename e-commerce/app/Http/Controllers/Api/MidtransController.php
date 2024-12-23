<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class MidtransController extends Controller
{
    public function callback(Request $request): \Illuminate\Http\JsonResponse
    {
        $transaction = Transaction::where('order_id', $request->order_id)->first();
        if (!$transaction) {
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 200);
        }
        $transaction->payment_type = $request->payment_type;
        $transaction->payment_method = $this->getPaymentMethod($request);
        // Jika transaksi berhasil
        if ($request->transaction_status == "settlement") {
            $transaction->transaction_status = "success";
        $transaction->save();
            // Jika transaksi pending
        } else if ($request->transaction_status == "pending") {
            $transaction->transaction_status = "pending";
            $transaction->save();
            // jika transaksi gagal
        } else {
            $transaction->transaction_status = "failed";
            $transaction->save();
        }
        return response()->json(['status' => 'success']);
    }

    private function getPaymentMethod($callback): string
    {
        if ($callback->payment_type == 'credit_card') {
            return 'Credit Card';
        } else if ($callback->payment_type == 'bank_transfer') {
            return $callback->va_numbers[0]->bank;
        } else if ($callback->payment_type == 'echannel') {
            return 'Mandiri';
        } else if ($callback->payment_type == 'qris') {
            return $callback->acquirer;
        } else if ($callback->payment_type == 'cstore') {
            return $callback->store;
        } else {
            return 'Unknown';
        }
    }
}
