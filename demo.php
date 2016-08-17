<?php
$private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQC3//sR2tXw0wrC2DySx8vNGlqt3Y7ldU9+LBLI6e1KS5lfc5jl
TGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2klBd6h4wrbbHA2XE1sq21ykja/
Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o2n1vP1D+tD3amHsK7QIDAQAB
AoGBAKH14bMitESqD4PYwODWmy7rrrvyFPEnJJTECLjvKB7IkrVxVDkp1XiJnGKH
2h5syHQ5qslPSGYJ1M/XkDnGINwaLVHVD3BoKKgKg1bZn7ao5pXT+herqxaVwWs6
ga63yVSIC8jcODxiuvxJnUMQRLaqoF6aUb/2VWc2T5MDmxLhAkEA3pwGpvXgLiWL
3h7QLYZLrLrbFRuRN4CYl4UYaAKokkAvZly04Glle8ycgOc2DzL4eiL4l/+x/gaq
deJU/cHLRQJBANOZY0mEoVkwhU4bScSdnfM6usQowYBEwHYYh/OTv1a3SqcCE1f+
qbAclCqeNiHajCcDmgYJ53LfIgyv0wCS54kCQAXaPkaHclRkQlAdqUV5IWYyJ25f
oiq+Y8SgCCs73qixrU1YpJy9yKA/meG9smsl4Oh9IOIGI+zUygh9YdSmEq0CQQC2
4G3IP2G3lNDRdZIm5NZ7PfnmyRabxk/UgVUWdk47IwTZHFkdhxKfC8QepUhBsAHL
QjifGXY4eJKUBm3FpDGJAkAFwUxYssiJjvrHwnHFbg0rFkvvY63OSmnRxiL4X6EY
yI9lblCsyfpl25l7l5zmJrAHn45zAiOoBrWqpM5edu7c
-----END RSA PRIVATE KEY-----';

$public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o
2n1vP1D+tD3amHsK7QIDAQAB
-----END PUBLIC KEY-----';

//echo $private_key;
$pi_key =  openssl_pkey_get_private($private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
$pu_key = openssl_pkey_get_public($public_key);//这个函数可用来判断公钥是否是可用的
// print_r($pi_key);echo "<br>";
// print_r($pu_key);echo "<br>";


$data = "This is a test!";//原始数据
$encrypted = ""; 
$decrypted = ""; 

openssl_private_encrypt($data,$encrypted,$pi_key);//私钥加密
$encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
openssl_public_decrypt(base64_decode($encrypted),$decrypted,$pu_key);//私钥加密的内容通过公钥可用解密出来

openssl_public_encrypt($data,$encrypted2,$pu_key);//公钥加密
$encrypted2 = base64_encode($encrypted2);
openssl_private_decrypt(base64_decode($encrypted2),$decrypted2,$pi_key);//私钥解密
?>
<html>
<head>
  <title>JSEncrypt Example</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <script type="text/javascript" src="./jsencrypt.js"></script>
  <script type="text/javascript" src="./jquery.js"></script>
</head>
<body>
  <script type="text/javascript">
function do_encrypt() {
      var encrypt = new JSEncrypt();
      encrypt.setPublicKey($('#pubkey').val());
      var encrypted = encrypt.encrypt($('#input').val());

      // Decrypt with the private key...
      var decrypt = new JSEncrypt();
      decrypt.setPrivateKey($('#privkey').val());
      var uncrypted = decrypt.decrypt(encrypted);

	var $bstr1=decrypt.encrypt($('#input').val());
	var $bstr2=encrypt.decrypt($bstr1);

      // Now a simple check to see if the round-trip worked.
      if (uncrypted == $('#input').val()) {
		  $('#pubkeyencode').val(encrypted);
		  $('#privkeydecode').val(uncrypted);
      }
      else {
        alert('Something went wrong....');
      }
	var decryptPHP = decrypt.decrypt("<?php echo $encrypted2;?>");
	$('#jsdecode').val(decryptPHP);
}
</script>
<label for="privkey">Private Key</label><br/>
<textarea id="privkey" rows="15" cols="65"><?php echo $private_key; ?></textarea><br/>
<label for="pubkey">Public Key</label><br/>
<textarea id="pubkey" rows="8" cols="65"><?php echo $public_key; ?></textarea><br/>

<label for="input">Text to encrypt:</label><br/>
<textarea id="input" name="input" type="text" rows=4 cols=70><?php echo $data; ?></textarea><br/>

<?php
echo "source data:".$data."<br>";
echo "private key encrypt by PHP:"."<br>";
echo $encrypted."<br>";
echo "public key decrypt by PHP:"."<br>";
echo $decrypted."<br>";
echo "---------------------------------------"."<br>";
echo "public key encrypt by PHP:"."<br>";
echo $encrypted2."<br>";
echo "private key decrypt by PHP:"."<br>";
echo $decrypted2."<br>";

echo "private key decrypt by JS:"."<br>";
?>
<textarea id="jsdecode" rows="3" cols="65"></textarea><br/>

<input id="testme" type="button" onClick="do_encrypt();" value="Test Me!!!" /><br/>
<label for="pubkey">Public Key Encrypt</label><br/>
<form name="testphp" action="demo.php" method="get">
<textarea id="pubkeyencode" name="encode" rows="5" cols="65"></textarea><br/>
<label for="pubkey">Private Key Decrypt</label><br/>
<textarea id="privkeydecode" rows="5" cols="65"></textarea><br/>

	<input type="submit"value="Test PHP!!!" /><br/>
</form>
<label for="pubkey">Private Key Decrypt By PHP</label><br/>
<textarea id="privkeydecode" rows="3" cols="65">
<?php
if($_GET["encode"]!="")
	openssl_private_decrypt(base64_decode($_GET["encode"]),$str1,$pi_key);//私钥解密
echo $str1;
?>
</textarea><br/>
</body>
</html>
