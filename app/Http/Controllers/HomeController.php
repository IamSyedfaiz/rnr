<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Group;
use App\Helpers\Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        // dd('prateek');
        return view('backend.home');
    }

    public function index()
    {
        return view('home');
    }

    public function user_home()
    {
        try {
            //code...

            $loggedinuser = auth()->id();
            // dd($userid);
            $application = Application::where('status', 1)
                // ->latest()
                ->get();

            $userapplication = [];
            $userid = [];
            // dd($application[1]->rolestable()->first());

            for ($i = 0; $i < count($application); $i++) {
                # code...
                if ($application[$i]->rolestable()->get() != 'null' && $application[$i]->rolestable()->get() != null) {

                    $rolestablearray = $application[$i]->rolestable()->get();

                    for ($k=0; $k < count($rolestablearray) ; $k++) { 
                        // dd($rolestablearray[$k]->group_list);
                        if ($rolestablearray[$k]->group_list != 'null') {
                            # code...
                            array_push($userid, Helper::findusers($rolestablearray[$k]->group_list));
                        }
                        // dd(json_decode($rolestablearray[0]->user_list));
                        if ($rolestablearray[$k]->user_list != 'null') {
                            # code...
                            array_push($userid, json_decode($rolestablearray[$k]->user_list));
                        }
                    }

                    $useridfound = 'false';
                    // dd(in_array(auth()->id(), $userid[2]));
                    for ($j = 0; $j < count($userid); $j++) {
                        if (in_array(auth()->id(), $userid[$j])) {
                            $useridfound = 'true';
                        }
                    }
                    // dd($useridfound);

                    if ($useridfound == 'true') {
                        array_push($userapplication, $application[$i]);
                    }
                }
            }
  
            // dd($userapplication);

            return view('backend.backenduserhome');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }

    public function razorpay(Request $request)
    {
        try {
            //code...
            // Generated @ codebeautify.org
            dd('prateek');
            $data = [
                'amount' => 50000,
                'amount_paid' => 0,
                'currency' => 'INR',
            ];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_COOKIEFILE => 'file.txt',
                CURLOPT_COOKIEJAR => 'file.txt',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                CURLOPT_USERPWD => 'rzp_live_lhgrMO0eBVfInc' . ':' . '2R7huhzG3LPvngJ23kTvqR7M',
            ]);

            $response = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $res = json_decode($response, true);

            dd($res);
        } catch (\Throwable $th) {
            //throw $th;
            // return response()->json(
            //     [
            //         'status' => '400',
            //         'data' => [];
            //     ]
            // );
        }
    }
}
