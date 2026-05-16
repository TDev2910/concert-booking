<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền thực hiện request này không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Định nghĩa các quy tắc validation cho việc đặt vé.
     */
    public function rules(): array
    {
        return [
            'concert_id' => 'required|uuid|exists:concerts,id',
            'items' => 'required|array|min:1',
            'items.*.category_id' => 'required|uuid|exists:ticket_categories,id',
            'items.*.quantity' => 'required|integer|min:1|max:10',
            'voucher_code' => 'nullable|string',
            'idempotency_key' => 'required|string|unique:orders,idempotency_key'
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi (nếu cần).
     */
    public function messages(): array
    {
        return [
            'idempotency_key.unique' => 'Yêu cầu này đã được xử lý trước đó (Idempotency error).',
            'items.required' => 'Vui lòng chọn ít nhất một hạng vé.',
        ];
    }
}
