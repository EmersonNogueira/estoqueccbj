<?php

namespace controllers;

class SolicitacaoController extends Controller
{
    public function __construct($view, $model)
    {
        parent::__construct($view, $model);
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

    public function index()
    {
        $this->checkAccess();
        try {
            $solicitacoes = $this->model->reader();
            $this->view->render('solicitacao.php', ['solicitacoes' => $solicitacoes]);
        } catch (\Exception $e) {
            error_log("Erro ao carregar produtos: " . $e->getMessage());
            die("Erro ao carregar produtos: " . $e->getMessage());
        }
    }

    public function solicitar()
    {
        
    
            // Certifique-se de que os dados do POST estão sendo utilizados corretamente
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $prod = $_POST;
                $this->view->render('solicitarProduto.php', ['prod' => $prod]);
    
    
            }
            else{
                header('Location: /Solicitacao/');
                exit();
            }
    
    }

    public function criarsolicitacao() {
        if (
            isset(
                $_POST['quantidade'], 
                $_POST['destino'], 
                $_POST['solicitante'], 
                $_POST['obs'], 
                $_POST['produto_id']
            )
        ) {
            $setor = $_POST['destino'];
            $solicitante = $_POST['solicitante'];
            $produto_id = $_POST['produto_id'];
            $quantidade_pedida = $_POST['quantidade'];
            $obs = $_POST['obs']; 
            $this->model->add($setor, $solicitante, $produto_id, $quantidade_pedida, $obs);
    
            // Definir mensagem de confirmação na sessão
            $_SESSION['mensagem_confirmacao'] = 'Sua solicitação foi criada com sucesso! Procure o setor de INFRA para retirar seu produto.';
    
            // Redirecionar para a página de produtos
            header('Location: /Produto/produtosolicitar');
            exit(); // Certifique-se de sair após o redirecionamento
        } else {
            echo "ERRO";
        }
    }
    

    public function finalizar() {
        if (isset($_SESSION['dados_form'])) {
            $dados = $_SESSION['dados_form'];
            if (isset($dados['id_sol'])) {
                try {
                    $this->model->updateStatus($dados['id_sol']);
                    unset($_SESSION['dados_form']);
                    // Redirecionar ou exibir uma mensagem de sucesso
                } catch (\Exception $e) {
                    error_log("Erro ao atualizar status: " . $e->getMessage());
                    echo "Erro ao atualizar status.";
                }
            } else {
                echo "ID da solicitação não fornecido.";
            }

            $_SESSION['mensagem_confirmacao'] = 'BAIXA NO PRODUTO FOI REALIZADA COM SUCESSO CONFIRA O NOVO SALDO';


            header('Location: /Produto/index');

        } else {
            echo "Dados do formulário não encontrados na sessão.";
        }
    }
    


}

?>
