<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
     // Listar todos los pagos
    public function index()
    {
         $payments = Payment::all()->map(function ($payment) {
            $payment->voucher = url('storage/' . $payment->voucher);
            return $payment;
        });

        return response()->json($payments, 200);
    }

    // Mostrar un pago específico
    public function show($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        // Agregar URL completa al campo voucher
        $payment->voucher = url('storage/' . $payment->voucher);

        return response()->json($payment, 200);
    }

    // Crear un nuevo pago
    public function store(Request $request)
    {
        $request->validate([
            'date'    => 'required|date',
            'bank'    => 'required|string|max:255',
            'mount'   => 'required|numeric',
            'voucher' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $voucherPath = $request->file('voucher')->store('vouchers', 'public');

        $payment = Payment::create([
            'date'    => $request->date,
            'bank'    => $request->bank,
            'mount'   => $request->mount,
            'voucher' => $voucherPath,
        ]);

        
        return response()->json($payment, 201);
    }

    // Actualizar un pago existente
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        $request->validate([
            'date'    => 'sometimes|date',
            'bank'    => 'sometimes|string|max:255',
            'mount'   => 'sometimes|numeric',
            'voucher' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('voucher')) {
            if ($payment->voucher && Storage::disk('public')->exists($payment->voucher)) {
                Storage::disk('public')->delete($payment->voucher);
            }
            $voucherPath = $request->file('voucher')->store('vouchers', 'public');
            $payment->voucher = $voucherPath;
        }

        if ($request->has('date')) {
            $payment->date = $request->date;
        }
        if ($request->has('bank')) {
            $payment->bank = $request->bank;
        }
        if ($request->has('mount')) {
            $payment->mount = $request->mount;
        }

        $payment->save();

        return response()->json($payment, 200);
    }

    // Eliminar un pago
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Pago no encontrado'], 404);
        }

        if ($payment->voucher && Storage::disk('public')->exists($payment->voucher)) {
            Log::debug("entra aqui");
            $deleted = Storage::disk('public')->delete($payment->voucher);
            Log::debug('Archivo borrado? ', ['deleted' => $deleted]);
            Log::debug($payment->voucher);
        }

        $payment->delete();

        return response()->json(['message' => 'Pago eliminado correctamente'], 200);
    }
}
