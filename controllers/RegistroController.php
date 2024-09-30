<?php
	namespace controllers;

	class RegistroController extends Controller{
	
            public function __construct($view,$model){
                $this->checkAccess();
                parent::__construct($view,$model);

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
                    // Se o tipo de usuário for 'admin', permite o acesso
                    if ($_SESSION['tipo'] === 'admin') {
                        return;
                    }
                    // Se o tipo de usuário for 'solicitante', redireciona para a página específica
                    elseif ($_SESSION['tipo'] === 'solicitante') {
                        header('Location: /Produto/produtosolicitar');
                        exit;
                    }
                    // Qualquer outro tipo de usuário exibe uma mensagem de acesso negado
                    else {
                        die('Acesso restrito.');
                    }
                } else {
                    // Caso o tipo de usuário não esteja definido
                    die('Acesso restrito.');
                }
            }

        public function finalizar(){
            session_start();
            if(isset($_POST)){
                $this->processa_registro($_POST);
                $_SESSION['dados_form'] = $_POST;
                header('Location: ' . '/Solicitacao/finalizar');
                exit(); 


                

            }
        }



            public function index(){//pegar do banco os produtos e enviar para view registro
                try {
                    $registros = $this->model->reader();
                    $this->view->render('registro.php',['registro' => $registros]);
                } catch (\Exception $e) {
                    error_log("Erro ao carregar produtos: " . $e->getMessage());
                    die("Erro ao carregar produtos: " . $e->getMessage());
                }
            }


            public function processa_registro($postData) {
                // Verifica se o campo 'tipo_operacao' está presente no POST
                if (isset($postData['tipo_operacao'])) {
                    $tipoOperacao = $postData['tipo_operacao'];
                    $id = $postData['id'];
                    $quantidade = $postData['quantidade'];
                    $saldo_atual = $postData['saldo_atual'];

                    $destino = $postData['destino'];
                    $obs = $postData['obs'];
                    
                    // Dependendo do tipo de operação, realiza uma ação específica
                    switch ($tipoOperacao) {
                        case 'compra':
                            // Lógica para a operação de compra
                            $saldo_final = $saldo_atual + $quantidade;
                            $this->model->processa_registro($id,$saldo_final,$tipoOperacao,$destino,$obs,$quantidade);
                            header("Location: /");
                            break;
            
                        case 'retirada':
                            // Lógica para a operação de venda
                            $saldo_final = $saldo_atual - $quantidade;
                            $this->model->processa_registro($id,$saldo_final,$tipoOperacao,$destino,$obs,$quantidade);
                            header("Location: /");
                            break;
            
                        case 'devolucao':
                            // Lógica para a operação de devolução
                            $saldo_final = $saldo_atual + $quantidade;
                            $this->model->processa_registro($id,$saldo_final,$tipoOperacao,$destino,$obs,$quantidade);
                            header("Location: /");
                            break;
            
                        default:
                            // Se o tipo de operação não for reconhecido
                            echo "Operação inválida!";
                            break;
                    }
                } else {
                    // Se o campo 'tipo_operacao' não estiver presente
                    echo "Tipo de operação não especificado!";
                }


            }


   
        


    }


?>