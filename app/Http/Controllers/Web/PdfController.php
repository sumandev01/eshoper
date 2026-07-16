<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Models\Media;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Setting;
use App\Models\ShippingAddress;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function downloadInvoice($orderId)
    {
        $logoId = Setting::whereKeyName('site_logo')->first()?->key_value;
        $logo = Storage::url(optional(Media::find($logoId, ['*']))->src ?? asset('default.webp'));
        $logoBase64 = null;
        
        if ($logo) {
            if (str_starts_with($logo, 'data:image')) {
                $logoBase64 = $logo;
            } else {
                $path = public_path($logo);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
        }
        
        $order = Order::findOrFail($orderId);
        $this->authorize('view', $order);
        $orderProducts = OrderProduct::whereOrderId($order->id)->get();
        $billingAddress = BillingAddress::whereOrderId($order->id)->first();
        $shippingAddress = ShippingAddress::whereOrderId($order->id)->first();
        
        $pdf = Pdf::setOption(['isPhpEnabled' => true])->loadView('pdf.invoice', compact('order', 'orderProducts', 'shippingAddress', 'logoBase64'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
