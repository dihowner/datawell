<?php
namespace App\Classes;

use App\Http\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Validator;

/**
 * This is a manual validator as I could not get a way around normal validation with request
 */
class ReferenceValidator  {
    use ResponseTrait;
    public static function ManualValidator($reference) {
        try {
            // Validate the reference ID
            $validator = Validator::make(['reference' => $reference], [
                'reference' => ['required', 'numeric'] // Add your validation rules here
            ]);

            if ($validator->fails()) {
                
                // Get the validation error messages
                $errors = $validator->errors();
                
                // Loop through each error message
                foreach ($errors->all() as $error) {
                    
                }
                
                $instance = new self();
                return $instance->sendError($error, [], 422);
            }
            return true;
        }
        catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
?>