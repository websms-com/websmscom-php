<?php
 //--------------------------------------------------------------------------------------
 // websms.com XML Gateway usage sample code
 //  1.) create client, 
 //  2.) create message, 
 //  3.) send message with client.
 //--------------------------------------------------------------------------------------
  include "WebSmsCom_Toolkit.inc"; // will need JSON.phps (Service_JSON) for PHP<5.2.0

  # Modify these values to your needs
  $username             = 'your_username';
  $pass                 = 'your_password';
  $gateway_url          = 'https://api.websms.com/';
  
  $recipientAddressList = array("4367612345678");
  $utf8_message_text    = "Willkommen zur BusinessPlatform SDK von websms.com! Diese Nachricht enthält 160 Zeichen. Sonderzeichen: äöüß. Eurozeichen: €. Das Ende wird nun ausgezählt43210";
  
  $maxSmsPerMessage     = 1; 
  $test                 = false; // true: do not send sms for real, just test interface

  try {
    
    // 1.) -- create sms client (once) ------
    $smsClient = new WebSmsCom_Client($username, $pass, $gateway_url);
    //$smsClient->setVerbose(true);

    // 2.) -- create text message ----------------
    $message  = new WebSmsCom_TextMessage($recipientAddressList, $utf8_message_text);
    //$message = binary_sms_sample($recipientAddressList);
    //$maxSmsPerMessage = null;  //needed if binary messages should be send

    // 3.) -- send message ------------------
    $Response = $smsClient->send($message, $maxSmsPerMessage, $test);

    // show success
    print_r(array(
           "Status          : ".$Response->getStatusCode(),
           "StatusMessage   : ".$Response->getStatusMessage(),
           "TransferId      : ".$Response->getTransferId(),
           "ClientMessageId : ".(($Response->getClientMessageId()) ? 
                                $Response->getClientMessageId() : '<NOT SET>'),
    ));

  // catch everything that's not a successfully sent message
  } catch (WebSmsCom_ParameterValidationException $e) {
    exit("ParameterValidationException caught: ".$e->getMessage()."\n");
    
  } catch (WebSmsCom_AuthorizationFailedException $e) {
    exit("AuthorizationFailedException caught: ".$e->getMessage()."\n");
  
  } catch (WebSmsCom_ApiException $e) {
    echo $e; // possibility to handle API status codes $e->getCode()
    exit("ApiException Exception\n");
    
  } catch (WebSmsCom_HttpConnectionException $e) {
    exit("HttpConnectionException caught: ".$e->getMessage()."HTTP Status: ".$e->getCode()."\n");
  
  } catch (WebSmsCom_UnknownResponseException $e) {
    exit("UnknownResponseException caught: ".$e->getMessage()."\n");
    
  } catch (Exception $e) {
    exit("Exception caught: ".$e->getMessage()."\n");
  }

//-- end of main

