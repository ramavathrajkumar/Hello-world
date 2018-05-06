<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PaymentController extends Controller {
	//
	public function httppost(Request $req) {
		$name = $req['name'];
		$email = $req['email_id'];
		$mobile = $req['phone_no'];
		$SALT = 'eCwWELxi';
		$id = rand(8, 10);
		$posted = array(
			'key' => 'gtKFFx',
			'txnid' => $id,
			'amount' => '500',
			'firstname' => $name,
			'email' => $email,
			'phone' => $mobile,
			'productinfo' => 'sellers',
			'surl' => 'http://127.0.0.1:8000',
			'furl' => 'http://127.0.0.1:8000',
		);

		$PAYU_BASE_URL = "https://test.payu.in";
		$hashSequence = "key|txnid|amount|productinfo|firstname|email|surl|furl|";
		if (empty($posted['hash'])) {
			if (
				empty($posted['key'])
				|| empty($posted['txnid'])
				|| empty($posted['amount'])
				|| empty($posted['firstname'])
				|| empty($posted['email'])
				|| empty($posted['phone'])
				|| empty($posted['productinfo'])
				|| empty($posted['surl'])
				|| empty($posted['furl'])) {
				$formError = 1;
			} else {
				$hashVarsSeq = explode('|', $hashSequence);
				$hash_string = '';
				foreach ($hashVarsSeq as $hash_var) {
					$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
					$hash_string .= '|';
				}
				$hash_string .= $SALT;
				$hash = strtolower(hash('sha512', $hash_string));
				$action = $PAYU_BASE_URL . '/_payment';
			}
		}
		$client = new Client();
		return $client->request('POST', 'https://secure.payu.in/_payment', [
			'form_params' => [
				'hash' => $hash,
				'key' => 'gtKFFx',
				'txnid' => $id,
				'amount' => '500',
				'firstname' => $name,
				'email' => $email,
				'phone' => $mobile,
				'productinfo' => 'sellers',
				'surl' => 'http://127.0.0.1:8000',
				'furl' => 'http://127.0.0.1:8000',
			],
		]);
	}
	public function success() {
		$status = $_POST["status"];
		$firstname = $_POST["firstname"];
		$amount = $_POST["amount"];
		$txnid = $_POST["txnid"];
		$posted_hash = $_POST["hash"];
		$key = $_POST["key"];
		$productinfo = $_POST["productinfo"];
		$email = $_POST["email"];
		$salt = "GQs7yium";

		If (isset($_POST["additionalCharges"])) {
			$additionalCharges = $_POST["additionalCharges"];
			$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;

		} else {

			$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;

		}
		$hash = hash("sha512", $retHashSeq);

		if ($hash != $posted_hash) {
			echo "Invalid Transaction. Please try again";
		} else {

			echo "Thank You. Your order status is " . $status . ".";
			echo "Your Transaction ID for this transaction is " . $txnid . ".";
			echo "We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.";

		}
	}
}
