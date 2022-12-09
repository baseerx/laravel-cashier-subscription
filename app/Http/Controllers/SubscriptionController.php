<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Model\User;
use App\Plan as AppPlan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    public function create(Request $request, Plan $plan)
    {
        $plan = Plan::findOrFail($request->get('plan'));
        // Log::info($request->all());
        // Log::info($plan);

        $user = $request->user();
        $paymentMethod = $request->paymentMethod;

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);
        // below code for active subscription
        // $user->newSubscription('Second', 'price_1MCmIQB0DWwA7fx5j0KDhKXc')
        //     ->create($paymentMethod, [
        //         'email' => $user->email,
        //     ]);

        // below code is working fine trail by many days delay
        // $request->user()->newSubscription('Second', 'price_1MCmIQB0DWwA7fx5j0KDhKXc')
        //     ->trialDays(10)
        //     ->create($paymentMethod, [
        //         'email' => $user->email,
        //     ]);

$dateone=Carbon::parse('2022-12-27')->addDays(1);
        //for trial until this date subscription
        $request->user()->newSubscription('Second', 'price_1MCmIQB0DWwA7fx5j0KDhKXc')
            ->trialUntil($dateone)
            ->create($paymentMethod, [
                'email' => $user->email,
            ]);


        return redirect()->route('home')->with('success', 'Your plan subscribed successfully');
    }


    public function createPlan()
    {
        return view('plans.create');
    }

    public function storePlan(Request $request)
    {
        $data = $request->except('_token');

        $data['slug'] = strtolower($data['name']);
        $price = $data['cost'] * 100;

        //create stripe product
        $stripeProduct = $this->stripe->products->create([
            'name' => $data['name'],
        ]);

        //Stripe Plan Creation
        $stripePlanCreation = $this->stripe->plans->create([
            'amount' => $price,
            'currency' => 'usd',
            'interval' => 'month', //  it can be day,week,month or year
            'product' => $stripeProduct->id,
        ]);

        $data['stripe_plan'] = $stripePlanCreation->id;

        Plan::create($data);

        echo 'plan has been created';
    }
}
