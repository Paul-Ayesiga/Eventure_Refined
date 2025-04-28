<?php

namespace App\Http\Controllers;

use App\Models\FlutterwaveTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FlutterwaveController extends Controller
{
    /**
     * Handle the callback from Flutterwave payment
     */
    public function handleCallback(Request $request)
    {
        Log::info('Flutterwave callback received', $request->all());

        // Get the transaction reference from the request
        $status = $request->status;
        $txRef = $request->tx_ref;
        $flwRef = $request->transaction_id;

        // Verify the transaction
        if ($status === 'successful' && $txRef && $flwRef) {
            // Find the transaction in our database
            $transaction = FlutterwaveTransaction::where('tx_ref', $txRef)->first();

            if ($transaction) {
                // Update transaction status
                $transaction->status = $status;
                $transaction->flw_ref = $flwRef;
                $transaction->save();

                // Store the transaction reference in session for the Livewire component to process
                Session::flash('flutterwave_payment_success', true);
                Session::flash('flutterwave_tx_ref', $txRef);
                Session::flash('flutterwave_flw_ref', $flwRef);

                // Redirect to the booking page to complete the process
                return redirect()->route('user.event.book', ['id' => $transaction->metadata['event_id']]);
            } else {
                Log::error('Flutterwave transaction not found', ['tx_ref' => $txRef]);
                return redirect()->route('home')->with('error', 'Transaction not found. Please contact support.');
            }
        } else {
            Log::warning('Flutterwave payment failed', [
                'status' => $status,
                'tx_ref' => $txRef
            ]);
            return redirect()->route('home')->with('error', 'Payment was not successful. Please try again.');
        }
    }

    /**
     * Handle webhook notifications from Flutterwave
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Flutterwave webhook received', $request->all());

        // Verify webhook signature
        $signature = $request->header('verif-hash');
        $secretHash = config('flutterwave.webhook_secret');

        if (!$signature || ($secretHash && $signature !== $secretHash)) {
            Log::warning('Invalid Flutterwave webhook signature', [
                'received' => $signature,
                'expected' => $secretHash
            ]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        // Process the webhook
        $payload = $request->all();

        if (isset($payload['event']) && $payload['event'] === 'charge.completed') {
            $data = $payload['data'];
            $txRef = $data['tx_ref'];
            $flwRef = $data['id'];
            $status = $data['status'];

            // Find the transaction
            $transaction = FlutterwaveTransaction::where('tx_ref', $txRef)->first();

            if ($transaction) {
                // Update transaction status
                $transaction->status = $status;
                $transaction->flw_ref = $flwRef;
                $transaction->save();

                Log::info('Flutterwave transaction updated via webhook', [
                    'tx_ref' => $txRef,
                    'status' => $status
                ]);
            } else {
                Log::error('Flutterwave transaction not found for webhook', ['tx_ref' => $txRef]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
