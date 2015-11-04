<?php
	//ini_set('default_charset', 'utf-8');
	set_time_limit(0);
	// include_once("classes/medoo.min.php");

	$tabelas_pedido = array(array("tabela" => "tb_pedidos", "id" => "id_pedido", "loja" => "Loja São Paulo", "uf" => "Loja SP"),
							array("tabela" => "tb_pedidosbh", "id" => "id_pedidobh", "loja" => "Loja BH", "uf" => "Loja BH"),
							array("tabela" => "tb_pedidosrj", "id" => "id_pedidorj", "loja" => "Rio de Janeiro", "uf" => "Loja Rio"),
							array("tabela" => "tb_pedidosw", "id" => "id_pedidow", "loja" => "D&A PARAMENTOS LTDA", "uf" => ""),
							array("tabela" => "tb_pedidosdf", "id" => "id_pedidodf", "loja" => "Brasília", "uf" => "Loja DF")
							);

	// $tabelas_pedido = array(array("tabela" => "tb_pedidosrj", "id" => "id_pedidorj", "loja" => "Rio de Janeiro", "uf" => "Loja Rio"));

	$_DB = cria_conexao();

	// $qry = "SELECT
	// 			ped.*,
	// 			cli.nome,
	// 			cli.cpf,
	// 			cli.cnpj,
	// 			cli.cidade,
	// 			cli.cep,
	// 			cli.uf,
	// 			cli.bairro,
	// 			cli.endereco,
	// 			cli.end_num,
	// 			cli.complemento
	// 			FROM tb_pedidosrj ped
	// 			LEFT JOIN tb_clientes cli ON cli.id_cliente = ped.id_cliente
	// 			WHERE
	// 			(status = 'Pendente' OR status = 'Pronto')
	// 	 		AND
	// 	 		(enviado_para_sige is null OR enviado_para_sige <> 'true')
	// 	 		AND
	// 	 		DATE_FORMAT(data_entrega, '%m%Y') = DATE_FORMAT(CURRENT_DATE, '%m%Y')
	// 	 		AND
	// 			id_pedidorj = 'RJ810'
	// 			GROUP BY ped.id_pedidorj order by id_cliente";
	// 			// echo $qry.PHP_EOL;
	// 	$aaa = query($qry);
	// 	print_r($aaa);
	// 	exit();

	function cria_conexao(){
		$_DB = mysql_connect('localhost', '', '');
		if (!$_DB) {
		    die('Could not connect: ' . mysql_error());
		}
		$db_selected = mysql_select_db('deapaine_deapainel1', $_DB);
		if (!$db_selected) {
		    die ('Can\'t use foo : ' . mysql_error());
		}

		return $_DB;
	}

	function fecha_conexao(){
		global $_DB;
		mysql_close($_DB);
	}


	function query($sql){
		global $_DB;
		$result = mysql_query($sql, $_DB);
		while ($row = mysql_fetch_array($result)) {
		    $obj_return[] = $row;
		}
		return $obj_return;
	}

	function grava($sql){
		global $_DB;
		$result = mysql_query($sql, $_DB);
		return $result;
	}

	// $sql = 'SELECT * FROM v_cpf_cnpj';
	// $obj_clientes = query($sql);

	// $_DB = new medoo(array(
	// 'database_type' => 'mysql',
	// 'database_name' => 'trina',
	// 'server' => '127.0.0.1',
	// 'username' => 'root',
	// 'password' => '',
	// 'charset' => 'utf8',
	// ));
	// $obj_clientes = $_DB->select("tb_produtossige", "*");

	// print_r($obj_clientes);
	// exit();

	if($_GET["a"] == "produtos"){
		baixa_pedidosSIGE(0);
	}
	if($_GET["a"] == "pedidos"){
		foreach($tabelas_pedido as $v_tabela){
			monta_pesquisa_pedido($v_tabela["tabela"], $v_tabela["id"], $v_tabela["loja"], $v_tabela["uf"]);
		}
	}







	//  SYNC DE PRODUTOS SIGE
	function baixa_pedidosSIGE($skip){
		global $_DB;
		$todos_produtos = getdata_SIGE("http://api.sigecloud.com.br/request/produtos/getall?skip=".$skip);
		foreach ($todos_produtos as $item) {
			if($item->Categoria == "PARAMENTOS"){
				$produtos_paramentos["descricao"] = $item->Nome;
				$produtos_paramentos["id_sige"] = $item->Codigo;
				$sql = "SELECT * FROM tb_produtossige where id_sige = ".$item->Codigo;
				$result_qry = query($sql);
				// $result_qry = $_DB->select("tb_produtossige", "*", array("id_sige" => $item->Codigo));
				if(count($result_qry) == 0){
					$sql_insert = "INSERT INTO tb_produtossige values(null, ".$produtos_paramentos["id_sige"].", '".utf8_decode($produtos_paramentos["descricao"])."' )";
					// echo $sql_insert;exit;
					grava($sql_insert);
					echo "Produto " . utf8_decode($produtos_paramentos["descricao"]). "gravado com sucesso.<br>";
					// $_DB->insert("tb_produtossige", $produtos_paramentos);
				}
			}
		}
		if(count($todos_produtos) == 100){
			baixa_pedidosSIGE($skip+100);
		}
		return;
	}


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


	//  PESSOAS

	function monta_obj_pessoa($id_pessoa){
		global $_DB;
		// $obj_cliente = $_DB->select("tb_clientes", "*", array("id_cliente" => $id_pessoa));
		$sql = "SELECT * FROM tb_clientes where id_cliente = ".$id_pessoa;
		$obj_cliente = query($sql);
		//print_r($obj_cliente);
		$obj_insert_client = array();
		$obj_insert_client["PessoaFisica"] = empty($obj_cliente[0]["cpf"]) ? "false" : "true";
		$obj_insert_client["NomeFantasia"] = utf8_encode($obj_cliente[0]["nome"]);
		$obj_insert_client["RazaoSocial"] = empty($obj_cliente[0]["cpf"]) ? $obj_cliente[0]["instituicao"] : "";
		$obj_insert_client["CNPJ_CPF"] = empty($obj_cliente[0]["cpf"]) ? preg_replace("/[^0-9]/", "", $obj_cliente[0]["cnpj"]) : preg_replace("/[^0-9]/", "", $obj_cliente[0]["cpf"]);
		$obj_insert_client["RG"] = "";
		$obj_insert_client["IE"] = $obj_cliente[0]["insc_est"];
		$obj_insert_client["Logradouro"] = utf8_encode($obj_cliente[0]["endereco"]);
		$obj_insert_client["LogradouroNumero"] = $obj_cliente[0]["end_num"];
		$obj_insert_client["Complemento"] = $obj_cliente[0]["complemento"];
		$obj_insert_client["Bairro"] = utf8_encode($obj_cliente[0]["bairro"]);
		$obj_insert_client["Cidade"] = utf8_encode($obj_cliente[0]["cidade"]);
		$obj_insert_client["CodigoMunicipio"] = "";
		$obj_insert_client["Pais"] = $obj_cliente[0]["insc_est"];
		$obj_insert_client["CodigoPais"] = $obj_cliente[0]["insc_est"];
		$obj_insert_client["CEP"] = $obj_cliente[0]["cep"];
		$obj_insert_client["UF"] = $obj_cliente[0]["uf"];
		$obj_insert_client["CodigoUF"] = "";


		if(empty($obj_cliente[0]["tel2"])){
			$tel = $obj_cliente[0]["tel1"];
		}

		if(empty($obj_cliente[0]["cel"])){
			$tel = $obj_cliente[0]["tel2"];
		}

		$obj_insert_client["Telefone"] = str_replace("-","",str_replace(")","",str_replace("(", "", $tel)));
		$obj_insert_client["Celular"] = str_replace("-","",str_replace(")","",str_replace("(", "", $obj_cliente[0]["cel"])));
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

	//  PEDIDOS


	function monta_pesquisa_pedido($tabela, $id, $cidade, $uf){
		global $_DB;
		global $tabelas_pedido;

		$qry = "SELECT
				ped.*,
				cli.nome,
				cli.cpf,
				cli.cnpj,
				cli.cidade,
				cli.cep,
				cli.uf,
				cli.bairro,
				cli.endereco,
				cli.end_num,
				cli.complemento
				FROM ".$tabela." ped
				LEFT JOIN tb_clientes cli ON cli.id_cliente = ped.id_cliente
				WHERE
				(status = 'Pendente' OR status = 'Pronto')
				AND
				(enviado_para_sige is null OR enviado_para_sige <> 'true')
				AND
				DATE_FORMAT(data_entrega, '%m%Y') = DATE_FORMAT(CURRENT_DATE, '%m%Y')
				GROUP BY ped.".$id. " order by id_cliente";



		// $qry = "SELECT
		// 		ped.*,
		// 		cli.nome,
		// 		cli.cpf,
		// 		cli.cnpj,
		// 		cli.cidade,
		// 		cli.cep,
		// 		cli.uf,
		// 		cli.bairro,
		// 		cli.endereco,
		// 		cli.end_num,
		// 		cli.complemento
		// 		FROM tb_pedidosrj ped
		// 		LEFT JOIN tb_clientes cli ON cli.id_cliente = ped.id_cliente
		// 		WHERE
		// 		(status = 'Pendente' OR status = 'Pronto')
		//  		AND
		//  		(enviado_para_sige is null OR enviado_para_sige <> 'true')
		//  		AND
		//  		DATE_FORMAT(data_entrega, '%m%Y') = DATE_FORMAT(CURRENT_DATE, '%m%Y')
		//  		AND
		// 		ped.id_cliente = '2620'";

		// 		echo $qry.PHP_EOL;
		$obj_pedido = query($qry);
		// print_r($obj_pedido);exit;
		$id_cliente = 0;
		$pedido_validado = true;

		foreach($obj_pedido as $k_linha => $linha){
			//echo $linha["id_cliente"].PHP_EOL;
			
				if(empty($linha["idComp"])){
					echo "id (". $linha[$id] . ") da tabela " .$tabela. utf8_decode(" está sem id_produto_SIGE <br>").PHP_EOL;
					$id_sige = "";
					$descricao = "";
				}else{
					$qry2 = "SELECT  * FROM tb_produtossige  WHERE id_sige = ".$linha["idComp"] ;
					// echo $qry.PHP_EOL;
					$produto_result = query($qry2);

					$produto_result = $produto_result[0];
					$id_sige = $produto_result["id_sige"];
					$descricao = utf8_encode($produto_result["descricao"]);

				}
				// echo $pedido_validado.PHP_EOL;
				if($linha["id_cliente"] != $id_cliente){

					if(!empty($obj_pesquisa_pedido)){
						if($pedido_validado == true){
							cadastra_pedido($obj_pesquisa_pedido);
							foreach($id_pedidos as $v_id_pedido){
								$qry_update = ' UPDATE '.$tabela.' set enviado_para_sige = "true" WHERE '.$id.' = "'.  $v_id_pedido . '"';
								// echo $qry_update;
								grava($qry_update);
								// echo $qry_update."<br>".PHP_EOL;
							}
						}
						$pedido_validado = true;
					}

					$id_cliente = $linha["id_cliente"];
					$id_pedidos = array();

					// PROCESSO DE CRUD DE CLIENTE DO PAINEL PARA O SIGE

					$cpf = preg_replace("/[^0-9]/", "", $linha["cpf"]);
					$cnpj = preg_replace("/[^0-9]/", "", $linha["cnpj"]);

					$cliente_existe_cpf = getdata_SIGE("http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=".$cpf."&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");
					$cliente_existe_cnpj = getdata_SIGE("http://api.sigecloud.com.br/request/pessoas/pesquisar?nomefantasia=&cpfcnpj=".$cnpj."&cidade=&uf=&cliente=false&fornecedor=false&pageSize=10&skip=0");				

					if(empty($cliente_existe_cpf) && empty($cliente_existe_cnpj)){
						$obj_cli = monta_obj_pessoa($id_cliente);

						cadastra_pessoa($obj_cli);
						echo "Cliente cadastrado no SIGE- ".  $linha["nome"]."<br>".PHP_EOL;
					}


					// PEDIDOS

					$obj_pesquisa_pedido = array();
					$obj_pesquisa_pedido["Codigo"] = "";
					$obj_pesquisa_pedido["OrigemVenda"] = "Venda Direta";
					$obj_pesquisa_pedido["Deposito"] = ("Depósito ".$uf);
					$obj_pesquisa_pedido["StatusSistema"] = "Pedido";


					if($tabela == "tb_pedidosw"){
						$status = "Sedex";
					// }elseif($tabela == "tb_pedidos"){
					}else{
						$status = "Retirar loja ".$loja;
					}

					$obj_pesquisa_pedido["Status"] = $status;
					$obj_pesquisa_pedido["Categoria"] = "";
					// $obj_pesquisa_pedido["Validade"] = $linha["data_entrega"]."T00:00:00-02:00";
					$obj_pesquisa_pedido["Validade"] = "0001-01-01T00:00:00-02:00";

					if(($linha["producao"] == "Fábrica" && $linha["tipo_entrega"] == "Sedex") || $tabela == "tb_pedidosw"){
						$empresa = "D&A PARAMENTOS LTDA";
					}else{
						$empresa = ("D&A Decorações e Artesanato Litúrgico Ltda - ".$cidade);
					}

					// if($linha["producao"] == "Atelier" && $linha["tipo_entrega"] == "Sedex" && $tabela != "tb_pedidosw"){
					// 	$empresa = "D&A Decorações e Artesanato Litúrgico Ltda - Loja ".$cidade;
					// }

					// if($linha["producao"] == "Atelier" && $linha["tipo_entrega"] == "Retirar Loja" && $tabela != "tb_pedidosw"){
					// 	$empresa =  "D&A Decorações e Artesanato Litúrgico Ltda - Loja ".$cidade;
					// }
					$obj_pesquisa_pedido["Empresa"] = $empresa;
					$obj_pesquisa_pedido["Cliente"] = $linha["nome"];
					$obj_pesquisa_pedido["ClienteCNPJ"] = empty($linha["cnpj"]) ? preg_replace("/[^0-9]/", "", $linha["cpf"]) : preg_replace("/[^0-9]/", "", $linha["cnpj"]);
					$obj_pesquisa_pedido["Vendedor"] = "";

					if($tabela == "tb_pedidosw" || $tabela == "tb_pedidos"){
						$plano_conta = "VENDA DE MERCADORIAS";
					}else{
						$plano_conta = "REVENDA DE MERCADORIAS";
					}

					$obj_pesquisa_pedido["PlanoDeConta"] = $plano_conta;
					$obj_pesquisa_pedido["FormaPagamento"] = "";
					$obj_pesquisa_pedido["NumeroParcelas"] = 0;
					$obj_pesquisa_pedido["Transportadora"] = "";
					$obj_pesquisa_pedido["DataPostagem"] = "";
					$obj_pesquisa_pedido["DataEnvio"] = "0001-01-01T00:00:00-02:00";
					$obj_pesquisa_pedido["Enviado"] = "false";
					$obj_pesquisa_pedido["ValorFrete"] = 0.0;
					$obj_pesquisa_pedido["FreteContaEmitente"] = "false";
					$obj_pesquisa_pedido["ValorSeguro"] = 0.0;
					$obj_pesquisa_pedido["Descricao"] = ""; 
					$obj_pesquisa_pedido["OutrasDespesas"] = 0.0;
					$obj_pesquisa_pedido["TransacaoCartao"] = "";
					$obj_pesquisa_pedido["ValorFinal"] = 0.0;
					$obj_pesquisa_pedido["Finalizado"] = "false";
					$obj_pesquisa_pedido["Lancado"] = "false";
					$obj_pesquisa_pedido["Municipio"] = "";
					$obj_pesquisa_pedido["CodigoMunicipio"] = "";
					$obj_pesquisa_pedido["Pais"] = "";
					$obj_pesquisa_pedido["CEP"] = "";
					$obj_pesquisa_pedido["UF"] = "";
					$obj_pesquisa_pedido["UFCodigo"] = "";
					$obj_pesquisa_pedido["Bairro"] = "";
					$obj_pesquisa_pedido["Logradouro"] = "";
					$obj_pesquisa_pedido["LogradouroNumero"] = "";
					$obj_pesquisa_pedido["LogradouroComplemento"] = "";
					$obj_pesquisa_pedido["Items"] = array();
					$obj_produto["Codigo"] = $id_sige;
					$obj_produto["Unidade"] = "und";
					if(empty($id_sige) || empty($descricao)){
						// echo $linha["id_cliente"]." - ".$linha["id_pedido"] . " - " .$id_sige." - ". $descricao.PHP_EOL;
						$pedido_validado = false;
					}
					$obj_produto["Descricao"] = $descricao;
					$obj_produto["Quantidade"] = (double) $linha["quantidade"];
					$obj_produto["ValorUnitario"] = (double) $linha["preco"] / $linha["quantidade"];
					$obj_produto["ValorFrente"] = 0;
					$obj_produto["DescontoUnitario"] = 0;
					$obj_produto["Desconto Total"] = 0;
					$obj_produto["Comprimento"] = 0;
					$obj_produto["Altura"] = 0;
					$obj_produto["Largura"] = 0;
					$obj_produto["FreteGratis"] = "true";
					$obj_produto["ValorUnitarioFrete"] = 0;
					$obj_produto["PrazoEntregaFrete"] = 0;
					$id_pedidos[] = $linha[$id];
				}else{
					if(empty($id_sige) || empty($descricao)){
						// echo $linha["id_cliente"]." - ".$linha["id_pedido"] . " - " .$id_sige." - ". $descricao.PHP_EOL;
						$pedido_validado = false;
					}
					$obj_produto["Codigo"] = $id_sige;
					$obj_produto["Unidade"] = "und";
					$obj_produto["Descricao"] = $descricao;
					$obj_produto["Quantidade"] = (double) $linha["quantidade"];
					$obj_produto["ValorUnitario"] = (double) $linha["preco"] / $linha["quantidade"];
					$obj_produto["ValorFrente"] = 0;
					$obj_produto["DescontoUnitario"] = 0;
					$obj_produto["Desconto Total"] = 0;
					$obj_produto["Comprimento"] = 0;
					$obj_produto["Altura"] = 0;
					$obj_produto["Largura"] = 0;
					$obj_produto["FreteGratis"] = "true";
					$obj_produto["ValorUnitarioFrete"] = 0;
					$obj_produto["PrazoEntregaFrete"] = 0;
					$id_pedidos[] = $linha[$id];
				}
				$obj_pesquisa_pedido["Items"][] = $obj_produto;
					// if($linha["id_cliente"] == 2620 && $linha["idComp"] == 23827 ){
					// 	print_r($obj_pesquisa_pedido);exit;
					// }	
				if(empty($obj_pedido[$k_linha+1])){
					if(!empty($obj_pesquisa_pedido)){
						if($pedido_validado == true){
							$success = cadastra_pedido($obj_pesquisa_pedido);
							foreach($id_pedidos as $v_id_pedido){
								$qry_update = ' UPDATE '.$tabela.' set enviado_para_sige = "true" WHERE '.$id.' = '.  $v_id_pedido;
								grava($qry_update);
								// echo $qry_update."<br>".PHP_EOL;
								if($success == true){
									echo "Pedido (".$v_id_pedido.") enviado para o SIGE com sucesso<br>".PHP_EOL;
								}
							}
						}
						$pedido_validado = true;
					}
				}
			// }
		}
	}
	function cadastra_pedido($obj_post){
		$query_string = http_build_query($obj_post);
		$ch = init_curl();
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_URL, "http://api.sigecloud.com.br/request/pedidos/salvar");

		if (($result = curl_exec($ch)) === FALSE) {
			die('cURL error: '.curl_error($ch)."<br />\n");
		} else {
			if(stripos($result, "PEDIDO SALVO COM SUCESSO") === false){
				echo "#ERRO_PEDIDO :  " . utf8_decode($result);
				print_r($obj_post);
				$retorno = false;
			}else{
				echo "<b>".$result."</b> <br />\n";
				$retorno = true;
			}

		}
		curl_close($ch);
		return $retorno;
	}

?>