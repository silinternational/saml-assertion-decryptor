# saml-assertion-decryptor

## Note: This is not a hacking tool.  
### It is only a troubleshooting tool for the owner of a SAML SP who needs to decrypt a SAML Assertion with his SP's private key.

It uses the SAML2 php library along with its dependencies.

* `> docker-compose up -d`
* copy private key named "saml.pem" into ./application/
* Browse to your local host
* Copy and paste saml assertion into the textarea and click on Submit
