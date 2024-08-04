<?php
namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\WalletIn;
use App\Models\WalletOut;
use App\Models\Transaction;
use App\Classes\PaginatorHelper;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionService {

    use ResponseTrait;
    protected $utilityService, $walletService, $responseBody;
    public function __construct(UtilityService $utilityService, WalletService $walletService) {
        $this->utilityService = $utilityService;
        $this->walletService = $walletService;
    }

    public function transactionSummary($transactType) {
        switch($transactType) {
            case "yesterday_profit":
            case "yesterday_total":
                $startDate = Carbon::yesterday()->startOfDay()->format('Y-m-d H:i');
                $endDate = Carbon::yesterday()->endOfDay()->format('Y-m-d H:i');
            break;
            
            case "today_profit":
            case "today_total":
                $startDate = Carbon::today()->startOfDay()->format('Y-m-d H:i'); // 00:00:00
                $endDate = Carbon::today()->endOfDay()->format('Y-m-d H:i'); // 23:59:59
            break;

            case "last_week":
                $startDate = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d H:i');
                $endDate = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d H:i');
            break;

            case "this_week":
                $startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i');
                $endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i');
            break;
            
            case "this_month":
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i');
                $endDate = Carbon::now()->endOfMonth()->endOfDay()->format('Y-m-d H:i');
            break;
            
            case "last_month":
                $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d H:i');
                $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i');
            break;

        }
        
        return Transaction::where(['status' => '1'])->whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('COALESCE(SUM(amount), 0) as total_sales, COALESCE(SUM(costprice), 0) as total_cost_price, COALESCE(SUM(amount - costprice), 0) as profit')
                    ->first();
                    
    }
    
    public function dataSummary($networkName, $transactType) {
        switch($transactType) {
            case "yesterday_profit":
            case "yesterday_total":
                $startDate = Carbon::yesterday()->startOfDay()->format('Y-m-d H:i');
                $endDate = Carbon::yesterday()->endOfDay()->format('Y-m-d H:i');
            break;
            
            case "today_profit":
            case "today_total":
                $startDate = Carbon::today()->startOfDay()->format('Y-m-d H:i'); // 00:00:00
                $endDate = Carbon::today()->endOfDay()->format('Y-m-d H:i'); // 23:59:59
            break;

            case "last_week":
                $startDate = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d H:i');
                $endDate = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d H:i');
            break;

            case "this_week":
                $startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i');
                $endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i');
            break;
            
            case "this_month":
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i');
                $endDate = Carbon::now()->endOfMonth()->endOfDay()->format('Y-m-d H:i');
            break;
            
            case "last_month":
                $startDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d H:i');
                $endDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i');
            break;

        }
        
        $dataTxns = Transaction::where(['category' => 'data', 'status' => '1'])->where('description', 'like',  '%' . $networkName . '%')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select('description')->get();
        $totalVolume = 0;

        if ($dataTxns->count() > 0) {
            $unwantedArray = ['purchase of ', 'mtn ', 'sme ', 'cg ', 'gifting ', 'airtel ', 'glo ', '9mobile ', '/30days', '30days', '30 days', '(special)' ];
            foreach ($dataTxns as $dataIndex => $dataTxn) {
                $description = trim(substr(str_replace($unwantedArray, '', strtolower($dataTxn->description)), 0,-16));

                if (str_contains($description, 'gb')) {
                    $volume = str_replace('gb', '', $description);
                } else if (str_contains($description, 'mb')) {
                    $volume = str_replace('mb', '', $description);
                    $volume = $volume/1000;
                } 

                if (is_numeric($volume)){
                    $totalVolume += $volume;
                }
            }
        }
        return number_format($totalVolume, 2);                    
    }

    public function userTransactionSummary($userId) {
        $statusOut = ['0', '1', '2'];
        
        $todaysStartingDate = Carbon::today()->startOfDay()->format('Y-m-d H:i'); // 00:00:00
        $todaysEndingDate = Carbon::today()->endOfDay()->format('Y-m-d H:i'); // 23:59:59

        $txQuery = Transaction::where(['user_id' => $userId])->whereIn('status', $statusOut);
        
        return [
            "daily_transaction" => $txQuery->whereBetween('created_at', [$todaysStartingDate, $todaysEndingDate])->selectRaw('COALESCE(SUM(amount), 0) as total_sales')->first(),
            "daily_total_transaction" => $txQuery->whereBetween('created_at', [$todaysStartingDate, $todaysEndingDate])->count(),
        ];
    }

    public function createTransaction(array $data) {
        try {
            return Transaction::create($data);
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function processTransaction($action, $txIDs)  {
        try {
            
            if(count($txIDs) > 10) {
                return $this->sendError("Maximum number of order 10", [], 400);
            }

            DB::beginTransaction();
            $adminFullName = Auth::guard('admin')->user()->fullname;
            $txnMemo = json_encode(["approved_by" => $adminFullName]);
            switch($action) {
                case "retry":
                    Transaction::whereIn('reference', $txIDs)->update(['status' => '0', 'memo' => $txnMemo]);
                    WalletOut::whereIn('reference', $txIDs)->update(['status' => '0']);
                    DB::commit();
                    return $this->sendResponse(count($txIDs). " Transactions retried successfully", [], 200);
                break;
                
                case "awaiting":
                    Transaction::whereIn('reference', $txIDs)->update(['status' => '2', 'memo' => $txnMemo]);
                    WalletOut::whereIn('reference', $txIDs)->update(['status' => '2']);
                    DB::commit();
                    return $this->sendResponse(count($txIDs). " Transactions status update to awaiting successfully", [], 200);
                break;
                
                case "complete":
                    Transaction::whereIn('reference', $txIDs)->update(['status' => '1', 'memo' => $txnMemo]);
                    WalletOut::whereIn('reference', $txIDs)->update(['status' => '1']);
                    DB::commit();
                    return $this->sendResponse(count($txIDs). " Transactions completed successfully", [], 200);
                break;

                case "refund":
                    foreach($txIDs as $transactId) {
                        $findTransaction = Transaction::where('reference', $transactId)->where('status', '0')->orWhere('status', '2')->first();
                        $userId = $findTransaction->user_id;
                        $amountCharged = (float) $findTransaction->amount;
                        $extraInfo = json_decode($findTransaction->extra_info, true);
                        $category = $findTransaction->category;

                        if($category == 'airtime') {
                            $description = "Refund of ".strtoupper($extraInfo['network'])." N".$extraInfo['amount']." for ".$extraInfo['destination'];
                        } else {
                            $description = "Refund of ".strtoupper($extraInfo['product'])." for ".$extraInfo['destination'];
                        }
                        
                        $userCurrentWallet = $this->walletService->getUserBalance($userId);
                        $newBalance = (float) $userCurrentWallet + $amountCharged;

                        // Update the order asap
                        Transaction::where('reference', $transactId)->update(["status" => '3']);
                        WalletOut::where('reference', $transactId)->update(["status" => '3']);

                        WalletIn::create([
                            'user_id' => $userId,
                            "description" => $description,
                            "old_balance" => $userCurrentWallet,
                            "amount" => $amountCharged,
                            "new_balance" => $newBalance,
                            "reference" => $transactId,
                            "status" => "3",
                            "channel" => "website",
                            "wallet_type" => "wallet_in",
                            "remark" => json_encode(["approved_by" => Auth::guard('admin')->user()->fullname])
                        ]);

                        Transaction::create([
                            "user_id" => $userId,
                            "plan" => User::with('plan')->where('id', $userId)->first()->plan->plan_name,
                            "description" => $description,
                            "destination" => $findTransaction->destination,
                            "old_balance" => $userCurrentWallet,
                            "amount" => $amountCharged,
                            "new_balance" => $newBalance,
                            "costprice" => $amountCharged,
                            "category" => $category,
                            "reference" => $transactId,
                            "response" => "Order refunded",
                            "status" => '4',
                            "api_id" => $findTransaction->api_id,
                            "channel" => "website"
                        ]);
                    }
                    DB::commit();
                    return $this->sendResponse(count($txIDs). " order(s) were refunded successfully", [], 200);
                break;
            }
            return $this->sendError("Error processing request", [], 500);
        }
        catch(Exception $e) {
            DB::rollBack();
            return $this->sendError("Error processing request", [], 500);   
        }        
    }

    public function userPurchaseHistory($userId = "", $searchValue = "", $status = "all") {
        $query = Transaction::whereNotNull('user_id')->orderByDesc('created_at');

        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }
        
        if ($status != "all") {
            if(is_array($status)) {
                $query->whereIn("status", $status);
            } else {
                $query->where("status", $status);
            }
        }
        
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where("destination", $searchValue)
                      ->orWhere("reference", $searchValue);
            });
        }   

        $walletOutRecords = $query->get();

        // Create a Paginator instance
        $paginatedRecords = PaginatorHelper::createPaginator($walletOutRecords, 20, request()->get('page'), request()->url());

        return $paginatedRecords;
    }

    public function getTransaction($userId = "", $reference) {
        $query = Transaction::with('user')->where('reference', $reference)->whereNotNull('user_id');
        
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }
        
        $txnRecord = $query->first();

        if($txnRecord == NULL) { return false; }
        
        return $txnRecord;
    }

    public function getMultipleTransactions($reference, $status = "all") {
        $query = Transaction::with('user')->where('reference', $reference)->orWhere('destination', $reference);
        
        if ($status != "all") {
            $query->where("status", $status);
        } else {
            $query->whereIn("status", ['0', '1', '2']);
        }

        $txnRecord = $query->orderBy('id', 'desc')->get();

        if($txnRecord == NULL) { return false; }
        
        return $txnRecord;
    }
}