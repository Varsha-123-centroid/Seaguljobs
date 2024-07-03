<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReceiptMail;
use App\Models\Temple;
use App\Models\Member;
use App\Models\Deity;
use App\Models\Pooja;
use App\Models\Wishlist;
use App\Models\Order;
use App\Models\Darshan;
use App\Models\Timeslot;
use App\Models\State;
use App\Models\Delivery_address;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;


class BookingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer_auth');
    }
    public function index()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        
        $today_booking=Order::where('orders.created_by',$customer_id)
        ->join('members','members.id','=','orders.member_id')->join('poojas','poojas.id','=','orders.pooja')
        ->join('birth_stars','birth_stars.id','=','members.star')->select('*','orders.id')->orderBy('orders.id', 'desc')->get();
        
        return view('user.bookings',compact('today_booking'));
    }
    public function book_pooja()
    {
        $customer_id = Auth::guard('customer')->user()->id;

        $wishlist=Wishlist::join('members','members.id','=','wishlists.member_id')->join('poojas','poojas.id','=','wishlists.pooja')
        ->join('birth_stars','birth_stars.id','=','members.star')->where('wishlists.created_by',$customer_id)
        ->select('wishlists.id','pooja_start','pooja_end','full_name','pooja_name','star_name','qty_per_day','total_price')->get();
        $totalprice=Wishlist::where('wishlists.created_by',$customer_id)->sum('total_price');
        
        $temples = Temple::get();
        $members=Member::join('birth_stars', 'birth_stars.id', '=', 'members.star')
        ->where('created_by',$customer_id)->select('*','members.id')->get();
        $delivery_address=Delivery_address::where('created_by',$customer_id)->orderBy('id', 'desc')->get();
        $state=State::orderBy('state', 'ASC')->get();
        return view('user.bookview',compact('temples','members','wishlist','totalprice','delivery_address','state'));
    }
    public function get_deity(Request $request,$id)
    {
        $deity=Deity::where('temple_id',$id)->orderBy('id', 'DESC')->get();
        return json_encode($deity);
    }
    public function get_pooja(Request $request)
    {
        
        $pooja=Pooja::where('temple_id',$request->input('temple'))->where('deity_id',$request->input('id'))->orderBy('id', 'DESC')->get();
        return json_encode($pooja);
    }
    public function select_pooja(Request $request,$id)
    {
        $poojadetial=Pooja::where('id',$id)->first();
        return json_encode($poojadetial);
    }
    public function add_to_wishlist(Request $request)
    {
        try {
        $address_id=0;
        $customer_id = Auth::guard('customer')->user()->id;
        if(!empty($request->input('addr_id'))){
                $address_id=$request->input('addr_id');
        }
        elseif(!empty($request->input('state'))){
        $newaddress= new Delivery_address;
        $newaddress->mobile=$request->input('mobile_num');
        $newaddress->region=$request->input('region');
        $newaddress->house_name=$request->input('house');
        $newaddress->state=$request->input('state');
        $newaddress->district=$request->input('district');
        $newaddress->city=$request->input('city');
        $newaddress->pincode=$request->input('pincode');
        $newaddress->created_by=$customer_id;
        $save= $newaddress->save();
        $address_id= $newaddress->id; 
        }
         if($request->input('enddate')==''){
            $enddate=$request->input('poojadate');
         }else{
            $enddate=$request->input('enddate');
         }
        $newwishlist= new Wishlist;
        $newwishlist->member_id=$request->input('member');
        $newwishlist->temple_id=$request->input('temple');
        $newwishlist->deity_id=$request->input('deity');
        $newwishlist->pooja=$request->input('pooja');
        $newwishlist->unit_price=$request->input('unit_price');
        $newwishlist->pooja_start=$request->input('poojadate');
        $newwishlist->pooja_end=$enddate;
        $newwishlist->days=$request->input('no_days');
        $newwishlist->qty_per_day=$request->input('qty');
        $newwishlist->total_price=$request->input('total_price');
         $newwishlist->address_id=$address_id;
        $newwishlist->created_by=$customer_id;
        $save= $newwishlist->save();


        if($save)
        {
        return response()->json(['status'=>true,'message' => 'Add To Wishlist']);
                  
           }
  
       else
        {
            return response()->json(['status'=>false,'message' => 'Something went wrong']);
         }
        } catch (\Exception $e) {

            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function place_order()
    {
        // $uid = uniqId();
        $merchTxnId = uniqId();
        $customer_id = Auth::guard('customer')->user()->id;

        $wishlistIds = Wishlist::where('created_by', $customer_id)->pluck('id');
        
            foreach($wishlistIds as $id){

             $ins = DB::insert("
                    INSERT INTO orders (member_id, temple_id, deity_id, pooja, unit_price, pooja_start, pooja_end, days, qty_per_day, total_price,address_id, created_by, order_no)
                    SELECT member_id, temple_id, deity_id, pooja, unit_price, pooja_start, pooja_end, days, qty_per_day, total_price,address_id, created_by, ? 
                    FROM wishlists
                    WHERE id = ?
                ", [$merchTxnId, $id]);
            // Mail::to($request->user())->send(new ReceiptMail());
            $htmlContent = view('email.receipt')->render();
            $emailData=array("from_name"=>"BG","to"=>"arunca231@gmail.com","subject"=>"Bg order mail","message"=>$htmlContent);
         $ch = curl_init();
            
           
            curl_setopt($ch, CURLOPT_URL, 'https://paradigm.qa/api.php');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                
                'content-type: application/json'
            ]);
            
           
            $response = curl_exec($ch);
            
            curl_close($ch);
            // mail
            $del=Wishlist::where('id',$id)->delete(); 

            }
            // payment
             
             $amount =  Order::where('order_no', $merchTxnId)->sum('total_price');
            //  $amount = 1;
             $login = "8952";
             $password = "Test@123";
             $product_id = "NSE";
             $date = date('Y-m-d H:i:s'); // current date
             $encRequestKey = "A4476C2062FFA58980DC8F79EB6A799E";
             $decResponseKey = "75AEF0FA1B94B3C10D4F5B268F757F11";
             $api_url = "https://caller.atomtech.in/ots/aipay/auth";
             $user_email = Auth::guard('customer')->user()->email ;
             $user_contact_number = Auth::guard('customer')->user()->mobile;
             $return_url = "https://shankarodathkovilakam.com/kovilakam_virtual/response";
        
             $payData = array(
                          'login'=>$login,
                          'password'=>$password,
                          'amount'=>$amount,
                          'prod_id'=>$product_id,
                          'txnId'=>$merchTxnId,
                          'date'=>$date,
                          'encKey'=>$encRequestKey,
                          'decKey'=>$decResponseKey,
                          'payUrl'=>$api_url,
                          'email'=>$user_email,
                          'mobile'=>$user_contact_number,
                          'txnCurrency'=>'INR',
                          'return_url'=>$return_url,
                          'udf1'=>"",  // optional
                          'udf2'=>"",  // optional 
                          'udf3'=>"",  // optional
                          'udf4'=>"",  // optional
                          'udf5'=>""   // optional
                         );
         
             $atomTokenId = $this->createTokenId($payData);
             return view('payment.atompay')->with('data', $payData)
                                   ->with('atomTokenId', $atomTokenId);


            // end payment       
        //     if($del)
        //     {
        //     return redirect(route('kovilakam.bookpooja'))->with('success','your order has been placed successfully !');			
        //        }
        //    else
        //     {
        //     return redirect()->back()->with('error','Something Went Wrong');
        //      } 
            
        
    }
    public function get_report(Request $request)
    {

        $start = $request->from;
        $end = $request->to;
      
         $bookings=Order::where('orders.pooja_start', '>=', $start)->where('orders.pooja_start', '<=', $end)
         ->join('members','members.id','=','orders.member_id')->join('poojas','poojas.id','=','orders.pooja')
         ->join('birth_stars','birth_stars.id','=','members.star')
         ->select('*','orders.id')->get();

         return json_encode($bookings);
       
    }
    public function detail_report(Request $request,$id)
    {


         $booking=Order::where('orders.id', $id)
         ->join('members','members.id','=','orders.member_id')->join('poojas','poojas.id','=','orders.pooja')
         ->join('temples','temples.id','=','orders.temple_id')->join('deities','deities.id','=','orders.deity_id')
         ->join('birth_stars','birth_stars.id','=','members.star')
         ->select('*','orders.id')->first();

         return view('user.bookingdetailView',compact('booking'));
       
    }
    public function remove_wishlist(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $id=$request->input('id');
        $del=Wishlist::where('id',$id)->delete();
        $totalprice=Wishlist::where('wishlists.created_by',$customer_id)->sum('total_price');
        $count=Wishlist::where('wishlists.created_by',$customer_id)->count();
        if($del)
        {
            return response()->json(['status'=>true,'message' => 'Removed successfully','count'=>$count,'totalprice'=>$totalprice]);
                      
               }
      
           else
            {
                return response()->json(['status'=>false,'message' => 'Something went wrong']);
             }
    }
    public function clear_wishlist(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $del=Wishlist::where('created_by',$customer_id)->delete();
        $totalprice=Wishlist::where('wishlists.created_by',$customer_id)->sum('total_price');
        
        if($del)
        {
            return response()->json(['status'=>true,'message' => 'Wishlist cleared','totalprice'=>$totalprice]);
                      
               }
      
           else
            {
                return response()->json(['status'=>false,'message' => 'Something went wrong']);
             }
    }
    public function book_darshan()
    {
        $customer_id = Auth::guard('customer')->user()->id;

        
        
        $temples = Temple::get();
        $members=Member::join('birth_stars', 'birth_stars.id', '=', 'members.star')
        ->where('created_by',$customer_id)->select('*','members.id')->get();
        return view('user.bookdarshan',compact('temples','members'));
    }
    public function store_book_darshan(Request $request)
    {
            $customer_id = Auth::guard('customer')->user()->id;
            $uid = Str::uuid();
            
            $name=$request->input('name');
            $age=$request->input('age');
            $gender=$request->input('gender');
            $id_type=$request->input('id_type');
            $idcard_no=$request->input('idcard_no');
            $mob_no=$request->input('mobile_no');
            
            $jasonname=json_encode($name);
            $jasonage=json_encode($age);
            $jasongender=json_encode($gender);
            $jasonid_type=json_encode($id_type);
            $jasonidcard_no=json_encode($idcard_no);
            $jasonmob_no=json_encode($mob_no);
            
            $insertDarshan= new Darshan;
            $insertDarshan->temple_id=$request->input('temple_id');
            $insertDarshan->date=$request->input('date');
            $insertDarshan->time=$request->input('time');
            $insertDarshan->no_person=$request->input('no_per');
            $insertDarshan->name=$jasonname;
            $insertDarshan->age=$jasonage;
            $insertDarshan->gender=$jasongender;
            $insertDarshan->id_type=$jasonid_type;
            $insertDarshan->idcard_no=$jasonidcard_no;
            $insertDarshan->mobile_no=$jasonmob_no;
            $insertDarshan->uniq_code= $uid.'-'.$request->input('date');
            $insertDarshan->created_by=$customer_id;
            $save= $insertDarshan->save();

            if($save)
            {
            return response()->json(['status'=>true,'message' => 'Darshan Booked Successfully']);
                      
               }
      
           else
            {
                return response()->json(['status'=>false,'message' => 'Something went wrong']);
             }
    }
    public function darshan_bookings_history()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $booking=Darshan::where('darshans.created_by',$customer_id)->join('temples','temples.id','=','darshans.temple_id')->orderBy('darshans.id', 'DESC')
        ->select('*','darshans.id')->get();
        
        return view('user.darshanbookings',compact('booking'));
    }
    public function darshan_booking(Request $request,$id)
    {

         $darshan=Darshan::where('darshans.id', $id)->join('temples','temples.id','=','darshans.temple_id')
         ->first();
         
        $data=array(
            'temple'=>$darshan->temple,
            'date'=>$darshan->date,
            'time'=>$darshan->time,
            'no_person'=>$darshan->no_person,
            'name'=>json_decode($darshan['name']),
            'age'=>json_decode($darshan['age']),
            'gender'=>json_decode($darshan['gender']),
            'id_type'=>json_decode($darshan['id_type']),
            'idcard_no'=>json_decode($darshan['idcard_no']),
            'mobile'=>json_decode($darshan['mobile_no']),
        );
       
         return view('user.darshanbookDetail',compact('data'));
       
    }
    public function get_receipt(Request $request,$id)
    {


         $booking=Order::where('orders.id', $id)
         ->join('members','members.id','=','orders.member_id')->join('poojas','poojas.id','=','orders.pooja')
         ->join('temples','temples.id','=','orders.temple_id')->join('deities','deities.id','=','orders.deity_id')
         ->join('birth_stars','birth_stars.id','=','members.star')
         ->select('*','orders.id')->first();
         $csrfToken = Session::token();
     
         $pdfOptions = [
             
             'defaultFont' => 'Noto Sans Malayalam',
         ];
         return view('pdf.user_receipt', ['data'=>$booking,'csrfToken' => $csrfToken]);
        // PDF::setOptions($pdfOptions);
         
        //$pdf = Pdf::loadView('pdf.user_receipt', ['data'=>$booking]);
       //  return $pdf->download('receipt.pdf');
       
    }
    public function get_receipt_download(Request $request)
    {
       
        $base64Data = $request->input('data');
    
        // Check if the data is present
        if ($base64Data) {
            // Remove the data:image/...;base64 part
            $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
    
            // Decode the Base64 data to binary
            $imageData = base64_decode($data);
    
            // Generate a unique filename for the image
            $filename = 'image_' . time() . '.png';
    // Define the path within the public folder
    $publicPath = public_path('receipt/'.$filename);

            // Save the image to the specified storage location
            if (file_put_contents($publicPath, $imageData)) {
                // Image saved successfully
                return response()->json(['message' => 'Image saved successfully', 'filename' => $filename]);
            } else {
                // Failed to save the image
                return response()->json(['error' => 'Failed to save the image']);
            }
        } else {
            // No Base64 data provided
            return response()->json(['error' => 'No Base64 data provided']);
        }
    }
    public function response()
    {
             $data = $_POST['encData'];
        
             // change decryption key below for production
             $decData = $this->decrypt($data, '75AEF0FA1B94B3C10D4F5B268F757F11', '75AEF0FA1B94B3C10D4F5B268F757F11');
             $jsonData = json_decode($decData, true);
                   
              if($jsonData['payInstrument']['responseDetails']['statusCode'] == 'OTS0000'){
                  echo 'Payment status = Transaction Successful';
                  echo "<br>";
                  echo 'Transaction id = '.$jsonData['payInstrument']['merchDetails']['merchTxnId'];
                  echo "<br>";
                  echo 'Transaction date = '.$jsonData['payInstrument']['merchDetails']['merchTxnDate']; 
                  echo "<br>";
                  echo 'Bank transaction id = '.$jsonData['payInstrument']['payModeSpecificData']['bankDetails']['bankTxnId'];
                  $order_id=$jsonData['payInstrument']['merchDetails']['merchTxnId'];
                  $order = Order::where('order_no', $order_id)->first();
                  $order->update(['payment_status' => 1]);
               }else{
                  echo 'Payment status = Transaction Failed';
              }
              echo "<pre>";
              print_r($jsonData);  
    }
    
    //do not change anything in below function
    public function createTokenId($data)
    {
           $jsondata = '{
                "payInstrument": {
                    "headDetails": {
                        "version": "OTSv1.1",      
                        "api": "AUTH",  
                        "platform": "FLASH"	
                    },
                    "merchDetails": {
                        "merchId": "'.$data['login'].'",
                        "userId": "",
                        "password": "'.$data['password'].'",
                        "merchTxnId": "'.$data['txnId'].'",      
                        "merchTxnDate": "'.$data['date'].'"
                    },
                    "payDetails": {
                        "amount": "'.$data['amount'].'",
                        "product": "'.$data['prod_id'].'",
                        "custAccNo": "213232323",
                        "txnCurrency": "'.$data['txnCurrency'].'"
                    },	
                    "custDetails": {
                        "custEmail": "'.$data['email'].'",
                        "custMobile": "'.$data['mobile'].'"
                    },
                    "extras": {
                        "udf1": "'.$data['udf1'].'",  
                        "udf2": "'.$data['udf2'].'",  
                        "udf3": "'.$data['udf3'].'", 
                        "udf4": "'.$data['udf4'].'",  
                        "udf5": "'.$data['udf5'].'" 
                    }
                }  
            }';
        
             $encData = $this->encrypt($jsondata, $data['encKey'], $data['encKey']);
         
             $curl = curl_init();
             curl_setopt_array($curl, array(
                  CURLOPT_URL => $data['payUrl'],
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_SSL_VERIFYHOST => 2,
                  CURLOPT_SSL_VERIFYPEER => 1,
                  CURLOPT_CAINFO => dirname(__FILE__).'/cacert.pem', //added in Controllers folder
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "encData=".$encData."&merchId=".$data['login'],
                  CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded"
                  ),
            ));
            $atomTokenId = null;
            $response = curl_exec($curl);
                    $getresp = explode("&", $response); 
                    $encresp = substr($getresp[1], strpos($getresp[1], "=") + 1);
                    $decData = $this->decrypt($encresp, $data['decKey'], $data['decKey']);
                    if(curl_errno($curl)) {
                        $error_msg = curl_error($curl);
                        echo "error = ".$error_msg;
                    }      
                    if(isset($error_msg)) {
                        echo "error = ".$error_msg;
                    }   
                    curl_close($curl);
                    $res = json_decode($decData, true);
                    if($res){
                      if($res['responseDetails']['txnStatusCode'] == 'OTS0000'){
                        $atomTokenId = $res['atomTokenId'];
                      }else{
                        echo "Error getting data";
                         $atomTokenId = null;
                      }
                    }
            return $atomTokenId;
    }
    
    //do not change anything in below function 
    public function encrypt($data, $salt, $key)
    { 
            $method = "AES-256-CBC";
            $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
            $chars = array_map("chr", $iv);
            $IVbytes = join($chars);
            $salt1 = mb_convert_encoding($salt, "UTF-8"); //Encoding to UTF-8
            $key1 = mb_convert_encoding($key, "UTF-8"); //Encoding to UTF-8
            $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 
            $encrypted = openssl_encrypt($data, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
            return strtoupper(bin2hex($encrypted));
    }  
    
    //do not change anything in below function
    public function decrypt($data, $salt, $key)
    {
            $dataEncypted = hex2bin($data);
            $method = "AES-256-CBC";
            $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
            $chars = array_map("chr", $iv);
            $IVbytes = join($chars);
            $salt1 = mb_convert_encoding($salt, "UTF-8");//Encoding to UTF-8
            $key1 = mb_convert_encoding($key, "UTF-8");//Encoding to UTF-8
            $hash = openssl_pbkdf2($key1,$salt1,'256','65536', 'sha512'); 
            $decrypted = openssl_decrypt($dataEncypted, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
            return $decrypted;
    }
    public function get_slot(Request $request)
    {
        $data=[];
        $date = $request->slot_date;
        $temple = $request->temple;
       
        $time=[];
        $time_period=[];
        $slot=[];
         
         $timeslot=Timeslot::where('date', '=', $date)->where('temp_id', $temple)->first();
         if(!empty($timeslot)){
         $timearray=json_decode($timeslot->time);
         $time_periodarray=json_decode($timeslot->time_period);
         $ticketsarray=json_decode($timeslot->tickets);
          $i=0;
         foreach($timearray as $row){
            
           $booked = Darshan::where('date', $date)->whereRaw("TIME_FORMAT(time, '%H:%i') = ?", [$row])->count();
           
           $time[]=$row;
           $time_period[]=$time_periodarray[$i];
           $slot[]=$ticketsarray[$i]-$booked;
           $i++;
         }
         
         $data=array(
            "time"=>$time,
            "time_period"=>$time_period,
            "avil_slot"=>$slot,
            );
    }

         return json_encode($data);
       
    }
}
