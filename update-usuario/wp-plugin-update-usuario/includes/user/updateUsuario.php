<?php
    class atualizarCadastroUsuario
    {
        public static function init()
        {
            add_shortcode('atualizar_cadastro', 'atualizarCadastroUsuario::formularioAtualizacao');
            add_action('wp_enqueue_scripts', 'atualizarCadastroUsuario::enqueue_scripts', 500);
            //caminho Endpoint da API
            add_action('rest_api_init', function () {
                register_rest_route('api/user', '/atualizarUsuario', array(
                    'methods' => 'POST',
                    'callback' => array('atualizarCadastroUsuario', 'atualizarUsuario'),
                ));
            });
            
        }


        public static function formularioAtualizacao()
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
            $user_email    = $current_user->user_email;
           



            //pega os campos do cliente 
            $user_nome = get_user_meta($user_id, 'user_nome_completo', true);
            $user_cpf = get_user_meta($user_id, 'user_cpf', true);
            $user_codigo_entidade = get_user_meta($user_id, 'user_codigo_entidade', true);
            $user_codigo_operador = get_user_meta($user_id, 'user_codigo_operador', true);
            $user_celular = get_user_meta($user_id, 'user_telefone', true);
            $user_email = get_user_meta($user_id, 'user_email', true);
            $cep = get_user_meta($user_id, 'user_cep', true);
            $logradouro = get_user_meta($user_id, 'user_logradouro', true);
            $numero = get_user_meta($user_id, 'user_numero', true);
            $complemento = get_user_meta($user_id, 'user_complemento', true);
            $bairro = get_user_meta($user_id, 'user_bairro', true);
            $cidade = get_user_meta($user_id, 'user_cidade', true);
            $uf = get_user_meta($user_id, 'user_uf', true);
    ?>



         <!--Formulario em HTML-->
         <form name="formUsuario" id="formUsuario" action="#" autocomplete="off">
            <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $user_id; ?>" />
             <label for="usuario-nome">Nome completo</label>
             <input type="text" name="usuario-nome" id="usuario-nome" value="<?= $user_nome; ?>" disabled />
             <label for="usuario-cpf">CPF</label>
             <input type="text" name="usuario-cpf" id="usuario-cpf" class="mascara-cpf" value="<?= $user_cpf; ?>" disabled />
             <label for="usuario-codigo-entidade">Codigo entidade</label>
             <input type="text" name="usuario-codigo-entidade" id="usuario-codigo-entidade" value="<?= $user_codigo_entidade; ?>" disabled />
             <label for="usuario-codigo-operador">Codigo operador</label>
             <input type="text" name="usuario-codigo-operador" id="usuario-codigo-operador" value="<?= $user_codigo_operador; ?>" disabled />
             <label for="usuario-celular">Celular</label>
             <input type="text" name="usuario-celular" id="usuario-celular" class="mascara-telefone" value="<?= $user_celular; ?>" disabled />
             <label for="usuario-email">E-mail</label>
             <input type="email" name="usuario-email" id="usuario-email" value="<?= $user_email; ?>"  require />
             <label for="usuario-cep">Cep</label>
             <input type="text" name="usuario-cep" id="usuario-cep" class="mascara-cep" value="<?= $cep; ?>" require />
             <label for="usuario-logradouro">Logradouro</label>
             <input type="text" name="usuario-logradouro" id="usuario-logradouro" value="<?= $logradouro; ?>" require />
             <label for="usuario-numero">Numero</label>
             <input type="text" name="usuario-numero" id="usuario-numero" value="<?= $numero; ?>" require />
             <label for="usuario-complemento">Complemento</label>
             <input type="text" name="usuario-complemento" id="usuario-complemento" value="<?= $complemento; ?>" />
             <label for="usuario-bairro">Bairro</label>
             <input type="text" name="usuario-bairro" id="usuario-bairro" value="<?= $bairro; ?>" require />
             <label for="usuario-cidade">Cidade</label>
             <input type="text" name="usuario-cidade" id="usuario-cidade" value="<?= $cidade; ?>" require />
             <label for="usuario-uf">UF</label>
             <input type="text" name="usuario-uf" id="usuario-uf" value="<?= $uf; ?>" require />
             <br>
             <button type="button" id="btn-atualizar-dados">EDITAR CADASTRO</button>
         </form>

 <?php

        }




        //salvar dados vindo da validação do JS
        public static function atualizarUsuario($request)
        {
            // Obtenha os dados enviados na requisição POST
            $dados = $request->get_params();

            $user_id = sanitize_text_field( $dados['user_id']);
            $user_email = sanitize_text_field( $dados['user_email']);
            $cep = sanitize_text_field( $dados['user_cep']);
            $logradouro = sanitize_text_field( $dados['user_logradouro']);
            $numero = sanitize_text_field( $dados['user_numero']);
            $complemento = sanitize_text_field( $dados['user_complemento']);
            $bairro = sanitize_text_field( $dados['user_bairro']);
            $cidade = sanitize_text_field( $dados['user_cidade']);
            $uf = sanitize_text_field( $dados['user_uf']);



            // Verifique se o e-mail já está em uso por outro usuário
            $user_id_by_email = email_exists($user_email);
            // Se o e-mail já estiver em uso e não pertencer ao usuário atual, retorne um erro
            if ($user_id_by_email && $user_id_by_email !=  $user_id) {
                $retorno = array(
                    'status' => 'erro',
                    'message' => 'E-MAIL JÁ CADASTRADO POR OUTRO USUÁRIO',
                );
                return new WP_REST_Response($retorno, 200);
            }




            //Função para salvar no BD do WP 

            //usamos para editar email que é nativo do WP
            wp_update_user(array('ID' => $user_id, 'user_email' => $user_email));
            update_user_meta($user_id, 'user_email', $user_email);
            update_user_meta($user_id, 'user_cep', $cep);
            update_user_meta($user_id, 'user_logradouro', $logradouro);
            update_user_meta($user_id, 'user_numero', $numero);
            update_user_meta($user_id, 'user_complemento', $complemento);
            update_user_meta($user_id, 'user_bairro', $bairro);
            update_user_meta($user_id, 'user_cidade', $cidade);
            update_user_meta($user_id, 'user_uf', $uf);


            $retorno  = array(
                'status' => 'sucesso',
                'message' => 'Dados salvos com sucesso!',
            );
            return new WP_REST_Response($retorno, 200);
        }


       


        //Chamada JS/css/jquey-mask
        //Ops Se não for usar no WP onde ja tem a api jquery tem que instalar manualmente
        public static function enqueue_scripts()
        {
            $version = time();
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
            wp_enqueue_script('update-usuario', plugins_url('wp-plugin-update-usuario/assets/js/formularioUpdate.js'), array('jquery'), $version, true);
        }
    }

    ?>