
 
                  W E B S M S . C O M   P H P  T O O L K I T 


  What is it?
  -----------

  A lightweight PHP-client-library for using websms.com SMS services.
  Reduces the complexity of network-communication between client and SMS gateway, 
  to help business-customer save time and money for focusing on their business logic.
  
  Installation
  -------------------------

  Include  [WebSmsCom_Toolkit.inc](WebSmsCom_Toolkit.inc) into your PHP File in order to access the classes and methods for sending text and binary SMS.
  
       // will need JSON.phps (Service_JSON) for PHP<5.2.0
       include "WebSmsCom_Toolkit.inc";`
  
  For PHP versions lower than 5.2.0 please make sure that JSON.phps is in the same directory as WebSmsCom_Toolkit.inc or install Services_JSON from PEAR.
  
  __*or install with Composer*__
  
       composer require websms-com/websmscom-php
  
  
  Example
  -------
  
  See [send_sms.php](send_sms.php) on how to send messages
  
  1. Create sms client (once)
  
     `$smsClient = new WebSmsCom_Client($username, $pass, $gateway_url);`
  2. Create message
  
     `$message  = new WebSmsCom_TextMessage($recipientAddressList, $utf8_message_text);`
  3. Send message
  
     `$Response = $smsClient->send($message, $maxSmsPerMessage, $test);`
    

  Documentation
  -------------
  The documentation available as of the date of this release is included 
  in [send_sms.php](send_sms.php) and [WebSmsCom_Toolkit.inc](WebSmsCom_Toolkit.inc).

  FAQ
  -------------
  __*Question:* Why do I get a CURLOPT_SSL_VERIFYHOST error?__
  
     `curl_setopt(): CURLOPT_SSL_VERIFYHOST no longer accepts the value 1, value 2 will be used instead` 
     
  __*Answer:* Just set $smsClient->setSslVerifyHost(2)__
  
       // 1.) -- create sms client (once) ------
       $smsClient = new WebSmsCom_Client($username, $pass, $gateway_url);
       $smsClient->setSslVerifyHost(2);

  Changelog
  ------------------
  
   * Version 1.0.10: Added SmsCount property to WebSmsCom_Response (thanks to AlexHoebart-ICPDR)
   * Version 1.0.9: Tested functionality with PHP 8.4.3; Fixes missing class property causing deprecation note on dynamic property.
   * Version 1.0.8: Tested functionality with PHP 8.0.5, removes deprecated and unnecessary defaultValue for $pass argument in WebSmsCom_Client constructor
   * Version 1.0.7: Restored compatibility to PHP 5.0.3+ (removes PHP7 style scalar type hinting)
   * Version 1.0.6: Access token support
   * Version 1.0.5: Composer / Packagist support
   * Version 1.0.4: Throws WebSmsCom_ParameterValidationException on json_encode() errors to prevent body being 'null' or 'false'. Before this change an empty content body resulted in an API exception with status code 4120 
   * Version 1.0.3: Fixed setter setHttpClient and setSenderAddressType
   * Version 1.0.2: Fixed error where long message content could not be sent 
                    because some curl/php versions set "Expect:" HTTP Header.
   * Version 1.0.1: Fixed setter setMessageContent() 
   * Version 1.0.0: Basic text- and binary-sms-sending.

  Contact
  -------
  For any further questions into detail the contact-email is developer@websms.com
