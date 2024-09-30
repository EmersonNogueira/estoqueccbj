<?php
	namespace controllers;

	class ProdutoController extends Controller{
	
		//private $produtos; 
		public function __construct($view,$model){
			$this->checkAccess();
			parent::__construct($view,$model);
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}
			

		}



		private function checkAccess() {
			// Verifica se a sessão está iniciada
			if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
				// Usuário não está logado, redireciona para a página de login
				header('Location: /Login/login');
				exit;
			}
		
			// Verifica o tipo de usuário
			if (isset($_SESSION['tipo'])) {
				if ($_SESSION['tipo'] === 'admin') {
					// Se o usuário for admin, permite o acesso
					return;
				} elseif ($_SESSION['tipo'] === 'solicitante') {
					// Se o usuário for solicitante, permite o acesso à rota de solicitação de produto
					if ($_SERVER['REQUEST_URI'] === '/Produto/produtosolicitar') {
						return; // Permite o acesso à rota de solicitante
					} else {
						// Redireciona para a página de solicitação de produtos se tentar acessar outra rota
						header('Location: /Produto/produtosolicitar');
						exit;
					}
				} else {
					// Se não for admin nem solicitante, exibe uma mensagem de acesso negado
					die('Acesso negado.');
				}
			} else {

				// Caso o tipo de usuário não esteja definido, exibe uma mensagem de erro
				die('Tipo de usuário não definido.');
			}
		}






		public function index(){//pegar do banco os produtos e enviar para view produtos
			try {
				$produtos = $this->model->reader();
				$this->view->render('produto.php',['produtos' => $produtos]);
			} catch (\Exception $e) {
				error_log("Erro ao carregar produtos: " . $e->getMessage());
				die("Erro ao carregar produtos: " . $e->getMessage());
			}
		}

		public function produtosolicitar(){
			try {
				$produtos = $this->model->readerComSaldo();
				$this->view->render('produtoSolicitar.php',['produtos' => $produtos]);
			} catch (\Exception $e) {
				error_log("Erro ao carregar produtos: " . $e->getMessage());
				die("Erro ao carregar produtos: " . $e->getMessage());
			}
        }


		public function add(){ // chamar view de adicionar produto 
			$this->view->render('addproduto.php');
		}

		public function adicionarprod($postData){//enviar para o banco produto para cadastro
			$result = $this->model->cadastrar($postData);
			if ($result) {
				//echo "Produto cadastrado com sucesso!";
				$this->index(); // Chama o método index da instância atual
			} else {
				echo "Erro ao cadastrar o produto.";
			}
		}

		public function editarprod(){ //view de para edição
			
			$data = [
				'id' => $_GET['id'],
				'nome' => $_GET['nome'],
				'local' => $_GET['local'],
				'categoria' => $_GET['categoria'],
				'custo' => $_GET['custo']
			];
			
			$this->view->render('atualizar.php', ['produto' => $data]);		
		}

		public function atualizar($postData){
			$result = $this->model->atualizar($postData);
			if ($result) {
				//echo "Produto cadastrado com sucesso!";
				header("Location: /");
				exit();
			} else {
				echo "Erro ao atuallizar o produto.";
			}

		}

		public function registroprod(){
			
			$data = [
				'id' => $_GET['id'],
				'nome' => $_GET['nome'],
				'local' => $_GET['local'],
				'categoria' => $_GET['categoria'],
				'custo' => $_GET['custo'],
				'saldo_final' =>$_GET['saldo_final']
			];
			$this->view->render('registroprod.php');


		}


		



	}





?>