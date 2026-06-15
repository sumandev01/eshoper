<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Media;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductReview;
use App\Models\ProductReviewReply;
use App\Models\Size;
use App\Models\SubCategory;
use App\Models\TeamMember;
use App\Services\ProductFilterService;
use App\Services\ProductWebService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{
    private $webService;

    public function __construct(ProductWebService $webService)
    {
        $this->webService = $webService;
    }
    public function root()
    {
        $products = Product::latest('id')->where('status', 1)->get();
        $latestProducts = $products->take(8);
        $trendingProducts = $this->webService->getTrendingProducts();
        $categories = Category::latest('id')->withCount('products')->get();
        $brands = Brand::latest('id')->get();
        return view('web.index', compact('products', 'latestProducts', 'trendingProducts', 'categories', 'brands'));
    }

    public function products(Request $request, ProductFilterService $filterService)
    {
        $products = $filterService->filter($request);

        if ($request->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }

        $allProductIds = Product::whereStatus(1)->pluck('id');
        $shopSidebarData = $filterService->shopSidebar();
        [$minPrice, $maxPrice] = $filterService->getPriceRange($allProductIds);

        return view('web.products', compact('products', 'minPrice', 'maxPrice'), $shopSidebarData);
    }

    public function productDetails($slug)
    {
        // Make sure the product exists or not
        $product = Product::with(['sizes', 'colors', 'tags', 'details', 'media', 'inventories.media', 'inventories.color', 'inventories.size'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Related products by category and random products
        $relatedProducts = $this->webService->getRelatedProductsSinglePage($product);
        $productReview = ProductReview::whereProductId($product->id)->whereStatus(1)->get();

        $totalReviews = $productReview->count();
        $totalRatingSum = $productReview->sum('rating');
        $finalRating = 0;
        if ($totalReviews > 0) {
            $finalRating = round($totalRatingSum / $totalReviews);
        } else {
            $finalRating = 0;
        }

        $inventoriesJson = $product->inventories->map(function ($inventory) use ($product) {
            $imageUrl = null;
            if ($inventory->media) {
                $imageUrl = $inventory->media->medium_url;
            }

            return [
                'id' => $inventory->id,
                'size_id' => $inventory->size_id,
                'color_id' => $inventory->color_id,
                'color_name' => $inventory->color->name ?? 'N/A',
                'stock' => $inventory->stock,
                'price' => $inventory->use_main_price == 1 ? $product->price : $inventory->price,
                'discount' => $inventory->use_main_discount == 1 ? $product->discount : $inventory->discount,
                'image' => $imageUrl,
            ];
        });

        // Return view
        return view('web.single-product', [
            'product'         => $product,
            'sizes'           => $product->sizes,
            'colors'          => $product->colors,
            'tags'            => $product->tags,
            'relatedProducts' => $relatedProducts,
            'productReview'   => $productReview,
            'finalRating'     => $finalRating,
            'inventoriesJson' => $inventoriesJson
        ]);
    }

    public function getColorBySize(Request $request)
    {
        $colors = $this->webService->getColorsBySize($request);
        return response()->json([
            'colors' => $colors
        ]);
    }

    // Filters available colors based on the selected size. For single product page.
    public function getAvailableColors(Request $request)
    {
        return $this->webService->singleProductGetColorBySize($request);
    }

    public function getSignleProductVariantBySizeId(Request $request)
    {
        return $this->webService->singleProductGetVariantDetails($request);
    }

    public function contact()
    {
        return view('web.contact');
    }

    public function contactRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'required|phone:AUTO,INTERNATIONAL',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'phone.required' => 'Phone is required.',
            'phone.phone' => 'Invalid phone number.',
            'subject.required' => 'Subject is required.',
            'message.required' => 'Message is required.',
        ]);

        $userIp = $request->ip();

        try {
            $contact = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 0,
                'ip_address' => $userIp,
            ]);

            return redirect()->route('contact')->with('success', 'Sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('contact')->with('error', 'Please try again.');
        }
    }

    public function about()
    {
        $aboutPages = (object)AboutUs::pluck('key_value', 'key_name')->toArray();
        $mediaId = $aboutPages->image ?? null;
        $media = null;
        if (!empty($mediaId)) {
            $media = Storage::url(optional(Media::find($mediaId, ['*']))->src);
        } else {
            $media = asset('about.jpg');
        }
        $teamMembers = TeamMember::with('media')->orderBy('order', 'asc')->where('is_active', 1)->get();
        return view('web.about', compact('aboutPages', 'media', 'teamMembers'));
    }

    // Search suggestions for header search input
    public function searchSuggestions(Request $request)
    {
        if (!$request->filled('search')) {
            return response()->json([]);
        }

        $products = Product::whereStatus(1)
            ->where('name', 'like', '%' . $request->search . '%')
            ->with('media')
            ->select('id', 'name', 'slug', 'price', 'discount', 'media_id')
            ->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id'        => $product->id,
                    'name'      => $product->name,
                    'slug'      => $product->slug,
                    'price'     => $product->price,
                    'discount'  => $product->discount,
                    'thumbnail' => $product->thumbnail,
                ];
            });

        return response()->json($products);
    }

    // Category
    public function categoryProducts($slug, ProductFilterService $filterService)
    {
        $category = Category::whereSlug($slug)->firstOrFail();
        $products = $filterService->CategoryFilter(request(), $category);

        if (request()->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }
        // Get all product IDs for the category to fetch sidebar data and price range
        $allProductIds = Product::whereStatus(1)
            ->whereHas('details', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            })->pluck('id');

        $sidebarData = $filterService->categorySidebar($allProductIds);
        [$minPrice, $maxPrice] = $filterService->getPriceRange($allProductIds);

        return view('web.category-products', compact('products', 'category', 'minPrice', 'maxPrice'), $sidebarData);
    }

    // Subcategory
    public function subcategoryProducts($slug, ProductFilterService $filterService)
    {
        $subcategory = SubCategory::whereSlug($slug)->firstOrFail();
        $products = $filterService->CategoryFilter(request(), null, $subcategory);

        if (request()->ajax()) {
            return view('web.layouts.partial.product_list', compact('products'))->render();
        }
        // Get all product IDs for the subcategory to fetch sidebar data and price range
        $allProductIds = Product::whereStatus(1)
            ->whereHas('details', function ($q) use ($subcategory) {
                $q->where('sub_category_id', $subcategory->id);
            })->pluck('id');

        $sidebarData = $filterService->categorySidebar($allProductIds);
        [$minPrice, $maxPrice] = $filterService->getPriceRange($allProductIds);

        return view('web.subcategory-products', compact('products', 'subcategory', 'minPrice', 'maxPrice'), $sidebarData);
    }

    // Terms and Conditions
    public function termsAndConditions()
    {
        return view('web.terms');
    }

    // Faq
    public function faq()
    {
        $faqs = Faq::orderBy('order', 'asc')->get();
        return view('web.faq', compact('faqs'));
    }

    // Privacy Policy
    public function privacyPolicy()
    {
        return view('web.privacy-policy');
    }

    // Order Tracking
    public function orderTracking()
    {
        return view('web.order-tracking');
    }

    public function orderTrackingDetails(Request $request)
    {
        $request->validate([
            'order_number' => 'required',
        ]);

        $order = Order::where('order_number', $request->order_number)->first();
        if ($order) {
            return view('web.order-tracking-result', compact('order'));
        } else {
            return back()->with('error', 'Order not found');
        }
    }
}
