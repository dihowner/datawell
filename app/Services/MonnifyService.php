<?php
namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\WalletIn;
use App\Models\VirtualBank;
use App\Classes\HttpRequest;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class MonnifyService extends UserService {
    use ResponseTrait;
    protected $responseBody, $utilityService, $userService;

    private $v1 = "/api/v1/";
    private $v2 = "/api/v2/";
    private $monnifyInfo, $endpoint, $apiKey, $secKey, $contractCode, $charges, $chargestype, $percent, $deposit_amount;

    public function __construct(UtilityService $utilityService)
    {
        $this->utilityService = $utilityService;
        $this->monnifyInfo = json_decode($this->utilityService->monnifyInfo());
        $this->endpoint = $this->monnifyInfo->baseUrl;
        $this->apiKey = $this->monnifyInfo->apiKey;
        $this->secKey = $this->monnifyInfo->secKey;
        $this->contractCode = $this->monnifyInfo->contractCode;
        $this->charges = (float) $this->monnifyInfo->charges;
        $this->chargestype = $this->monnifyInfo->chargestype;
        $this->percent = $this->monnifyInfo->percent;
        $this->deposit_amount = $this->monnifyInfo->deposit_amount;
    }

    public function getAllVirtualBanks() {
        return VirtualBank::all();
    }

    public function getVirtualBankByCode($bank_code) {
        $getBank = VirtualBank::where("bank_code", $bank_code)->first();
        if($getBank != NULL) {
            $result = $getBank;
        }
        else {
            $result = false;
        }
        return $result;
    }

    private function generateAuthToken() {
        try {
            $result = HttpRequest::sendPost($this->endpoint.$this->v1."auth/login", "", [
                "Authorization" => "Basic ".base64_encode($this->apiKey.':'.$this->secKey),
                "Content-Type" => "application/json"
            ]);

            // $accessToken = json_decode((string) $result);
            $bearerToken = isset($result['responseBody']['accessToken']) ? $result['responseBody']['accessToken'] : false;
            $this->responseBody = $bearerToken;
        } catch (RequestException $e) {
            // Handle request exceptions (e.g. 4xx, 5xx status codes)
            $this->responseBody = [
                "status_code" => $e->getCode(),
                "message" => $e->getMessage()
            ];
        } catch (\Exception $e) {
            // Handle other exceptions (e.g. network errors)
            $this->responseBody = ["message" => $e->getMessage()];
        }
        return $this->responseBody;
    }

    public function generateVirtualAccount(array $userData) {
        try {
            $allVirtualBank = $this->getAllVirtualBanks();
            if($allVirtualBank === false) {
                return $this->sendError("Virtual Bank(s) could not be retrieved", [], 404);
            }

            for($i = 0; $i < count($allVirtualBank); $i++) {
                $bank_code[] = $allVirtualBank[$i]['bank_code'];
            }

            $reserveBody = [
                "accountReference" => $userData['reference'],
                "accountName" => $userData['username'],
                "currencyCode" => "NGN",
                "contractCode" => $this->contractCode,
                "customerEmail" => $userData['email_address'],
                "customerName" => $userData['username'],
                "getAllAvailableBanks" => false,
                "preferredBanks" => $bank_code
            ];

            $reserveResult = HttpRequest::sendPost($this->endpoint.$this->v2."bank-transfer/reserved-accounts", $reserveBody, [
                "Authorization" => "Bearer ".$this->generateAuthToken(),
                "Content-Type" => "application/json"
            ]);

            $decodeReserve = is_array($reserveResult) ? $reserveResult : json_decode((string) $reserveResult, true);

            $getAccount = isset($decodeReserve['responseBody']['accounts']) ? $decodeReserve['responseBody']['accounts'] : false;
            if($getAccount !== false) {

                for($i = 0; $i < count($getAccount); $i++) {
                    $bank_code = $getAccount[$i]['bankCode'];
                    $accountNumber = $getAccount[$i]['accountNumber'];
                    $newArray[$bank_code] = $accountNumber;
                }
                $this->responseBody = $newArray;
            }
            else {
                $this->responseBody = $this->sendError("Error reserving account", [], 400);
            }
        }
        catch(Exception $e) {
            Log::error($e->getMessage());
            $this->responseBody = $this->sendError("System Error!", [], 400);
        }
        return $this->responseBody;
    }

    public function deleteReservedAccount($accountReference) {
        try {

            $deleteAccount = HttpRequest::sendDelete($this->endpoint.$this->v1."bank-transfer/reserved-accounts/reference/".$accountReference, "", [
                "Authorization" => "Bearer ".$this->generateAuthToken(),
                "Content-Type" => "application/json"
            ]);

            $decodeDelete = json_decode($deleteAccount);

            if(isset($decodeDelete->responseCode) AND $decodeDelete->responseCode != 200) {
                $this->responseBody = $this->sendError("Error", $decodeDelete->responseMessage, 200);
            }
            else if(isset($decodeDelete->responseBody) AND $decodeDelete->responseBody->accountReference == $accountReference) {
                $this->responseBody = $this->sendResponse("Success", "Virtual Account ({$accountReference}) deallocated successfully", 200);
            }
            else {
                $this->responseBody = $this->sendError("Error", "Error deallocating virtual account", 400);
            }
        }
        catch (RequestException $e) {
            // Handle request exceptions (e.g. 4xx, 5xx status codes)
            $this->responseBody = [
                "status_code" => $e->getCode(),
                "message" => $e->getMessage()
            ];
        } catch (\Exception $e) {
            // Handle other exceptions (e.g. network errors)
            $this->responseBody = ["message" => $e->getMessage()];
        }
        return $this->responseBody;
    }

    public function approveMonnifyPayment($paymentData) {
        try {
            $decode_result = json_decode($paymentData, true);
            $monnifyInfo = $this->utilityService->monnifyInfo();
            $decodeMonnify = json_decode($monnifyInfo, true);
            
            if(isset($decode_result["eventType"]) == "SUCCESSFUL_TRANSACTION" OR isset($decode_result["transactionReference"]) != NULL) {
                
                if(isset($decode_result["eventType"])) {
                    $result = $decode_result['eventData'];
                } else {
                    $result = $decode_result;
                }

                $hash = hash("SHA512" ,str_replace(" ", "", $this->secKey)."|".$result['paymentReference']."|".$result['amountPaid']."|".$result['paidOn']."|".$result['transactionReference']);

                $paymentReference = $result["paymentReference"];

                $isExistPayment = WalletIn::where(['reference' => $paymentReference])->first();
                
                if ($isExistPayment) {
                    return $this->sendError("Payment already approved", [], 400);
                } else {
                    $data = http_build_query([ "paymentReference" =>$paymentReference ]);

                    $verifyPayment = HttpRequest::sendGet($decodeMonnify['baseUrl']."/api/v1/merchant/transactions/query", $data, [
                        "Authorization" => "Basic ".base64_encode($this->apiKey.':'.$this->secKey),
                        "Content-Type" => "application/json"
                    ]);
                    $decodeverifyPayment = json_decode($verifyPayment);
                    if ($decodeverifyPayment->responseBody->paymentStatus =="PAID") {
                        if ($result["product"]["type"] == "RESERVED_ACCOUNT") { //if reserved/mapped account
                            
                            $userReference = $result["product"]["reference"]; // Monnify account reference

                            $getClient = User::where(['auto_funding_reference' => $userReference])->first();
                            $userId = $getClient['id']; //Client Id
                            $currBlc = app(WalletService::class)->getUserBalance($userId); //what's the current balance of the member making payment...
                            $amountPaid = (double) $result["amountPaid"]; // how much was paid to monnify without settlement amount.... 

                            if($this->chargestype == "flat_rate") {
                                $amount_crdt = (float) ($amountPaid - $this->charges);
                            }
                            else if($this->chargestype == "percentage") {
                                $amount_crdt = (float) ($amountPaid - (($amountPaid * $this->percent)/100));
                            }
                            else {
                                if($amountPaid >= $this->deposit_amount) {
                                    $amount_left = ($amountPaid - (($amountPaid * $this->percent)/100));
                                    $amount_crdt = (float) ($amount_left - $this->charges);
                                }
                                else {
                                    $amount_crdt = (float) ($amountPaid - $this->charges);
                                }
                            }

                            $newBlc = (float) ($currBlc + $amount_crdt);

                            $result['approved_by'] = 'Monnify System';

                            $walletData = [
                                'user_id' => $userId,
                                'description' => 'Monnify Wallet funding of '.$amount_crdt,
                                'old_balance' => $currBlc,
                                'amount' => $amount_crdt,
                                'new_balance' => $newBlc,
                                'reference' => $paymentReference,
                                'status' => '1',
                                'channel' => 'monnify',
                                'wallet_type' => 'wallet_in',
                                'remark' => json_encode($result)
                            ];

                            $createWallet = app(WalletService::class)->createWallet('inward', $walletData);

                            if ($createWallet) {
                                $data = [
                                    'user_reference' => $userReference,
                                    'amount' => $amount_crdt,
                                    'amount_paid' => $amountPaid,
                                    'payment_reference' => $paymentReference
                                ];
                                
                                return $this->sendResponse("Payment approved successfully", $data);
                            }
                            return $this->sendError("Error updating user wallet balance", [], 400);
                        }
                        return $this->sendError("Payment data is not with a reserved account", [], 400);
                    }
                    return $this->sendError("Payment is in pending state with Provider", [], 400);
                }
            }
            else {
                return $this->sendError("Not a successful transaction", [], 400);
            }
        }
        catch(Exception $e) {
            return $this->sendError("Error!. ".$e->getMessage(), [], 500);
        }
    }

}
?>