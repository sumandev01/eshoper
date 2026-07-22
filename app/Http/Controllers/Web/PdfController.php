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
        $media = Media::find($logoId);
        $logoBase64 = null;
        
        if ($media && $media->src) {
            $path = Storage::disk('public')->path($media->src);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        
        if (!$logoBase64) {
            $path = public_path('default.webp');
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }
        
        $order = Order::findOrFail($orderId);
        \Illuminate\Support\Facades\Gate::authorize('view', $order);
        $orderProducts = OrderProduct::whereOrderId($order->id)->get();
        $billingAddress = BillingAddress::whereOrderId($order->id)->first();
        $shippingAddress = ShippingAddress::whereOrderId($order->id)->first();
        
        $pdf = Pdf::setOption(['isPhpEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.invoice', compact('order', 'orderProducts', 'shippingAddress', 'logoBase64'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}
