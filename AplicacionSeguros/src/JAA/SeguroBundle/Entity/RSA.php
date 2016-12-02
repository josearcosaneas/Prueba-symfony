<?php

class myRSA
{

    public $pubkey = '';
    public $privkey = '';

    public function encrypt($data)
    {
		$fp = fopen("public.txt","r");
		for ($i =0; $i<=5;$i++){
			$pubkey .= fgets($fp);
		}
        fclose($fp);
		if (openssl_public_encrypt($data, $encrypted, $this->pubkey))
            $data = base64_encode($encrypted);
      
        return $data;
    }

    public function decrypt($data)
    {
		$fp2 = fopen("private.txt","r");
		for ($i =0; $i<=15; $i++){
			 $privkey .= fgets($fp2);
		}
		fclose($fp2);

        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey))
            $data = $decrypted;
        else
            $data = '';

        return $data;
    }
}


?>