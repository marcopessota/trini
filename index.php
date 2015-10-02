<?php

	$appKey = "7c85ca3c4bb3bb4ee89731382998762a84599fd2ccbb3299a5bae155fd9d4793fbc1662961afa8b70e683bd8daeeb7351e3f301354f2a0e9ab85d8a6b993d4d808b1a82d88ef1438a1cc6a9be4b417d88c1a5c60324dee81130a53f71808426d99c0b9bbd1c1d87931dcf8c3eb4b5b91ba05f5e799fb33b9cad6188cb59bc3a2";
	$user = "financeiro@deaparamentos.com.br";
	$app = "deaparamentos";

	$headers = array('Authorization-Token: '.$appKey, 
					'User: '.$user,
					'App: '.$app);

	$ch = curl_init();
	$a = urlencode("D & A PARAMENTOS LTDA - EPP");
	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/get?codigo=50271");
	curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/pesquisar?codigo=&numeroSerie=&nome=&genero=&categoria=false&marca=false&pageSize=100&skip=0");
	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=".$a."&cpfcnpj=&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	//curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pedidos/pesquisar?codigo=0&origem=&status=&categoria=&cliente=&pageSize=10&skip=0");

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	if (($result = curl_exec($ch)) === FALSE) {
	die('cURL error: '.curl_error($ch)."<br />\n");
	} else {
	//echo "Success!<br />\n";
	}
	curl_close($ch);

	$aaa = json_decode($result);
	print_r($aaa);
	//print_r(utf8_decode($aaa->Categoria));

	// // The URL to POST to
	// $url = "http://www.sigecloud.com.br/api/request/pedidos/pesquisar";

	// // Get the SOAP data into a string, I am using HEREDOC syntax
	// // but how you do this is irrelevant, the point is just get the
	// // body of the request into a string
	// $mySOAP["Authorization-Token"] = "7c85ca3c4bb3bb4ee89731382998762a84599fd2ccbb3299a5bae155fd9d4793fbc1662961afa8b70e683bd8daeeb7351e3f301354f2a0e9ab85d8a6b993d4d808b1a82d88ef1438a1cc6a9be4b417d88c1a5c60324dee81130a53f71808426d99c0b9bbd1c1d87931dcf8c3eb4b5b91ba05f5e799fb33b9cad6188cb59bc3a2";
	// $mySOAP["User"] = "financeiro@deaparamentos.com.br";
	// $mySOAP["App"] = "deaparamentos";

	// // The HTTP headers for the request (based on image above)
	// $headers = array('Content-Type: text/xml; charset=utf-8');

	// // Build the cURL session
	// $ch = curl_init();
	// curl_setopt($ch, CURLOPT_URL, $url);
	// curl_setopt($ch, CURLOPT_POST, TRUE);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $mySOAP);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	// // Send the request and check the response
	// if (($result = curl_exec($ch)) === FALSE) {
	// die('cURL error: '.curl_error($ch)."<br />\n");
	// } else {
	// echo "Success!<br />\n";
	// }
	// curl_close($ch);

	// // Handle the response from a successful request
	// print_r($result);

?>