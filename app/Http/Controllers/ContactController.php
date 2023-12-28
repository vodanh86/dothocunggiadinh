<?php

namespace App\Http\Controllers;

use App\Models\BranchModel;
use App\Models\ContactModel;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ResponseFormattingTrait;

    public function createContactForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'products' => 'required|array',
            'products.*' => 'string|max:255',
            'note' => 'nullable|string|max:2000',
        ]);
        $currentTimestamp = now()->timestamp;

        $contact = ContactModel::create([
            'name' => $validatedData['name'],
            'phone_number' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'content' => implode(', ', $validatedData['products']),
            'note' => $validatedData['note'],
            'reply' => 0,
            'status' => 1,
            'created_at' => $currentTimestamp,
        ]);
        if ($contact === null) {
            $response = $this->_formatBaseResponse(400, null, 'Tạo dữ liệu thất bại');
        } else {
            $response = $this->_formatBaseResponse(201, 'OK', 'Tạo dữ liệu thành công');
        }
        return response()->json($response);
    }
}
