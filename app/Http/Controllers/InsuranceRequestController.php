<?php

namespace App\Http\Controllers;

use App\Models\NewInsuranceRequest; // Adjust the model name as per your actual model
use Illuminate\Http\Request;

class InsuranceRequestController extends Controller
{
    public function index()
    {
        $insuranceRequests = NewInsuranceRequest::all(); // Fetch all insurance requests

        return view('admin.insurance-requests.index', [
            'insuranceRequests' => $insuranceRequests,
        ]);
    }

    public function updateState(Request $request, $id)
    {
        $insuranceRequest = NewInsuranceRequest::findOrFail($id);

        // Update the state based on the input
        $insuranceRequest->update([
            'state' => $request->state,
        ]);

        return redirect()->route('admin.insurance-requests')
            ->with('success', 'Insurance request state updated successfully.');
    }
}
