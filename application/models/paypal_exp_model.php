<?php

class Paypal_exp_model extends CI_Model{

	

	function __construct() {

		parent::__construct();

	}

	

	private $GateWayState='sandbox'; //sandbox         live
    private $APIUserName='';

    private $APIPassword='';

    private $APISignature='';

    private $SbnCode='';

    private $URL='';

    private $EndPoint="";

    private $Version="98";

    private $Host="";

    private $IPNnEndpoint='';

    private $CurrencyCodeType='USD';

    private $PaymentType='Sale';



    /**

     * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

     * @description :- It will return re-assign payment gatway config data for API access

     */

    private function Init(){

        //if($this->GateWayState=='sandbox'){

            $sql="SELECT * FROM payment_gateway_config WHERE type='".$this->GateWayState."' AND gatewayName='expresscheckout'";

            $result=$this->db->query($sql)->result();

            $this->APIUserName=$result[0]->username;

            $this->APIPassword=$result[0]->password;

            $this->APISignature=$result[0]->signature;

            $this->EndPoint=$result[0]->endpoint;

            $this->URL=$result[0]->url;

            $this->Host=$result[0]->host;

            $this->IPNnEndpoint=$result[0]->ipn_endpoint;

			//echo '<pre>';print_r($result);die;

        //}

    }

    

    public function PaymentIniteate(){

        $this->CallExpressCheckout();

    }

    

    public function PaymentFinalize(){

        return $this->ConfirmPayment();

    }





    /**

     * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

     * @param type $token

     * @return boolean

     * @description :- It will return Express Checkout Details data from API server

     */

    public function GetCheckoutDetails($token){

        $nvpstr="&TOKEN=" . $token;

        $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);

        $ack = strtoupper($resArray["ACK"]);

        if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING"){

            $this->session->set_userdata('PayerID',$resArray['PAYERID']);

