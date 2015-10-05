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



	$ch = init_curl();
	// $a = urlencode("D & A PARAMENTOS LTDA - EPP");
	curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/getall?skip=0");
	// // curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/produtos/pesquisar?codigo=&numeroSerie=&nome=&genero=&categoria=false&marca=false&pageSize=100&skip=0");
	// // curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=".$a."&cpfcnpj=&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=28893364840&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pedidos/pesquisar?codigo=0&origem=&status=&categoria=&cliente=&pageSize=10&skip=0");
	// curl_setopt($ch,CURLOPT_POST, 1);

	// curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/salvar?codigo=0&origem=&status=&categoria=&cliente=&pageSize=10&skip=0");
	// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	if (($result = curl_exec($ch)) === FALSE) {
	die('cURL error: '.curl_error($ch)."<br />\n");
	} else {
	//echo "Success!<br />\n";
	}
	curl_close($ch);

	$aaa = json_decode($result);
	print_r($aaa);
	exit();

	// $retorno = array();
	// $nao_existe = array();
	// foreach($obj_clientes as $v_cliente){
	// 	// echo $v_cliente["cpf_cnpj"]
	// 	$cpf_cnpj = preg_replace('/\D/', '', $v_cliente["cpf_cnpj"]);
	// 	$retorno[$v_cliente["id_cliente"]] = getdata_SIGE("http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=".$cpf_cnpj."&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
	// 	if(empty($retorno[$v_cliente["id_cliente"]])){
	// 		$nao_existe[] = $v_cliente["id_cliente"];
	// 	}
	// 	if(count($retorno) > 2){
	// 		print_r($retorno);
	// 		print_r($nao_existe);
	// 		exit();
	// 	}
	// }

	

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
		$obj_insert_client["PessoaFisica"] = empty($obj_cliente[0]["cpf"]) ? "false" : "true";
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
		$obj_insert_client["Cliente"] = "true";
		$obj_insert_client["Tecnico"] = "false";
		$obj_insert_client["Vendedor"] = "false";
		$obj_insert_client["Transportadora"] = "false";
		$obj_insert_client["Fornecedor"] = "false";
		$obj_insert_client["Representada"] = "false";
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
	print_r($obj_post);
	cadastra_pessoa($obj_post);

	function cadastra_pessoa($obj_post){
		$query_string = http_build_query($obj_post);
		//echo $query_string;
		$ch = init_curl();
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pessoas/salvar");

		if (($result = curl_exec($ch)) === FALSE) {
			die('cURL error: '.curl_error($ch)."<br />\n");
		} else {
			echo "Success!<br />\n";
		}
		curl_close($ch);
		return json_decode($result);
	}

	function monta_pesquisa_pedido () {
		global $_DB;
		$obj_pedido = $_DB->select("view_pesquisa_pedidos", "*");

		$obj_pesquisa_pedido = array();
		$obj_pesquisa_pedido["Codigo"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["OrigemVenda"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Deposito"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["StatusSistema"] = $obj_pedido[0]["status"]; // StatusSistema e Status com o mesmo valor
		$obj_pesquisa_pedido["Status"] = $obj_pedido[0]["status"]; // StatusSistema e Status com o mesmo valor
		$obj_pesquisa_pedido["Categoria"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Validade"] = $obj_pedido[0]["data_entrega"]; // Data de entrega?
		$obj_pesquisa_pedido["Empresa"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Cliente"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["ClienteCNPJ"] = empty($obj_pedido[0]["cnpj"]) ? preg_replace("/[^0-9]/", "", $obj_pedido[0]["cpf"]) : preg_replace("/[^0-9]/", "", $obj_pedido[0]["cnpj"]);?
		$obj_pesquisa_pedido["Vendedor"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["PlanoDeConta"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["FormaPagamento"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["NumeroParcelas"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Transportadora"] = $obj_pedido[0]["tipo_entrega"]; // Sedex, retirar em loja
		$obj_pesquisa_pedido["DataEnvio"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Enviado"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["ValorFrete"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["FreteContaEmitente"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["ValorSeguro"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Descricao"] = $obj_pedido[0]["obs"]; 
		$obj_pesquisa_pedido["OutrasDespesas"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["ValorFinal"] = $obj_pedido[0]["preco"];
		$obj_pesquisa_pedido["Finalizado"] = $obj_pedido[0][""]; // Pedido finalizado não pode sofrer alterações
		$obj_pesquisa_pedido["Lancado"] = $obj_pedido[0][""]; // Pedido já lançado pelo financeiro
		$obj_pesquisa_pedido["Municipio"] = $obj_pedido[0]["cidade"];
		$obj_pesquisa_pedido["CodigoMunicipio"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Pais"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["CEP"] = $obj_pedido[0]["cep"];
		$obj_pesquisa_pedido["UF"] = $obj_pedido[0]["uf"];
		$obj_pesquisa_pedido["UFCodigo"] = $obj_pedido[0][""]; // ?
		$obj_pesquisa_pedido["Bairro"] = $obj_pedido[0]["bairro"];
		$obj_pesquisa_pedido["Logradouro"] = $obj_pedido[0]["endereco"];
		$obj_pesquisa_pedido["LogradouroNumero"] = $obj_pedido[0]["end_num"];
		$obj_pesquisa_pedido["LogradouroComplemento"] = $obj_pedido[0]["complemento"];
		$obj_pesquisa_pedido["Items"] = // ?
		// "Items":[ {"Codigo":"41", "Unidade":"", "Descricao":"SERVIÇOS GERAIS", "Quantidade":1.0, "ValorUnitario":10.0, "DescontoUnitario":0.0, "ValorTotal":10.0},{"Codigo":"12", "Unidade":"", 
		// "Descricao":"WEB CAN", "Quantidade":15.0, "ValorUnitario":101.51, "DescontoUnitario":0.0, "ValorTotal":1522.65}]}

		return $obj_pesquisa_pedido;
	}


?>