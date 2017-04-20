<?php

namespace Omnipay\CyberSourceSoap\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * Cybersource Response
 */
class CommonResponse extends AbstractResponse
{
	/** @var \stdClass  */
	protected $response = null;

	protected $statusOK = false;
	protected $responseMessage = "";
	protected $responseReasonCode = "";
	protected $requestId = "";
	protected $requestToken = "";
	protected $processorTransactionId = "";
	protected $reconciliationId = "";
	protected $authReconciliationId = "";
	protected $authRecord = "";
	protected $verificationCode = "";
	protected $verificationCodeRaw = "";
    protected $merchantReferenceCode;
    protected $subscriptionReconciliationId;

    /**
	 * @return string
	 */
	public function getResponseMessage()
	{
		return $this->responseMessage;
	}

	/**
	 * @return string
	 */
	public function getResponseReasonCode()
	{
		return $this->responseReasonCode;
	}

	/**
	 * @return string
	 */
	public function getRequestId()
	{
		return $this->requestId;
	}

	/**
	 * @return string
	 */
	public function getRequestToken()
	{
		return $this->requestToken;
	}

	/**
	 * @return string
	 */
	public function getProcessorTransactionId()
	{
		return $this->processorTransactionId;
	}

	/**
	 * @return string
	 */
	public function getReconciliationId()
	{
		return $this->reconciliationId;
	}

	/**
	 * @return string
	 */
	public function getAuthReconciliationId()
	{
		return $this->authReconciliationId;
	}

	/**
	 * @return string
	 */
	public function getAuthRecord()
	{
		return $this->authRecord;
	}

	/**
	 * @return string
	 */
	public function getVerificationCode()
	{
		return $this->verificationCode;
	}

	/**
	 * @return string
	 */
	public function getVerificationCodeRaw()
	{
		return $this->verificationCodeRaw;
	}

	protected static $avs_codes = [
		'A' => 'Partial match: Street address matches, but 5-digit and 9-digit postal codes do not match.',
		'B' => 'Partial match: Street address matches, but postal code is not verified.',
		'C' => 'No match: Street address and postal code do not match.',
		'D' => 'Match: Street address and postal code match.',
		'E' => 'Invalid: AVS data is invalid or AVS is not allowed for this card type.',
		'F' => 'Partial match: Card member\'s name does not match, but billing postal code matches.',
		'G' => 'Not supported: Non-U.S. issuing bank does not support AVS.',
		'H' => 'Partial match: Card member\'s name does not match, but street address and postal code match.',
		'I' => 'No match: Address not verified.',
		'K' => 'Partial match: Card member\'s name matches, but billing address and billing postal code do not match.',
		'L' => 'Partial match: Card member\'s name and billing postal code match, but billing address does not match.',
		'M' => 'Match: Street address and postal code match.',
		'N' => 'No match: Card member\'s name, street address, or postal code do not match.',
		'O' => 'Partial match: Card member\'s name and billing address match, but billing postal code does not match.',
		'P' => 'Partial match: Postal code matches, but street address not verified.',
		'R' => 'System unavailable.',
		'S' => 'Not supported: U.S. issuing bank does not support AVS.',
		'T' => 'Partial match: Card member\'s name does not match, but street address matches.',
		'U' => 'System unavailable: Address information is unavailable because either the U.S. bank does not support non-U.S. AVS or AVS in a U.S. bank is not functioning properly.',
		'V' => 'Match: Card member\'s name, billing address, and billing postal code match.',
		'W' => 'Partial match: Street address does not match, but 9-digit postal code matches.',
		'X' => 'Match: Street address and 9-digit postal code match.',
		'Y' => 'Match: Street address and 5-digit postal code match.',
		'Z' => 'Partial match: Street address does not match, but 5-digit postal code matches.',
		'1' => 'Not supported: AVS is not supported for this processor or card type.',
		'2' => 'Unrecognized: The processor returned an unrecognized value for the AVS response.',
	];

	protected static $cvn_codes = [
		'D' => 'The transaction was determined to be suspicious by the issuing bank.',
		'I' => 'The CVN failed the processor\'s data validation check.',
		'M' => 'The CVN matched.',
		'N' => 'The CVN did not match.',
		'P' => 'The CVN was not processed by the processor for an unspecified reason.',
		'S' => 'The CVN is on the card but waqs not included in the request.',
		'U' => 'Card verification is not supported by the issuing bank.',
		'X' => 'Card verification is not supported by the card association.',
		'1' => 'Card verification is not supported for this processor or card type.',
		'2' => 'An unrecognized result code was returned by the processor for the card verification response.',
		'3' => 'No result code was returned by the processor.',
	];

