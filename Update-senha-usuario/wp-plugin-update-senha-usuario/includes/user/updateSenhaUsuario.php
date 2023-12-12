<?php
    class atualizarSenhaLogin
    {
        public static function init()
        {
            add_shortcode('atualizar_senha_login', 'atualizarSenhaLogin::formularioAtualizacaoSenha');
            add_action('wp_enqueue_scripts', 'atualizarSenhaLogin::enqueue_scripts', 500);
            //caminho Endpoint da API
           add_action('rest_api_init', function () {
                register_rest_route('api/user', '/atuaSenhaUsuario', array(
                    'methods' => 'POST',
                    'callback' => array('atualizarSenhaLogin', 'atuaSenhaUsuario'),
                ));
            });
            
        }


        public static function formularioAtualizacaoSenha()
        {
            //Condição para usar o formulario somente logado
            if (!is_user_logged_in()) {
                echo "<div class='sucesso-login'>VOCÊ NÃO ESTÁ LOGADO, REDIRECIONANDO...</div>";
                wp_redirect(get_site_url() . "/login");
                exit();
            }


            // Obtenha os dados do usuário logado
            $current_user = wp_get_current_user();

            // Pegue os detalhes que você deseja pelo ID
            $user_id       = $current_user->ID;
           
           
    ?>



         <!--Formulario em HTML-->
         <form name="formularioSenha" id="formularioSenha" action="#" autocomplete="off">
            <input type="hidden" name="us_id" id="us_id" value="<?= $user_id; ?>" />

            <label for="us-senha">Senha</label>
             <input type="password" name="us-senha" id="us-senha"  require />

             <label for="us-confirmar-senha">Confirmar Senha</label>
             <input type="password" name="us-confirmar-senha" id="us-confirmar-senha" require />
           
             
             <button type="button" id="btn-atualizar-senha">EDITAR CADASTRO</button>
         </form>

 <?php

        }


       //salvar dados vindo da validação do JS
       public static function atuaSenhaUsuario($request)
	{
		try {
			$dados = $request->get_params();
			$user_id = $dados['user_id'];
			$user_senha_atualizada = sanitize_text_field($dados['user_senha_atualizada']);

			if (!$user_id) {
				throw new Exception('Usuário não encontrado.');
			}

			wp_set_password($user_senha_atualizada, $user_id);

			$retorno = array(
				'status' => 'sucesso',
				'message' => 'Senha atualizada com sucesso!',
			);

			return new WP_REST_Response($retorno, 200);
		} catch (Exception $e) {
			error_log('Erro ao atualizar senha: ' . $e->getMessage());
			$retorno = array(
				'status' => 'erro',
				'message' => 'Erro ao atualizar senha. Consulte os logs para mais detalhes.',
			);

			return new WP_REST_Response($retorno, 500);
		}
	}

     
        //Chamada JS/css/jquey-mask
        //Ops Se não for usar no WP onde ja tem a api jquery tem que instalar manualmente
        public static function enqueue_scripts()
        {
            $version = time();
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
            wp_enqueue_script('trocar-senha', plugins_url('wp-plugin-update-senha-usuario/assets/js/formularioUpdateSenha.js'), array('jquery'), $version, true);
        }
    }

    ?>