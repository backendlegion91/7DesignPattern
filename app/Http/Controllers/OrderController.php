<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Builders\OrderBuilder;
use App\Services\OrderService;
use App\Decorators\BaseOrder;
use App\Decorators\DiscountDecorator;
use App\Observers\OrderSubject;
use App\Observers\AdminNotifier;
use App\Strategies\PaymentContext;
use App\Strategies\PayPalPayment;
use App\Strategies\CreditCardPayment;
use App\Adapters\PaymentAdapter;
use App\Adapters\LegacyPaymentGateway;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::with('products')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'payment_method' => 'required|string' // 'paypal','card','legacy'
        ]);

        // 1) Build order (Builder)
        $user = User::findOrFail($data['user_id']);
        $builder = new OrderBuilder();
        $builder->setUser($user)->setPaymentMethod($data['payment_method']);
        foreach ($data['product_ids'] as $pid) {
            $builder->addProduct(Product::findOrFail($pid));
        }
        $order = $builder->build();

        // 2) Singleton: add to OrderService
        OrderService::getInstance()->addOrder($order);

        // 3) Decorator: calculate total + optional discount (hardcoded 10 for demo)
        $baseOrder = new BaseOrder($order);
        $discounted = new DiscountDecorator($baseOrder, 10.0);
        $total = $discounted->getTotal();

        // 4) Strategy + Adapter: choose payment
        $method = $data['payment_method'];
        if ($method === 'paypal') {
            $paymentStrategy = new PayPalPayment();
        } elseif ($method === 'card') {
            $paymentStrategy = new CreditCardPayment();
        } elseif ($method === 'legacy') {
            // Adapter implements PaymentStrategy implicitly through adapter interface usage:
            $paymentStrategy = new PaymentAdapter(new LegacyPaymentGateway());
        } else {
            $paymentStrategy = new PayPalPayment();
        }
        $paymentContext = new PaymentContext($paymentStrategy);
        $paymentResult = $paymentContext->pay($total);

        // 5) Save final total
        $order->update(['total' => $total]);

        // 6) Observer: notify admin(s)
        $subject = new OrderSubject();
        $subject->attach(new AdminNotifier());
        $subject->notify($order);

        return response()->json([
            'order' => $order->load('products'),
            'total' => $total,
            'payment' => $paymentResult
        ], 201);
    }

    public function show($id)
    {
        return response()->json(Order::with('products')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['payment_method' => 'required|string']);
        $order->payment_method = $request->payment_method;
        $order->save();
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        // Optionally notify admin about cancellation
        $subject = new OrderSubject();
        $subject->attach(new AdminNotifier());
        $subject->notify($order);

        return response()->json(null, 204);
    }
}