	protected static $result_codes = [
		100 => 'Successful transaction',
		101 => 'Declined - The request is missing one or more fields',
		102 => 'Declined - One or more fields in the request contains invalid data',
		104 => 'Declined - The merchantReferenceCode sent with this authorization request matches the merchantReferenceCode of another authorization request that you sent in the last 15 minutes.',
		110 => 'Partial amount was approved',
		150 => 'Error - General system failure.',
		151 => 'Error - The request was received but there was a server timeout. This error does not include timeouts between the client and the server.',
		152 => 'Error: The request was received, but a service did not finish running in time.',
		200 => 'Soft Decline - The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the Address Verification Service (AVS) check.',
		201 => 'Decline - The issuing bank has questions about the request. You do not receive an authorization code programmatically, but you might receive one verbally by calling the processor.',
		202 => 'Decline - Expired card. You might also receive this if the expiration date you provided does not match the date the issuing bank has on file. Note: The ccCreditService does not check the expiration date; instead, it passes the request to the payment processor. If the payment processor allows issuance of credits to expired cards, CyberSource does not limit this functionality.',
		203 => 'Decline - General decline of the card. No other information provided by the issuing bank.',
		204 => 'Decline - Insufficient funds in the account.',
		205 => 'Decline - Stolen or lost card.',
		207 => 'Decline - Issuing bank unavailable.',
		208 => 'Decline - Inactive card or card not authorized for card-not-present transactions.',
		209 => 'Decline - card verification number (CVN) did not match.',
		210 => 'Decline - The card has reached the credit limit.',
		211 => 'Decline - Invalid Card Verification Number (CVN).',
		220 => 'Decline - Generic Decline.',
		221 => 'Decline - The customer matched an entry on the processor\'s negative file.',
		222 => 'Decline - customer\'s account is frozen',
		230 => 'Soft Decline - The authorization request was approved by the issuing bank but declined by CyberSource because it did not pass the card verification number (CVN) check.',
		231 => 'Decline - Invalid account number',
		232 => 'Decline - The card type is not accepted by the payment processor.',
		233 => 'Decline - General decline by the processor.',
		234 => 'Decline - There is a problem with your CyberSource merchant configuration.',
		235 => 'Decline - The requested amount exceeds the originally authorized amount. Occurs, for example, if you try to capture an amount larger than the original authorization amount.',
		236 => 'Decline - Processor failure.',
		237 => 'Decline - The authorization has already been reversed.',
		238 => 'Decline - The transaction has already been settled.',
		239 => 'Decline - The requested transaction amount must match the previous transaction amount.',
		240 => 'Decline - The card type sent is invalid or does not correlate with the credit card number.',
		241 => 'Decline - The referenced request id is invalid for all follow-on transactions.',
		242 => 'Decline - The request ID is invalid. You requested a capture, but there is no corresponding, unused authorization record. Occurs if there was not a previously successful authorization request or if the previously successful authorization has already been used in another capture request.',
		243 => 'Decline - The transaction has already been settled or reversed.',
		246 => 'Decline - The capture or credit is not voidable because the capture or credit information has already been submitted to your processor. Or, you requested a void for a type of transaction that cannot be voided.',
		247 => 'Decline - You requested a credit for a capture that was previously voided.',
		248 => 'Decline - The boleto request was declined by your processor.',
		250 => 'Error - The request was received, but there was a timeout at the payment processor.',
		251 => 'Decline - The Pinless Debit card\'s use frequency or maximum amount per use has been exceeded.',
		254 => 'Decline - Account is prohibited from processing stand-alone refunds.',
		400 => 'Soft Decline - Fraud score exceeds threshold.',
		450 => 'Apartment number missing or not found.',
		451 => 'Insufficient address information.',
		452 => 'House/Box number not found on street.',
		453 => 'Multiple address matches were found.',
		454 => 'P.O. Box identifier not found or out of range.',
		455 => 'Route service identifier not found or out of range.',
		456 => 'Street name not found in Postal code.',
		457 => 'Postal code not found in database.',
		458 => 'Unable to verify or correct address.',
		459 => 'Multiple addres matches were found (international)',
		460 => 'Address match not found (no reason given)',
		461 => 'Unsupported character set',
		475 => 'The cardholder is enrolled in Payer Authentication. Please authenticate the cardholder before continuing with the transaction.',
		476 => 'Encountered a Payer Authentication problem. Payer could not be authenticated.',
		480 => 'The order is marked for review by Decision Manager',
		481 => 'The order has been rejected by Decision Manager',
		520 => 'Soft Decline - The authorization request was approved by the issuing bank but declined by CyberSource based on your Smart Authorization settings.',
		700 => 'The customer matched the Denied Parties List',
		701 => 'Export bill_country/ship_country match',
		702 => 'Export email_country match',
		703 => 'Export hostname_country/ip_country match',
	];


