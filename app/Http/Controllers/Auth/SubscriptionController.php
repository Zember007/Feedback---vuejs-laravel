<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;


class SubscriptionController extends Controller
{

    public function init(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->user_id)->first();
        
        
        $pg_merchant_id = '553314';
        $secret_key = 'ct2WlBOGCkYtsZwI';
        $pg_duration = $request->duration;
        $pg_description = $request->pg_description;
        $request = $requestForSignature = [
            'pg_order_id' => $request->user_id,
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_amount' => $request->pg_amount,
            'pg_description' => $pg_description,
            'pg_salt' => 'molbulak',
            'pg_currency' => 'KZT',
            'pg_check_url' => '',
            'pg_result_url' => 'https://feedback.kz/subscription-result',
            'pg_request_method' => 'POST',
            'pg_success_url' => 'https://feedback.kz/dashboard',
            'pg_failure_url' => 'https://feedback.kz/dashboard',
            'pg_success_url_method' => 'GET',
            'pg_failure_url_method' => 'GET',
            'pg_payment_system' => 'EPAYWEBKZT',
            'pg_lifetime' => '86400',
            'pg_user_ip' => '127.0.0.1',
            'pg_postpone_payment' => '0',
            'pg_language' => 'ru',
            'pg_testing_mode' => '1',
            'pg_user_id' => $request->user_id,
            'pg_recurring_start' => '1',
            'pg_recurring_lifetime' => '156',
        ];
        
        /**
         * Функция превращает многомерный массив в плоский
         */
        function makeFlatParamsArray($arrParams, $parent_name = '')
        {
            $arrFlatParams = [];
            $i = 0;
            foreach ($arrParams as $key => $val) {
                $i++;
                /**
                 * Имя делаем вида tag001subtag001
                 * Чтобы можно было потом нормально отсортировать и вложенные узлы не запутались при сортировке
                 */
                $name = $parent_name . $key . sprintf('%03d', $i);
                if (is_array($val)) {
                    $arrFlatParams = array_merge($arrFlatParams, makeFlatParamsArray($val, $name));
                    continue;
                }
                $arrFlatParams += array($name => (string)$val);
            }

            return $arrFlatParams;
        }

        // Превращаем объект запроса в плоский массив
        $requestForSignature = makeFlatParamsArray($requestForSignature);

        // Генерация подписи
        ksort($requestForSignature); // Сортировка по ключю
        array_unshift($requestForSignature, 'init_payment.php'); // Добавление в начало имени скрипта
        array_push($requestForSignature, $secret_key); // Добавление в конец секретного ключа

        $request['pg_sig'] = md5(implode(';', $requestForSignature));
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.freedompay.money/init_payment.php",// your preferred url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return back();
        } else {            
            $response = simplexml_load_string($response);
            $response = json_encode($response); 
            $response = json_decode($response, true);
            $user->subscription_paymed = $response['pg_payment_id'];
            $user->subscription_link = $response['pg_redirect_url'];
            $user->subscription_duration = $pg_duration;
            $user->subscription_name = $pg_description;
            $user->save();
            return back();
        }

        
    }

    public function result(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->user_id)->first();

        $pg_merchant_id = '553314';
        $secret_key = 'ct2WlBOGCkYtsZwI';

        $request = [
            'pg_merchant_id'=> $pg_merchant_id,
            'pg_payment_id' => $request->pg_payment_id,
            'pg_salt' => 'some random string',
        ];

        //generate a signature and add it to the array
        ksort($request); //sort alphabetically
        array_unshift($request, 'get_status3.php');
        array_push($request, $secret_key); 

        $request['pg_sig'] = md5(implode(';', $request)); // signature

        unset($request[0], $request[1]);

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.freedompay.money/get_status3.php",// your preferred url
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return back();
        } else {            
            $response = simplexml_load_string($response);
            $response = json_encode($response); 
            $response = json_decode($response, true);            
            $user->subscription_link = '';
            if($response['pg_payment_status'] == "success"){
                $user->subscription_paymed = '';

                $user->subscription = date("m.d.Y"); 

            }else if($response['pg_payment_status'] == "waiting"){
                $user->subscription_paymed = '';
                $user->subscription = '';
                $user->subscription_duration = '';
            }
            $user->save();
            return back();
        }
    }

    public function update(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->user()->id)->first();       
        
        $user->subscription_duration = $request->month;
        $user->subscription = $request->subscription;

        $user->save();
        $request->user()->save();
        return back();
    
    }
}