            return TRUE;

        }else{

            return FALSE;

        }

    }

    /**

     * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

     * @param type $notifyURL

     * @return type

     * @description :- It will return final payment API for udpate the Subscriotion History and activate the service

     */

    public function ConfirmPayment(){

        $this->Init();

        //$session = JFactory::getSession();

        

        $PayerID= $_GET["PayerID"];

        if(isset($PayerID)){

			$this->session->set_userdata('PayerID',$PayerID);

        }else{

            if($this->GetCheckoutDetails($this->session->userdata('token'))==FALSE){

                // return errr message to user

            }

        }

        $NotifyURL=$this->session->userdata('NotifyURL');

        //Format the other parameters that were stored in the session from the previous calls	

        $token 				= urlencode($this->session->userdata('token'));

        $paymentType 		= urlencode($this->PaymentType);

        $currencyCodeType 	= urlencode($this->CurrencyCodeType);

        $payerID 			= urlencode($this->session->userdata('PayerID'));

        $custom=$this->session->userdata("OrderID").'^'.$this->session->userdata('PaymentType');

        $OrderDetails=$this->session->userdata('OrderDetails');

        

        $serverName 		= urlencode($_SERVER['SERVER_NAME']);

        

        $NOTIFYURL=  urlencode($NotifyURL);

        $nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTREQUEST_0_PAYMENTACTION=' . $paymentType . '&PAYMENTREQUEST_0_AMT=' . $OrderDetails['Charges'];

        $nvpstr .= '&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName.'&NOTIFYURL='.$NOTIFYURL;  

        //die($nvpstr);

         /* Make the call to PayPal to finalize payment

            If an error occured, show the resulting errors

            */

        $resArray=$this->hashCall("DoExpressCheckoutPayment",$nvpstr);

        /* Display the API response back to the browser.

           If the response from PayPal was a success, display the response parameters'

           If the response was an error, display the errors received using APIError.php.

           */

		   //echo '<pre>';print_r($resArray);die;

        return $resArray;

    }

    

    /**

     * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

     * @param type $returnURL

     * @param type $cancelURL

     * @param type $notifyURL

     * @return type

     * @description :- It is start the Express Checkout API and return token for other API calling process

     */

    private function CallExpressCheckout() {

        $resArray=array();

        $this->Init();

        //$session = JFactory::getSession();

        //$sessionModelData=$session->get('seoSale',array(),'com_onseosale');

        $returnURL=$this->session->userdata('returnURL');

        $cancelURL=$this->session->userdata('cancelURL');

        $notifyURL=$this->session->userdata('notifyURL');

        

        $custom=$this->session->userdata('OrderID').'^'.$this->session->userdata('PaymentType');

        $OrderDetails=$this->session->userdata('OrderDetails');

        //------------------------------------------------------------------------------------------------------------------------------------

        // Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation

        //$nvpstr="&AMT=". $sessionModelData["fees"];

        $nvpstr="&PAYMENTREQUEST_0_AMT=". $OrderDetails['Charges'];

        //$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_AMT=". $sessionModelData["fees"];

        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $this->PaymentType;

        $nvpstr = $nvpstr . "&RETURNURL=" . urlencode($returnURL);

        $nvpstr = $nvpstr . "&CANCELURL=" . urlencode($cancelURL);

        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $this->CurrencyCodeType;

        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_NOTIFYURL=" . urlencode($notifyURL);

        

        

        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NAME0=".$OrderDetails['Title'];

        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_NUMBER0=1";

        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_DESC0=".$OrderDetails['Title'];

        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_AMT0=".$OrderDetails['Charges'];

        $nvpstr = $nvpstr . "&L_PAYMENTREQUEST_0_QTY0=1" ;//.$OrderDetails['Qty'];

        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_ITEMAMT=".$OrderDetails['Charges'];

        $nvpstr = $nvpstr . "&SOLUTIONTYPE=Sole";

        //$nvpstr = $nvpstr . "&LANDINGPAGE=Billing";
        $nvpstr = $nvpstr . "&LANDINGPAGE=Login";

        $nvpstr = $nvpstr . "&LOGOIMG=https://www.dailyplaza.com/resources/images/dp_paypal_logo.png";
        $nvpstr = $nvpstr . "&CARTBORDERCOLOR=0000CD";

        $nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CUSTOM=".$custom;

        

        //'--------------------------------------------------------------------------------------------------------------- 

        //' Make the API call to PayPal

        //' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  

        //' If an error occured, show the resulting errors

        //'---------------------------------------------------------------------------------------------------------------

        $resArray=$this->hashCall("SetExpressCheckout", $nvpstr);

		//echo '<pre>';print_r($resArray);die;

        $ack = strtoupper($resArray["ACK"]);

        if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING"){

            $token = urldecode($resArray["TOKEN"]);

            $this->session->set_userdata('token',$token);

            //$session->set('seoSale',$sessionModelData,'com_onseosale');

            $this->redirectToPayPal($token);

        }

	}

    

        /**

         * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

         * @param type $methodName

         * @param type $nvpStr

         * @return type

         * @description :- It is creating API calling data and send them to API server and craete the response array for request function

         */

    private function hashCall($methodName,$nvpStr){

        //die($nvpStr);

        //declaring of global variables

        //$API_UserName=$payapal_api_username='judhisahoo2009-facilitator_api1.gmail.com';

        //$API_Password=$payapal_api_password='38BHWYTQWM94XYBH';

        //$API_Signature=$payapal_api_signature='A75gIvodTAj9-kFzuKTlMsd5qhQ.AOzwnd3RoBTibFAtzXOHYR1o9q8x';

        //setting the curl parameters.

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,  $this->EndPoint);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);



        //turning off the server and peer verification(TrustManager Concept).

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);



        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch, CURLOPT_POST, 1);



        //NVPRequest for submitting to server

        $nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode($this->Version) . "&PWD=" . urlencode($this->APIPassword) . "&USER=" . urlencode($this->APIUserName) . "&SIGNATURE=" . urlencode($this->APISignature) . $nvpStr . "&BUTTONSOURCE=" . urlencode($this->SbnCode);

        //die($nvpreq);

        //setting the nvpreq as POST FIELD to curl

        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);



        //getting response from server

        $response = curl_exec($ch);



        //convrting NVPResponse to an Associative Array

        $nvpResArray=$this->deformatNVP($response);

        $nvpReqArray=$this->deformatNVP($nvpreq);

        

        if (curl_errno($ch)) {

                die('CURL Error arise check here');

        }else {

                 //closing the curl

                curl_close($ch);

        }



        return $nvpResArray;

    }

        

        /**

         * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

         * @param type $nvpstr

         * @return type

         * @description :- TI is create response array for request function

         */

    private function deformatNVP($nvpstr){

        $intial=0;

        $nvpArray = array();



        while(strlen($nvpstr))

        {

            //postion of Key

            $keypos= strpos($nvpstr,'=');

            //position of value

            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);



            /*getting the Key and Value values and storing in a Associative Array*/

            $keyval=substr($nvpstr,$intial,$keypos);

            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);

            //decoding the respose

            $nvpArray[urldecode($keyval)] =urldecode( $valval);

            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));

        }

        return $nvpArray;

    }

        

        /**

         * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

         * @param type $Tid

         * @return type

         * @description :-. It will return Transaction Data for that related to TRansctionId from API server

         */

    public function getTransctionDetails($Tid){

        $this->Init();

        $nvpstr="&TRANSACTIONID=". $Tid;

        $nvpResArray=$this->hashCall('GetTransactionDetails',$nvpstr);

        //echo '<pre>';print_r($nvpResArray);echo '</pre>';

        return $nvpResArray;

	}

        

        /**

         * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

         * @param type $token

         * @description : It will redirect to user to paypal to pay there.

         */

    private function redirectToPayPal ( $token ){

        $PayPalURL = $this->URL . $token;

        header("Location: ".$PayPalURL);exit;

	}

        

        /**

         * @author judhisthira Sahoo<judhisthira.sahoo@onsumaye.com>

         * @param type $dataArr

         * @return type

         * @description :- It will check the data requested are valid data from IPN.

         */

    public function isIpnValid($dataArr){

        $this->Init();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->EndPoint);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataArr);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));

        curl_setopt($ch, CURLOPT_HEADER , 1);   

        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // In wamp like environment where the root authority certificate doesn't comes in the bundle, you need

        // to download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 

        // of the certificate as shown below.

        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');

        $res = curl_exec($ch);

        curl_close($ch);

        return $res;

    }

}

