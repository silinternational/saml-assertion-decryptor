<?php

ini_set('display_errors', '1');

include "/data/vendor/autoload.php";

// include __DIR__ . '/../vendor/simplesamlphp/saml2/src/SAML2/Utils.php';

use XMLSecurityKey;
//use SAML2\Utils;
use SAML2_Utils;

$results = '<no results yet>';

if(isset($_POST['assertionXML'])) {    
    $xmlDataKey = '<xenc:EncryptedData';
    $xmlDataEnd = '</xenc:EncryptedData>';
    // $xmlDataKey = 'saml:EncryptedAssertion';
    
    $privateKey = __DIR__ . '/../saml.pem';
    
    $assertionXML = $_POST['assertionXML'];
    
    /*
     * SAML2 wants the Encrypted Data to appear as a child of the outer xml element.
     * So, extract it from the input and wrap it in a <list> element.
     */
    $startPos = strpos($assertionXML, $xmlDataKey);
    $endPos = strpos($assertionXML, $xmlDataEnd);
    $length = $endPos - $startPos + strlen($xmlDataEnd);
    
    $encryptedData = substr($assertionXML, $startPos, $length);
    $newXML = '<list>' . $encryptedData . '</list>';
        
    $dom = new DOMDocument();
    $dom->loadXML($newXML);  
    
    $domAssertion = $dom->documentElement;
    
    $data = \SAML2_Utils::xpQuery($domAssertion, './xenc:EncryptedData')[0];
    // header('Content-Type: text/plain');
    // die("AAA " . var_export($data->ownerDocument->saveXML($data), true));
    
    $decryptKey = new XMLSecurityKey(
        XMLSecurityKey::RSA_OAEP_MGF1P,
        ['type' => 'private']
    );    
    
    $decryptKey->loadKey($privateKey, true, false);    
    $decryptedAssertion = \SAML2_Utils::decryptElement($data, $decryptKey);  
    
    // Convert output to a string.
    $results = $decryptedAssertion->ownerDocument->saveXML($decryptedAssertion);
}

?>

<html>

 <body style="background-color: #FEB">
  <h1> Decrypt a SAML Assertion</h1>

  <form action="." method="POST">
    Insert the encrypted SAML assertion:   (Must include a &lt;xenc:EncryptedData&gt; tag)
    <br>
    <textarea name="assertionXML" rows="10" cols="100"></textarea>
    <br>
    <input type="submit" value="Submit">
  </form>

  <?php
    if ($results) {
      echo '<h4>Results</h4>';
      echo '<textarea name="results" rows="20" cols="100">' . $results . '</textarea>';
    }
  ?>
 </body>
</html>