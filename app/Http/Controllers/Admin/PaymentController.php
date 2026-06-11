<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['application.user', 'application.charityType'])->latest()->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $applicationId = $request->query('application_id');
        $application = Application::findOrFail($applicationId);
        
        if ($application->status !== 'approved') {
            return redirect()->route('admin.applications.show', $application)->with('error', 'Only approved applications can be paid.');
        }

        if ($application->payment) {
            return redirect()->route('admin.applications.show', $application)->with('error', 'Payment has already been made for this application.');
        }

        $gateways = PaymentGatewayService::getAvailableGateways();

        return view('admin.payments.create', compact('application', 'gateways'));
    }

    public function store(Request $request, PaymentGatewayService $paymentGateway)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'amount' => 'required|numeric|min:1',
            'payment_gateway' => 'required|string|in:fpx,toyyibpay,billplz',
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $application = Application::findOrFail($request->application_id);

        if ($application->payment) {
             return redirect()->route('admin.applications.show', $application)->with('error', 'Payment already exists.');
        }

        // Validate gateway & method combination
        if (!$paymentGateway->validateGateway($request->payment_gateway, $request->payment_method)) {
            return back()->withErrors(['payment_gateway' => 'Gateway atau kaedah pembayaran tidak sah.'])->withInput();
        }

        // Process payment through selected gateway
        $response = $paymentGateway->processPayment(
            $request->amount,
            $request->payment_gateway,
            $request->payment_method
        );

        Payment::create([
            'application_id' => $application->id,
            'amount' => $response['amount'],
            'transaction_id' => $response['transaction_id'],
            'status' => $response['status'],
            'payment_gateway' => $request->payment_gateway,
            'payment_method' => $request->payment_method,
            'gateway_reference' => $response['gateway_reference'],
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berjaya diproses melalui ' . ($response['gateway'] ?? $request->payment_gateway) . '.');
    }
}
