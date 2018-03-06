
 
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
  
   * Version 1.0.3: Fixed setter setHttpClient and setSenderAddressType
   * Version 1.0.2: Fixed error where long message content could not be sent 
                    because some curl/php versions set "Expect:" HTTP Header.
   * Version 1.0.1: Fixed setter setMessageContent() 
   * Version 1.0.0: Basic text- and binary-sms-sending.

  Contact
  -------
  For any further questions into detail the contact-email is developer@websms.com
