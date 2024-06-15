<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Group;
use App\Helpers\Helper;
use App\Models\backend\Dashboard;
use App\Models\backend\Report;

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
        $dashboards = Dashboard::where('active', 'Y')->latest()->get();

        $filteredDashboards = $dashboards->filter(function ($dashboard) {
            if (is_null($dashboard->report_id)) {
                return false;
            }

            $reportIds = explode(',', $dashboard->report_id);
            $reports = Report::whereIn('id', $reportIds)->get();
            $dashboard->reports = $reports;

            return !$reports->isEmpty();
        });

        return view('backend.home', ['dashboards' => $filteredDashboards]);
    }

    public function index()
    {
        return view('home');
    }

    public function user_home()
    {
        try {
            // $dashboards = Dashboard::where('active', 'Y')->latest()->get();
            $userId = auth()->user()->id;
            $userDashboards = Dashboard::whereJsonContains('user_list', (string) $userId)->orWhere('user_id', $userId)->where('active', 'Y')->get();

            // Retrieve group IDs where the authenticated user is listed
            $groupIds = Group::whereJsonContains('userids', (string) $userId)->pluck('id');

            // Initialize an empty collection for group dashboards
            $groupDashboards = collect();

            // Check if groupIds is not empty
            if ($groupIds->isNotEmpty()) {
                // Retrieve dashboards where any of the user's group IDs are listed
                $groupDashboards = Dashboard::where(function ($query) use ($groupIds) {
                    foreach ($groupIds as $groupId) {
                        $query->orWhereJsonContains('group_list', (string) $groupId);
                    }
                })->get();
            }

            // Combine the two collections and remove duplicates
            $dashboards = $userDashboards->merge($groupDashboards)->unique('id');
            // dd($dashboards);

            $filteredDashboards = $dashboards->filter(function ($dashboard) {
                if (is_null($dashboard->report_id)) {
                    return false;
                }

                $reportIds = explode(',', $dashboard->report_id);
                $reports = Report::whereIn('id', $reportIds)->get();
                $dashboard->reports = $reports;

                return !$reports->isEmpty();
            });
            return view('backend.backenduserhome', ['dashboards' => $filteredDashboards]);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
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
