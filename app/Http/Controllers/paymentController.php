<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;
use App\Models\payment;
use App\Models\invoice;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class paymentController extends Controller
{


     public function create_bkp(Request $request){

        $validator=validator::make($request->all(),[
            'invoice_id'=>'required',
            'project_id'=>'required',
            'payment_date'=>'required|date',
            'payment_mode'=>'required',
            'payment_proof'=>'required',
            'payment_file' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,pdf|max:4048',
            'amount'        => 'required'
          
           
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'error'=>'Validation Error',
                'message'=> $validator->errors()->first()
            ]);
        }

         try {
     
        $file = $request->file('payment_file');
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payments', $filename, 'public');

        $payment = Payment::create([
           
            'project_id'    => $request->project_id,
            'payment_date'  => $request->payment_date,
            'payment_mode'  => $request->payment_mode,
            'payment_proof' => $request->payment_proof,
            'amount' => $request->amount,
            // save the relative storage path, e.g. "payments/abcd1234.pdf"
            'payment_file'  => $path,
            'updated_by'    => auth()->user()->id,
        ]);


         $invoice = invoice::findOrFail($request->invoice_id);
        //  $invoice = invoice::where('')($request->project_id);
        $invoice->advancePaid += $request->amount;
        $invoice->balance     = $invoice->totalAmount - $invoice->advancePaid;
        $invoice->save();

        return response()->json([
            'status'  => true,
            'message' => 'Payment record created successfully!',
            'data'    => $payment,
            'invoice' => $invoice,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'error'   => 'Exception Error',
            'message' => $e->getMessage(),
        ], 500);
    }

       }

       public function create(Request $request)
{
    $validator = validator::make($request->all(), [
        'invoice_id'     => 'required',
        'project_id'     => 'required',
        'payment_date'   => 'required|date',
        'payment_mode'   => 'required',
        'payment_proof'  => 'required',
        'payment_file'   => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,pdf|max:4048',
        'amount'         => 'required|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'error'   => 'Validation Error',
            'message' => $validator->errors()->first()
        ]);
    }

    try {
        $path = null;

        // ✅ only process file if it's uploaded
        if ($request->hasFile('payment_file')) {
            $file = $request->file('payment_file');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payments', $filename, 'public');
        }

        // ✅ create payment
        $payment = payment::create([
            'project_id'    => $request->project_id,
            'payment_date'  => $request->payment_date,
            'payment_mode'  => $request->payment_mode,
            'payment_proof' => $request->payment_proof,
            'amount'        => $request->amount,
            'payment_file'  => $path,
            'updated_by'    => auth()->user()->id,
        ]);

        // ✅ update invoice
        $invoice = invoice::findOrFail($request->invoice_id);
        $invoice->advancePaid += $request->amount;
        $invoice->balance = $invoice->totalAmount - $invoice->advancePaid;
        $invoice->save();

        // Update in Project Tabel
        $project=project::findOrFail($request->project_id);
         $project->balanceRemaining=$invoice->balance;
         $project->save();



        return response()->json([
            'status'  => true,
            'message' => 'Payment record created successfully!',
            'data'    => $payment,
            'invoice' => $invoice,
            'project' => $project,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'error'   => 'Exception Error',
            'message' => $e->getMessage(),
        ], 500);
    }
}


public function update(Request $request,$id){

     $payment = payment::findOrFail($id);

    $validated = $request->validate([
        'payment_date' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'payment_mode' => 'required|string',
        'notes' => 'nullable|string',
        'payment_file' => 'nullable',
          'payment_proof' => 'required', // "Yes" or "No"
        // 'payment_proof' => 'required'
    ]);

    $payment->payment_date = $validated['payment_date'];
    $payment->amount = $validated['amount'];
    $payment->payment_mode = $validated['payment_mode'];
    // $payment->notes = $validated['notes'] ?? null;

    // handle file upload
   
     $payment->payment_proof = $request->input('payment_proof') === "Yes" ? 1 : 0;
     if ($request->hasFile('payment_file')) {
            $file = $request->file('payment_file');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('payments', $filename, 'public');

               $payment->payment_file = $path;
        }



   
    $payment->save();


    
    if ($payment->project_id) {
    $invoice = Invoice::where('project_id', $payment->project_id)->first();
    if ($invoice) {
        // get total of all payments for this project
        $totalPaid = Payment::where('project_id', $payment->project_id)->sum('amount');
        $invoice->advancePaid = $totalPaid;
        $invoice->save();
    }


      return response()->json([
        'message' => 'Payment updated successfully',
        'payment' => $payment
    ]);

}


  


}


public function destroy($id)
{
    $payment = payment::findOrFail($id);

    // Delete the file from storage if it exists
    if ($payment->payment_file) {
        $oldPath = str_replace('/storage/', '', $payment->payment_file);
        Storage::disk('public')->delete($oldPath);
    }

    $projectId = $payment->project_id;

    // Delete the payment record itself
    $payment->delete();

    // Optionally update invoice advancePaid
    if ($projectId) {
        $invoice = invoice::where('project_id', $projectId)->first();
        if ($invoice) {
            $totalPaid = payment::where('project_id', $projectId)->sum('amount');
            $invoice->advancePaid = $totalPaid;
            $invoice->save();
        }
    }

    return response()->json([
        'message' => 'Payment deleted successfully'
    ]);
}

}
