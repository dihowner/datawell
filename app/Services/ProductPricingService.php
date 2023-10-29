<?php
namespace App\Services;

use Exception;
use App\Models\Product;
use App\Models\ProductPricing;

class ProductPricingService {

    protected $responseBody;

    public function getProductPrice($planId, $productId, $amount = '') {
        try {
            $products = Product::with('productpricing')->where('product_id', $productId)->whereHas('productpricing', function ($query) use ($planId) {
                            $query->where('plan_id', $planId);
                        })->orderBy('id')->first();

                // return $products;
            
            if($products != NULL) {
                // Get plan pricing
                $productPricing = $products['productpricing'];
                $selling_price = (float) $productPricing['selling_price'];
                $extra_charges = (float) $productPricing['extra_charges'];
                $productCostPrice = (float) $products['cost_price'];

                // When amount is supplied and it's airtime...
                if($amount != '' AND strpos(strtolower($products['category']->category_name), 'airtime') !== false) {
                    $selling_price = $selling_price <= 0 ? 100 : $selling_price;
                    $sellingPrice = (float) ($amount * $selling_price)/100;
                    $SalesCostPrice = (float) ($amount * $productCostPrice)/100;
                } else if($amount != '' AND (strpos($productId, "prepaid") !== false OR strpos($productId, "postpaid") !== false)) { #For Electricity...
                    $sellingPrice = (float) ($amount + $extra_charges);
                    $SalesCostPrice = (float) ($amount * $productCostPrice);
                } else {
                    
                    $SalesCostPrice = $productCostPrice;
                    
                    if($this->isExemptCategory($products['category']['category_name']) AND $amount > 0) {
                        $sellingPrice = (float) $amount + $extra_charges;
                        $SalesCostPrice = (float) $amount + $productCostPrice;
                    }
                    else {
                        $sellingPrice = (float) $selling_price + $extra_charges;
                    }
                }
                
                if($sellingPrice <= 0) {
                    return false;
                }
                
                return [
                    "product_name" => $products['product_name'],
                    "selling_price" => $sellingPrice,
                    "cost_price" => $SalesCostPrice,
                    "profit" => $sellingPrice - $SalesCostPrice
                ];
            }
            return false;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateProductPricing(int $planId, array $productIds, array $sellingPrices, array $extraCharges) {
        foreach($productIds as $index => $productId) {
            ProductPricing::where(["plan_id" => $planId, "product_id" => $productId])->update([
                "selling_price" => $sellingPrices[$index] == "" ? 0 : $sellingPrices[$index], 
                "extra_charges" => $extraCharges[$index] == "" ? 0 : $extraCharges[$index]
            ]);
        }    
        return true;
    }

    private function isExemptCategory($category) {
        $exemptedCategory = [
            "dstv", "gotv"
        ];

        foreach ($exemptedCategory as $keyword) {
            if (stripos($category, $keyword) !== false) {
                return true; // Category matches an exempt keyword
                break;
            }
        }
        return false;
    }
}