	public function __construct($request, $response)
	{
		$this->request = $request;
		$this->response = $response;

		$this->goThroughResponse();
	}



	protected function goThroughResponse(){
		// customize the error message if the reason indicates a field is missing
		if ($this->response->reasonCode == 101) {
			$missing_fields = 'Missing fields: ';
			if (!isset($this->response->missingField)) {
				$missing_fields = $missing_fields.'Unknown';
			} elseif (is_array($this->response->missingField)) {
				$missing_fields = $missing_fields.implode(', ', $this->response->missingField);
			} else {
				$missing_fields = $missing_fields.$this->response->missingField;
			}
			$this->statusOK = false;
			$this->responseMessage =  $missing_fields;
			$this->responseReasonCode = $this->response->reasonCode;
			return;
		}

		// customize the error message if the reason code indicates a field is invalid
		if ($this->response->reasonCode == 102) {
			$invalid_fields = 'Invalid fields: ';
			if (!isset($this->response->invalidField)) {
				$invalid_fields = $invalid_fields.'Unknown';
			} elseif (is_array($this->response->invalidField)) {
				$invalid_fields = $invalid_fields.implode(', ', $this->response->invalidField);
			} else {
				$invalid_fields = $invalid_fields.$this->response->invalidField;
			}
			$this->statusOK = false;
			$this->responseMessage =  $invalid_fields;
			$this->responseReasonCode = $this->response->reasonCode;
			return;
		}

		$this->statusOK = in_array($this->response->reasonCode, [100]);
//		$this->statusOK = in_array($this->response->decision, ['ACCEPT', 'REVIEW', ]);
//		$this->statusOK = true;
		$this->requestId = $this->response->requestID;
		$this->requestToken = $this->response->requestToken;
		$this->merchantReferenceCode = $this->response->merchantReferenceCode;
		$this->responseReasonCode = $this->response->reasonCode;
		$this->responseMessage =  self::$result_codes[ $this->response->reasonCode ];

		$this->authReconciliationId = isset($this->response->ccAuthReply->reconciliationID) ? $this->response->ccAuthReply->reconciliationID : null;
		$this->authRecord = isset($this->response->ccAuthReply->authRecord) ? $this->response->ccAuthReply->authRecord : null;
		$this->reconciliationId = isset($this->response->ccCaptureReply->reconciliationID) ? $this->response->ccCaptureReply->reconciliationID : null;
		$this->subscriptionReconciliationId = isset($this->response->paySubscriptionCreateReply->reconciliationID) ? $this->response->paySubscriptionCreateReply->reconciliationID : null;

		if (isset($this->response->ecDebitReply)) {
			$this->reconciliationId = $this->response->ecDebitReply->reconciliationID;
			$this->processorTransactionId = $this->response->ecDebitReply->processorTransactionID;
			$this->verificationCode = $this->response->ecDebitReply->verificationCode;
			$this->verificationCodeRaw = $this->response->ecDebitReply->verificationCodeRaw;
		}
	}

	public function getToken(){
	    return $this->subscriptionReconciliationId;
    }

	public function isSuccessful()
	{
		return $this->statusOK;
	}

	public function isPending(){
		return in_array($this->responseReasonCode, [
			200,
			201,
			230,
			480,
			520
		]);
	}

	public function getMessage()
	{
		return (string)$this->responseMessage;
	}

	public function getReasonCode()
	{
		return $this->responseReasonCode;
	}

    public function getTransactionReference()
    {
        return $this->requestId;
    }

    public function getTransactionId()
    {
        return $this->merchantReferenceCode;
    }


}
