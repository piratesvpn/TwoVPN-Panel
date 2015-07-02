<?php 

namespace App\Http\Controllers;

use App\User;
use App\Services;
use Illuminate\Http\Request;
use Illuminate\Database;
use Illuminate\Database\Eloquent\Model;

class PaymentController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('portal.payment');
    }

    public function postBuy(Request $request)
    {
        $user = \App\User::find(1);

        $user->charge($request->get('price'), [
            //'source' => $request->get('StripeToken'),
            'source' => $request->get('stripeToken'),
            'receipt_email' => $user->email,
        ]);

        $services = new \App\Services;
        $services->Username = $request->get('account');
        $services->Attribute = "MD5-Password";
        $services->op = ":=";
        $services->Value = md5($request->get('password'));

        $services->save();

        return redirect($this->homePath());

    }

    public function homePath()
    {
        return property_exists($this, 'homePath') ? $this->homePath : '/home';
    }

}