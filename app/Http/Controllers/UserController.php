<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\KYC;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\PlanService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\BankAccountRequest;
use App\Http\Requests\PlanUpgradeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EditUserByAdminRequest;

class UserController extends Controller
{
    protected $userService, $planService;
    public function __construct(UserService $userService, PlanService $planService)
    {
        $this->userService = $userService;
        $this->planService = $planService;
    }

    public function index() {
        $userDetail = $this->userService->getUserById(Auth::id());
        
        if($userDetail === false) { return view('login'); }

        if($userDetail['secret_pin'] === "0000") {
            // So user will have a clue of what happen, let's sent a toast notification...
            Alert::info("Create Your PIN", "To enjoy our numerous offer, kindly change your default transaction pin");
            return redirect()->route('user.pin-password-view');
        }

        $use_nin = $use_bvn = true;

        // Has User performed KYC ???
        $kycData = KYC::where('user_id', Auth::id())->first();
        if ($kycData AND $kycData->nin_status === 'verified') {
            $use_nin = false;
        }
        if ($kycData AND $kycData->bvn_status === 'verified') {
            $use_bvn = false;
        }

        return view('private.dashboard',  compact('userDetail', 'use_nin', 'use_bvn'));
    }

    public function GenerateUserVirtualAccount($userId = "") {
        // If request was sent by Admin, then a user id is passed
        $userId = $userId != "" ? $userId : Auth::id();

        $generateAccount = $this->userService->GenerateUserVirtualAccount($userId);
        $responseCode = $generateAccount->getStatusCode() ?? 400;

        $responseContent = json_decode($generateAccount->content());
        $message = $responseContent->message ?? "Error occurred";

        if($responseCode === 200) {
            Alert::success('Success', $message)->autoClose(10000);
        }
        else {
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    public function MyProfile() {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.my-profile', compact('userDetail'));
    }

    public function BankInfoView() {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.bank-account', compact('userDetail'));
    }

    public function UpdateUserBank(BankAccountRequest $request) {
        $createBank = $this->userService->UpdateUserBank($request->validated());
        $responseCode = $createBank->getStatusCode();

        $responseContent = json_decode($createBank->content());
        $message = $responseContent->message;

        if($responseCode === 200) {
            Alert::success('Success', $message)->autoClose(10000);
        }
        else {
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    public function PinPassView() {
        $userDetail = $this->userService->getUserById(Auth::id());
        return view('private.change-pin-password', compact('userDetail'));
    }

    /*AJAX Search */
    public function findUser($userPhone) {
        $userDetail = $this->userService->getUserByPhone_Username($userPhone);
        return $userDetail;
    }

    public function UpgradePlan(PlanUpgradeRequest $request) {
        $planUpgrade = $this->userService->UpgradeUserPlan($request->validated());
        $responseCode = $planUpgrade->getStatusCode();
        $responseContent = json_decode($planUpgrade->content());
        $message = $responseContent->message;
        if($responseCode === 200) {
            Alert::success("Success", $message)->autoClose(10000);
        } else {
            Alert::error("Error", $message)->autoClose(10000);
        }
        return redirect()->back();
    }

    public function usersList(Request $request) {
        $searchValue = "";
        if($request->filled('query')){
            $searchValue = $request->input('query');
        }
        
        $allUsers = $this->userService->allUsers($searchValue);
        return view('main.userlist', compact('allUsers'));
    }

    public function userMgt(Request $request) {
        $searchValue = "";
        if($request->filled('query')){
            $searchValue = $request->input('query');
        }
        
        $allUsers = $this->userService->allUsers($searchValue);
        $allPlans = $this->planService->getAllPlan();
        return view('main.usermgt', compact('allUsers', 'allPlans'));
    }

    public function updateUser(EditUserByAdminRequest $request) {
        $updateUser = $this->userService->updateUser($request->validated());
        $decodeResponse = json_decode($updateUser->getContent(), true);
        if($updateUser->getStatusCode() == 200) {
            Alert::success("Success", $decodeResponse['message'])->autoClose(10000);
        } else {
            Alert::error("Error", "Error processing request");
        }
        return redirect()->back();
    }

    public function updateUserAccessControl($id, $action) {
        $updateUser = $this->userService->updateUserAccessControl($id, $action);
        $responseCode = $updateUser->getStatusCode();
        $responseContent = json_decode($updateUser->content());
        $message = $responseContent->message;
        if($responseCode === 200) {
            Alert::success("Success", $message);
        } else {
            Alert::error("Error", $message);
        }
        return redirect()->back();
    }

    public function exportUserCSV() {
        $todatsDate = Carbon::now();
        $filename = 'datawell-users-'.$todatsDate.'.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
    
            // Add CSV headers
            fputcsv($handle, [
                'First Name',
                'Last Name',
                'Email',
                'Phone Number'
            ]);
    
             // Fetch and process data in chunks
            User::chunk(5000, function ($users) use ($handle) {
                foreach ($users as $user) {
                    $fullname = explode(" ", $user['fullname']);
                    $first_name = $fullname[0];
                    $last_name = !empty($fullname[1]) ? $fullname[1] : '';
                    // Extract data from each user.
                    $data = [
                        $first_name,
                        $last_name,
                        $user['emailaddress'],
                        $user['phone_number']
                    ];
    
                    // Write data to a CSV file.
                    fputcsv($handle, $data);
                }
            });
    
            // Close CSV file handle
            fclose($handle);
        }, 200, $headers);
    }

    public function KycView() {
        $userDetail = $this->userService->getUserById(Auth::id());
        
        $kycSettings = $this->userService->getAllSettings()->kycSettings;
        if ($kycSettings !== false) {
            $kycSettings = json_decode($kycSettings, true);
            if (!isset($kycSettings['verification_type'])) {
                Alert::error('Error', 'KYC Verification is not properly configured. Notify Admin');
                return redirect()->route('user.index');
            } else if ($kycSettings['verification_type'] === 'disabled') {
                Alert::error('Error', 'KYC Verification is currently disabled');
                return redirect()->route('user.index');
            }
        }
        
        $use_nin = $use_bvn = true;

        // Has User performed KYC ???
        $kycData = KYC::where('user_id', Auth::id())->first();
        if ($kycData AND $kycData->nin_status === 'verified') {
            $use_nin = false;
        }
        if ($kycData AND $kycData->bvn_status === 'verified') {
            $use_bvn = false;
        }

        if (!$use_nin AND !$use_bvn) {
            Alert::error('Error', 'KYC Verification fully setup');
            return redirect()->route('user.index');
        } 

        return view('private.kyc', compact('userDetail', 'kycSettings', 'use_nin', 'use_bvn'));
    }

    public function submitKycDetails(ProfileUpdateRequest $request) {
        $data = $request->validated();
        $user = Auth::user();
        $userId = $user->id;
        $verificationMethod = strtolower($data['verification_method']);
        $isUserVerified = false;

        // Has User performed KYC ???
        $kycData = KYC::where('user_id', $userId)->first();

        if ($kycData AND $verificationMethod == 'bvn' AND $kycData->bvn_status == 'verified') {
            Alert::error('Error', 'BVN already verified');
            return redirect()->route('user.kyc-view');
        } else if ($kycData AND $verificationMethod == 'nin' AND $kycData->nin_status == 'verified') {
            Alert::error('Error', 'NIN already verified');
            return redirect()->route('user.kyc-view');
        } else {
            switch ($verificationMethod) {
                case 'bvn':
                    $clientName = $data['fullName'];
                    $bvnNumber = $data['bvnNumber'];
                    $bvnPhoneNumber = $data['bvnPhoneNumber'];
                    $dateOfBirth = Carbon::createFromFormat('Y-m-d', $data['dateOfBirth'])->format('d-M-Y');
    
                    $result = $this->userService->verifyBVN($userId, $clientName, $bvnNumber, $bvnPhoneNumber, $dateOfBirth);
                break;
                
                case 'nin':
                    $clientName = $data['fullName'];
                    $ninNumber = $data['ninNumber'];
                    $ninPhoneNumber = $data['ninPhoneNumber'];
                    $dateOfBirth = Carbon::createFromFormat('Y-m-d', $data['dateOfBirth'])->format('d-M-Y');
                    
                    $result = $this->userService->verifyNIN($userId, $clientName, $ninNumber, $ninPhoneNumber, $dateOfBirth);
                break;
                default:
                    $result = false;
            }
            return [$verificationMethod, $userId, $result];

        }
    }

}