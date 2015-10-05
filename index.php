<?php

	include_once("classes/medoo.min.php");




	$_DB = new medoo(array(
	'database_type' => 'mysql',
	'database_name' => 'trina',
	'server' => '127.0.0.1',
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
	));

	$obj_clientes = $_DB->select("v_cpf_cnpj", "*");

	// print_r($clientes);



	// $ch = curl_init();
	// $a = urlencode("D & A PARAMENTOS LTDA - EPP");
	// // curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/get?codigo=50271");
	// // curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/pesquisar?codigo=&numeroSerie=&nome=&genero=&categoria=false&marca=false&pageSize=100&skip=0");
	// // curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=".$a."&cpfcnpj=&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=28893364840&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// // curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pedidos/pesquisar?codigo=0&origem=&status=&categoria=&cliente=&pageSize=10&skip=0");

	// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	// if (($result = curl_exec($ch)) === FALSE) {
	// die('cURL error: '.curl_error($ch)."<br />\n");
	// } else {
	// //echo "Success!<br />\n";
	// }
	// curl_close($ch);

	// $aaa = json_decode($result);
	// print_r($aaa);

	// $retorno = array();
	// $nao_existe = array();
	// foreach($obj_clientes as $v_cliente){
	// 	// echo $v_cliente["cpf_cnpj"]
	// 	$cpf_cnpj = preg_replace('/\D/', '', $v_cliente["cpf_cnpj"]);
	// 	$retorno[$v_cliente["id_cliente"]] = getdata_SIGE("http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=".$cpf_cnpj."&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// 	if(empty($retorno[$v_cliente["id_cliente"]])){
	// 		$nao_existe[] = $v_cliente["id_cliente"];
	// 	}
	// 	// if(count($retorno) > 30){
	// 	// 	print_r($retorno);
	// 	// 	print_r($nao_existe);
	// 	// 	exit();
	// 	// }
	// }

	


		//foreach($nao_existe as $v_id_cliente){
		// $obj_cliente = $_DB->select("tb_clientes", "*", array("id_cliente" => 10));
		// //print_r($obj_cliente);
		// $obj_insert_client = array();
		// $obj_insert_client["PessoaFisica"] = empty($obj_cliente[0]["cpf"]) ? 0 : 1;
		// $obj_insert_client["NomeFantasia"] = $obj_cliente[0]["nome"];
		// $obj_insert_client["RazaoSocial"] = empty($obj_cliente[0]["cpf"]) ? $obj_cliente[0]["instituicao"] : "";
		// $obj_insert_client["CNPJ_CPF"] = empty($obj_cliente[0]["cpf"]) ? $obj_cliente[0]["cnpj"] : $obj_cliente[0]["cpf"];
		// $obj_insert_client["RG"] = "";
		// $obj_insert_client["IE"] = $obj_cliente[0]["insc_est"];
		// $obj_insert_client["Logradouro"] = $obj_cliente[0]["endereco"];
		// $obj_insert_client["LogradouroNumero"] = $obj_cliente[0]["end_num"];
		// $obj_insert_client["Complemento"] = $obj_cliente[0]["complemento"];
		// $obj_insert_client["Bairro"] = $obj_cliente[0]["bairro"];
		// $obj_insert_client["Cidade"] = $obj_cliente[0]["cidade"];
		// $obj_insert_client["CodigoMunicipio"] = "";
		// $obj_insert_client["Pais"] = $obj_cliente[0]["insc_est"];
		// $obj_insert_client["CodigoPais"] = $obj_cliente[0]["insc_est"];
		// $obj_insert_client["CEP"] = $obj_cliente[0]["cep"];
		// $obj_insert_client["UF"] = $obj_cliente[0]["uf"];
		// $obj_insert_client["CodigoUF"] = "";

		// if(empty($obj_cliente[0]["tel1"]) && empty($obj_cliente[0]["tel2"])){
		// 	$tel = $obj_cliente[0]["cel"];
		// }

		// if(empty($obj_cliente[0]["tel2"]) && empty($obj_cliente[0]["cel"])){
		// 	$tel = $obj_cliente[0]["tel1"];
		// }

		// if(empty($obj_cliente[0]["tel1"]) && empty($obj_cliente[0]["cel"])){
		// 	$tel = $obj_cliente[0]["tel2"];
		// }

		// $obj_insert_client["Telefone"] = $tel;
		// $obj_insert_client["Celular"] = "";
		// $obj_insert_client["Email"] = $obj_cliente[0]["email"];
		// $obj_insert_client["Cliente"] = true;
		// $obj_insert_client["Tecnico"] = false;
		// $obj_insert_client["Vendedor"] = false;
		// $obj_insert_client["Transportadora"] = false;
		// $obj_insert_client["Fornecedor"] = false;
		// $obj_insert_client["Representada"] = false;
		// $obj_insert_client["Ramo"] = "";
		// $obj_insert_client["VendedorPadrao"] = "";
		// $obj_insert_client["NomePai"] = "";
		// $obj_insert_client["NomeMae"] = "";
		// $obj_insert_client["Naturalidade"] = "";
		// $obj_insert_client["ValorMinimoCompra"] = 0;
		// $obj_insert_client["DataNascimento"] = "";
		// print_r($obj_insert_client);
		// exit();
		//}




	function init_curl(){
		$appKey = "7c85ca3c4bb3bb4ee89731382998762a84599fd2ccbb3299a5bae155fd9d4793fbc1662961afa8b70e683bd8daeeb7351e3f301354f2a0e9ab85d8a6b993d4d808b1a82d88ef1438a1cc6a9be4b417d88c1a5c60324dee81130a53f71808426d99c0b9bbd1c1d87931dcf8c3eb4b5b91ba05f5e799fb33b9cad6188cb59bc3a2";
		$user = "financeiro@deaparamentos.com.br";
		$app = "deaparamentos";

		$headers = array('Authorization-Token: '.$appKey, 
						'User: '.$user,
						'App: '.$app);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		return $ch;
	}

	function getdata_SIGE($url){
		$ch = init_curl();
		curl_setopt($ch, CURLOPT_URL, $url);

		if (($result = curl_exec($ch)) === FALSE) {
			die('cURL error: '.curl_error($ch)."<br />\n");
		} else {
			//echo "Success!<br />\n";
		}
		curl_close($ch);
		return json_decode($result);
	}

	function monta_obj_pessoa($id_pessoa){
		global $_DB;
		$obj_cliente = $_DB->select("tb_clientes", "*", array("id_cliente" => $id_pessoa));
		//print_r($obj_cliente);
		$obj_insert_client = array();
		$obj_insert_client["PessoaFisica"] = empty($obj_cliente[0]["cpf"]) ? 0 : 1;
		$obj_insert_client["NomeFantasia"] = $obj_cliente[0]["nome"];
		$obj_insert_client["RazaoSocial"] = empty($obj_cliente[0]["cpf"]) ? $obj_cliente[0]["instituicao"] : "";
		$obj_insert_client["CNPJ_CPF"] = empty($obj_cliente[0]["cpf"]) ? preg_replace("/[^0-9]/", "", $obj_cliente[0]["cnpj"]) : preg_replace("/[^0-9]/", "", $obj_cliente[0]["cpf"]);
		$obj_insert_client["RG"] = "";
		$obj_insert_client["IE"] = $obj_cliente[0]["insc_est"];
		$obj_insert_client["Logradouro"] = $obj_cliente[0]["endereco"];
		$obj_insert_client["LogradouroNumero"] = $obj_cliente[0]["end_num"];
		$obj_insert_client["Complemento"] = $obj_cliente[0]["complemento"];
		$obj_insert_client["Bairro"] = $obj_cliente[0]["bairro"];
		$obj_insert_client["Cidade"] = $obj_cliente[0]["cidade"];
		$obj_insert_client["CodigoMunicipio"] = "";
		$obj_insert_client["Pais"] = $obj_cliente[0]["insc_est"];
		$obj_insert_client["CodigoPais"] = $obj_cliente[0]["insc_est"];
		$obj_insert_client["CEP"] = $obj_cliente[0]["cep"];
		$obj_insert_client["UF"] = $obj_cliente[0]["uf"];
		$obj_insert_client["CodigoUF"] = "";

		if(empty($obj_cliente[0]["tel1"]) && empty($obj_cliente[0]["tel2"])){
			$tel = $obj_cliente[0]["cel"];
		}

		if(empty($obj_cliente[0]["tel2"]) && empty($obj_cliente[0]["cel"])){
			$tel = $obj_cliente[0]["tel1"];
		}

		if(empty($obj_cliente[0]["tel1"]) && empty($obj_cliente[0]["cel"])){
			$tel = $obj_cliente[0]["tel2"];
		}

		$obj_insert_client["Telefone"] = $tel;
		$obj_insert_client["Celular"] = "";
		$obj_insert_client["Email"] = $obj_cliente[0]["email"];
		$obj_insert_client["Cliente"] = true;
		$obj_insert_client["Tecnico"] = false;
		$obj_insert_client["Vendedor"] = false;
		$obj_insert_client["Transportadora"] = false;
		$obj_insert_client["Fornecedor"] = false;
		$obj_insert_client["Representada"] = false;
		$obj_insert_client["Ramo"] = "";
		$obj_insert_client["VendedorPadrao"] = "";
		$obj_insert_client["NomePai"] = "";
		$obj_insert_client["NomeMae"] = "";
		$obj_insert_client["Naturalidade"] = "";
		$obj_insert_client["ValorMinimoCompra"] = 0;
		$obj_insert_client["DataNascimento"] = "";
		// print_r($obj_insert_client);
		return $obj_insert_client;
	}

	$obj_post = monta_obj_pessoa(10);
	// print_r($obj_post); exit;
	cadastra_pessoa($obj_post);

	function cadastra_pessoa($obj_post){
		echo http_build_query($obj_post);
		$ch = init_curl();
	}


?>