//-----------------------------------------------
// binary_message_content
//-----------------------------------------------
function binary_sms_sample($recipientAddressList) {
  
  //| Working messageContent sample of PDU sms containing content "Zusammengefügt."
  //| sent as 2 SMS segments: ("Zusammen","gefügt."). 
  //| First 6 Bytes per segment are sample UDH. See http://en.wikipedia.org/wiki/Concatenated_SMS
  
  //$messageContentSegments = array(
  //    "BQAD/AIBWnVzYW1tZW4=", // 0x05,0x00,0x03,0xfc,0x02,0x01, 0x5a,0x75,0x73,0x61,0x6d,0x6d,0x65,0x6e
  //    "BQAD/AICZ2Vmw7xndC4="  // 0x05,0x00,0x03,0xfc,0x02,0x02, 0x67,0x65,0x66,0xc3,0xbc,0x67,0x74,0x2e
  //);

  // bytewise rebuilt sample. (identical to above):
  $messageContentSegments = array(
      base64_encode( pack("c*", 0x05,0x00,0x03,0xfc,0x02,0x01, 0x5a,0x75,0x73,0x61,0x6d,0x6d,0x65,0x6e) ),
      base64_encode( pack("c*", 0x05,0x00,0x03,0xfc,0x02,0x02, 0x67,0x65,0x66,0xc3,0xbc,0x67,0x74,0x2e) )
  );
  $userDataHeaderPresent  = true; # Binary Data includes UserDataHeader for e.G.: PDU sms (Concatenation)  

  $message = new WebSmsCom_BinaryMessage($recipientAddressList, $messageContentSegments, $userDataHeaderPresent);
  
  return $message;
}
//--------------------------------------------------------------------------------------
// WebSmsCom Class DESCRIPTION
//--------------------------------------------------------------------------------------
//  Classes:
//  ---------------------
//  WebSmsCom_Client         - $smsClient = new WebSmsCom_Client($username, $pass, $gateway_url);
//  WebSmsCom_TextMessage    - $message   = new WebSmsCom_TextMessage($recipientAddressList, $utf8MessageText)
//  WebSmsCom_BinaryMessage  - $message   = new WebSmsCom_BinaryMessage($recipientAddressList, $messageContentSegments, $userDataHeaderPresent)
//  WebSmsCom_Response       - returned by $smsClient->send() method
//
//  Client Methods list:
//  ---------------------
//  getConnectionTimeout
//  setConnectionTimeout     - $smsClient->setConnectionTimeout($connectionTimeout = 10);
//  getContentType
//  setContentType           - $smsClient->setContentType($seconds);
//  getHttpClientType
//  setHttpClientType        - $smsClient->setHttpClientType($httpClientType);
//  getLastHttpTransfer
//  getUrl
//  getUsername
//  setVerbose               - $smsClient->setVerbose($bool);
//  getVersion
//
//  Message Methods list
//  --------------------
// getRecipientAddressList
// setRecipientAddressList    - $message->setRecipientAddressList($array)
// getSenderAddress
// setSenderAddress           - $message->setSenderAddress($string)
// getSenderAddressType
// setSenderAddressType       - $message->setSenderAddressType($string)
// getSendAsFlashSms
// setSendAsFlashSms          - $message->setSendAsFlashSms($bool)
// getNotificationCallbackUrl
// setNotificationCallbackUrl - $message->setNotificationCallbackUrl($string)
// getClientMessageId
// setClientMessageId         - $message->setClientMessageId($string)
// getPriority
// setPriority                - $message->setPriority($int)
// getMessageContent
// setMessageContent          - $message->setMessageContent($string)
// setMessageContent          - $message->setMessageContent($array)
// getUserDataHeaderPresent
// setUserDataHeaderPresent   - $message->setMessageContent($bool)
//
//  Exceptions (@thrown by WebSmsCom_Client):
//  --------------------
//  WebSmsCom_AuthorizationFailedException
//  WebSmsCom_ApiException
//  WebSmsCom_HttpConnectionException
//  WebSmsCom_UnknownResponseException
//  Exception
//
//  Exceptions (@thrown by WebSmsCom_Message):
//  --------------------
//  Exception
//
//====================================================================================
// Class WebSmsCom_Methods Descriptions:
//====================================================================================
//
//------------------------------------------------------------------------------------
// getConnectionTimeout()
//
//     - returns timeout in seconds 
//
//------------------------------------------------------------------------------------
// setConnectionTimeout($seconds)
//
//     - sets timeout in seconds for 'curl' and 'fsockopen' (HttpClientType) connections
//
//------------------------------------------------------------------------------------
// getContentType()
//
//     - returns content-type (by default it's "application/json;charset=UTF-8")
//
//------------------------------------------------------------------------------------
// setContentType($string)
//
//     - possibility to change content-type
//
//------------------------------------------------------------------------------------
// getHttpClientType()
//
//     - returns used http client type (php connection method 'curl' or 'fopen')
//
//------------------------------------------------------------------------------------
// setHttpClientType()
//
//     - set http connection method 'curl' or 'fopen'. default is 'curl'
//
//------------------------------------------------------------------------------------
// getLastHttpTransfer()
//
//     - returns last HTTP Request and HTTP Response as string.
//
//------------------------------------------------------------------------------------
// getUrl()
//
//     - returns url that was set at WebSmsCom_Client creation
//
//------------------------------------------------------------------------------------
// getUsername()
//
//     - returns username set at WebSmsCom_Client creation
//
//------------------------------------------------------------------------------------
// setVerbose($boolean)
//
//     - set verbose to see more information about request (echos)
//
//------------------------------------------------------------------------------------
// getVersion($boolean)
//
//     - get version of WebSmsCom_Client class
//
//------------------------------------------------------------------------------------
//
//
//====================================================================================
// Class WebSmsCom_Message Methods Descriptions:
//   (WebSmsCom_TextMessage and WebSmsCom_BinaryMessage
//
//====================================================================================
//
//  getRecipientAddressList()
//   - returns array of set recipients
//
//----------------------------------------------------------------
//
// setRecipientAddressList($array)
//   - set array of recipients 
//     (array of strings containing full international MSISDNs)
//
//----------------------------------------------------------------
//
//  getSenderAddress
//    - returns set senderAddress
//----------------------------------------------------------------
//
//  setSenderAddress(string senderAdress)
//    - set string of sender address msisdn or alphanumeric
//      sender address is dependend on user account
//
//----------------------------------------------------------------
//
//  getSenderAddressType
//    - returns set sender address type
//
//----------------------------------------------------------------
//
//  setSenderAddressType(string senderAdressType)
//   - depending on account settings this can be set to
//     'national', 'international', 'alphanumeric' or 'shortcode'
//
//----------------------------------------------------------------
//  getSendAsFlashSms
//    - returns set SendAsFlashSms flag
//
//----------------------------------------------------------------
//
//  setSendAsFlashSms($boolean)
//    - set send as flash sms flag true or false
//    (SMS is not saved on SIM, but shown directly on screen)
//
//----------------------------------------------------------------
//
//  getNotificationCallbackUrl
//    - returns set notification callback url
//
//----------------------------------------------------------------
//
//  setNotificationCallbackUrl(string $url)
//    - set string og notification callback url
//    customers url that listens for delivery report notifications
//    or replies for this message
//
//----------------------------------------------------------------
//
//  getClientMessageId
//    - returns set clientMessageId
//
//----------------------------------------------------------------
//
//  setClientMessageId($string)
//    - set message id for this message, returned with response
//      and used for notifications
//
//----------------------------------------------------------------
//  getPriority
//    - returns set message priority
//
//----------------------------------------------------------------
//
//  setPriority(int $priority)
//    - sets message priority as integer (1 to 9)
//     (if supported by account settings)
//----------------------------------------------------------------
//
//
//====================================================================================
// Class WebSmsCom_TextMessage Methods Descriptions:
//====================================================================================
//----------------------------------------------------------------
//  getMessageContent()
//   - returns set messageContent
//
//----------------------------------------------------------------
//  setMessageContent(string $messageContent)
//   - set utf8 string message text
//----------------------------------------------------------------
//
//====================================================================================
// Class WebSmsCom_BinaryMessage Methods Descriptions:
//  constructor: $message->WebSmsCom_BinaryMessage
//====================================================================================
//----------------------------------------------------------------
//
//  getMessageContent()
//   - returns set messageContent segments (array)
//
//----------------------------------------------------------------
//
//  setMessageContent(array $messageContent)
//   - set binary message content (array of base64 encoded binary strings)
//
//----------------------------------------------------------------
//
//  getUserDataHeaderPresent()
//   - returns set UserDataHeaderPresent flag
//
//----------------------------------------------------------------
//
//  setUserDataHeaderPresent(boolean $userDataHeaderPresent)
//   - set boolean userDataHeaderPresent flag
//     When set to true, messageContent segments are expected
//     to contain a UserDataHeader
//
//----------------------------------------------------------------
//
//====================================================================================
// Class WebSmsCom_Response Methods Descriptions:
//  returned by successful smsClient->send() method
//====================================================================================
//----------------------------------------------------------------
//
//  getRawContent()
//   - returns raw content of response
//
//----------------------------------------------------------------
//
//  getStatusCode()
//   - returns received StatusCode of API Response
//
//----------------------------------------------------------------
//
//  getStatusMessage()
//   - returns received StatusMessage of API Response
//
//----------------------------------------------------------------
//
//  getTransferId()
//   - returns received TransferId of API Response for successfully sent Message
//
//----------------------------------------------------------------

?>
