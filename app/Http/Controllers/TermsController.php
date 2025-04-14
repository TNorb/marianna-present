<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function termsAndConditions()
    {
        $content = '<h1>Terms and Conditions</h1><p>Here are the terms and conditions...</p>';
        return view('terms', compact('content'));
    }

    public function termsOfPurchase()
    {
        $content = '<h1>Terms of Purchase</h1><p>Here are the terms of purchase...</p>';
        return view('terms', compact('content'));
    }

    public function shippingInformations()
    {
        $content = '<h1>Shipping Informations</h1><p>Here is the shipping informations...</p>';
        return view('terms', compact('content'));
    }

    public function consumerInformations()
    {
        $content = '<h1>Consumer Informations</h1><p>Here is the consumer informations...</p>';
        return view('terms', compact('content'));
    }

    public function declarationOfWithdrawal()
    {
        $content = '<h1>Declaration of Withdrawal</h1><p>Here is the declaration of withdrawal...</p>';
        return view('terms', compact('content'));
    }

    public function privacyPolicy()
    {
        $content = '<h1>Privacy Policy</h1><p>Here is the privacy policy...</p>';
        return view('terms', compact('content'));
    }
}