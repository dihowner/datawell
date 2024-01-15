<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("content");
            $table->timestamps();
        });

        Schema::create('virtual_banks', function (Blueprint $table) {
            $table->id();
            $table->string("bank_code")->unique();
            $table->string("bank_name")->unique();
            $table->timestamp('date_created');
        });

        // Insert some stuff
        $settingsArray = [
            'default_plan_id' => '1',
            'monnify' => '{"baseUrl":"https:\/\/sandbox.monnify.com","apiKey":"MK_TEST_SXSY8BH2T8","secKey":"GA84CM4UA9Z4S38GTZ3YMMES96NBBF3S","contractCode":"1378644451","chargestype":"percentage","charges":"51.45","percent":0,"deposit_amount":"10000"}',
            'bank_details' => 'Account Name: Ogundowole Raheem',
            'bank_charges' => '{"stamp_duty_charge": 50, "min_wallet":1000, "min_stamp": 2000}',
            'flutterwave' => '{"status":"active","public_key":"FLWPUBK_TEST-6eff3a37d706602aa9267034740dffe6-X","secret_key":"FLWSECK_TEST-5514ebd9ca2ffe22eeef05e2204fb922-X","encrypt_key":"FLWSECK_TEST920fd730c56f","charges":"50","chargesType":"percentage"}',
            'paystack' => '{"status":"active","public_key":"pk_test_44d615caae49b0e77b58d005c41c8980ecf10d1e","secret_key":"sk_test_3f3bc374bfebaa581b78965d299d3572a57b10d9","charges":"50","chargesType":"percentage"}',
            'airtimeInfo' => '{"min_value":"10","max_value":"2000","delivery":{"mtn":"2000","glo":"4549","airtel":"1879","eti":"2999","status":"5"}}',
            'airtime_conversion' => '{
                "mtn":{
                    "status":1,
                    "percentage":90,
                    "receiver":09033024846
                },
                "airtel":{
                    "status":1,
                    "percentage":96,
                    "receiver":09033024846
                },
                "glo":{
                    "status":1,
                    "percentage":85,
                    "receiver":08155577122
                },
                "9mobile":{
                    "status":1,
                    "percentage":92,
                    "receiver":09033024846
                }
            }',
            'loginPassword' => 'datawell24'
        ];
        $this->loadSettings($settingsArray);

        // Insert some stuff
        $bankArray = [
            'Wema Bank' => '035',
            'Sterling Bank' => '232'
        ];

        $this->loadBanks($bankArray);

    }
    
    private function loadBanks($bankArray) {
        foreach($bankArray as $index => $bank) {
            DB::table('virtual_banks')->insert(['bank_code' => $bank, 'bank_name' => $index]);
        }
    }
    
    private function loadSettings($settingsArray) {
        foreach($settingsArray as $name => $content) {
            DB::table('settings')->insert(['content' => $content, 'name' => $name]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};