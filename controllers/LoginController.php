<?php
    namespace controllers;

    class LoginController extends Controller{

        public function login(){
            $this->view->render('login.php');
        }


        public function criarlogin(){
            $this->view->render('criarlogin.php');
        }
        
        
        public function usuario($posdata){

         
           $this->model->criaruser($posdata);

            
        }

        public function logar($posdata) {
            $login = $this->model->verificarLogin($posdata['username'], $posdata['password'],$posdata['tipo']);
            
            if ($login) {
                // Definir a variável de sessão para o usuário autenticado
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $login['username']; // Opcional: guardar o nome do usuário
                $_SESSION['tipo'] = $login['tipo'];
                header('Location: /');

            } else {
                $_SESSION['mensagem_confirmacao'] = 'Usuário ou senha invalidos. Tente novamente';
                header('Location: /Login/login');
                exit;              
            }
        }


        public function logout() {
            // Iniciar a sessão
            session_start();
        
            // Limpar todas as variáveis de sessão
            $_SESSION = array();
        
            // Se o cookie de sessão existir, exclua-o
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, 
                    $params["path"], $params["domain"], 
                    $params["secure"], $params["httponly"]
                );
            }
        
            // Destruir a sessão
            session_destroy();
        
            // Redirecionar para a página de login ou home
            header('Location: /Login/login');
            exit;
        }
        
        
    }
?>