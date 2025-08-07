<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * Get all transactions with current balance
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            // Get all transactions ordered by newest first (as per OpenAPI spec)
            $transactions = Transaction::orderBy('created_at', 'desc')->get();
            
            // Get the current balance (from the most recent transaction)
            $currentBalance = $transactions->first()?->balance ?? 0;
            
            return response()->json([
                'transactions' => $transactions,
                'current_balance' => $currentBalance
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve transactions',
                'error' => 'Internal server error occurred'
            ], 500);
        }
    }
    
    /**
     * Create a new transaction
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the incoming request with detailed error messages
            $validated = $request->validate([
                'account_id' => [
                    'required',
                    'string',
                    'uuid'  // UUID validation rule
                ],
                'amount' => [
                    'required',
                    'numeric',
                    'not_in:0'
                ]
            ], [
                'account_id.required' => 'The account id field is required.',
                'account_id.string' => 'The account id must be a valid string.',
                'account_id.uuid' => 'The account id must be a valid UUID.',
                'amount.required' => 'The amount field is required.',
                'amount.numeric' => 'The amount must be a valid number.',
                'amount.not_in' => 'The amount field cannot be 0.',
            ]);
            
            // Get the last transaction for this account to calculate new balance
            $lastTransaction = Transaction::where('account_id', $validated['account_id'])
                ->orderBy('created_at', 'desc')
                ->first();
                
            // Calculate new balance
            $previousBalance = $lastTransaction ? $lastTransaction->balance : 0;
            $newBalance = $previousBalance + $validated['amount'];
            
            // Create the transaction
            $transaction = Transaction::create([
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'balance' => $newBalance
            ]);
            
            // Return success response with 201 status (Created)
            return response()->json([
                'message' => 'Transaction created successfully',
                'transaction' => $transaction
            ], 201);
            
        } catch (ValidationException $e) {
            // Return validation errors with 422 status (Unprocessable Entity)
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            // Return server error with 500 status
            return response()->json([
                'message' => 'Failed to create transaction',
                'error' => 'Internal server error occurred'
            ], 500);
        }
    }
}