 <?php
<<<<<<< HEAD
    class AtualizacaoUser
    {
        public static function init()
        {
            add_shortcode('update_cadastro', 'AtualizacaoUser::editarCadastro');
            add_action('wp_enqueue_scripts', 'AtualizacaoUser::enqueue_scripts', 500);
            add_action('rest_api_init', function () {
                register_rest_route('api/v2', '/atualizarDados', array(
                    'methods' => 'POST',
                    'callback' => 'AtualizacaoUser::atualizarDados',
                ));
            });
           
            
        }


        public static function editarCadastro()
        {      //Condição para usar o formulario somente logado
            // para fazer teste comentar o if
           
=======
    /*Plugins 
 Formulario para cadastro de usuario*/
    class atualizarDadosUsuario
    {
        public static function init()
        {
            add_shortcode('update_cadastro', 'atualizarDadosUsuario::formLogin');
            add_action('wp_enqueue_scripts', 'atualizarDadosUsuario::enqueue_scripts', 500);
            //caminho Endpoint da API
            add_action('rest_api_init', function () {
                register_rest_route('api/user', '/atualizarDados', array(
                    'methods' => 'POST',
                    'callback' => ' atualizarDadosUsuario::atualizarDados',
                ));
            });
        }


        public static function formLogin()
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
            $user_login    = $current_user->user_login;
            $user_email    = $current_user->user_email;
            $user_firstname = $current_user->user_firstname;
            $user_lastname = $current_user->user_lastname;



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
>>>>>>> 000cbfd0d0ff433a36185ab289998ba22076ddec
    ?>



         <!--Formulario em HTML-->
         <form name="formUsuario" id="formUsuario" action="#" autocomplete="off">
             <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>" />
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
             <input type="email" name="usuario-email" id="usuario-email" value="<?= $user_email; ?>" disabled />
             <label for="usuario-cep">Cep</label>
             <input type="text" name="usuario-cep" id="usuario-cep" class="mascara-cep" value="<?= $cep; ?>" require />
             <label for="usuario-logradouro">Logradouro</label>
             <input type="text" name="usuario-logradouro" id="usuario-logradouro" value="<?= $logradouro; ?>" require />
             <label for="usuario-numero">Numero</label>
             <input type="text" name="usuario-numero" id="usuario-numero" value="<?= $numero; ?>" require />
             <label for="usuario-complemento">Complemento</label>
             <input type="text" name="usuario-complemento" id="usuario-complemento" value="<?= $complemento; ?>" />
             <label for="usuario-bairro">Bairro</label>
<<<<<<< HEAD
             <input type="text" name="usuario-bairro" id="usuario-bairro" value="" require />
            
             
             
             <br>
             <button type="button" id="btn-update-formulario">EDITAR CONTA</button>
=======
             <input type="text" name="usuario-bairro" id="usuario-bairro" value="<?= $bairro; ?>" require />
             <label for="usuario-cidade">Cidade</label>
             <input type="text" name="usuario-cidade" id="usuario-cidade" value="<?= $cidade; ?>" require />
             <label for="usuario-uf">UF</label>
             <input type="text" name="usuario-uf" id="usuario-uf" value="<?= $uf; ?>" require />
             <br>
             <label for="usuario-aceito-termos" style="display: block;">
                 <input type="checkbox" name="usuario-aceito-termos" id="usuario-aceito-termos">
                 Concordo com o tratamento dos meus dados pessoais e dos dados pessoais do(s) meu(s)
                 dependente(s) para recebimento de informações.
             </label>
             <br>
             <button type="button" id="btn-update-formulario">EDITAR CADASTRO</button>
>>>>>>> 000cbfd0d0ff433a36185ab289998ba22076ddec
         </form>

 <?php

        }




<<<<<<< HEAD
=======
        //salvar dados vindo da validação do JS
        public static function atualizarDados($request)
        {
            // Obtenha os dados enviados na requisição POST
            $dados = $request->get_params();
            $user_nome = sanitize_text_field($dados['user_nome']);
            $user_cpf = sanitize_text_field($dados['user_cpf']);
            $user_codigo_entidade = sanitize_text_field($dados['user_codigo_entidade']);
            $user_codigo_operador = sanitize_text_field($dados['user_codigo_operador']);
            $user_celular = sanitize_text_field($dados['user_telefone']);
            $user_email = sanitize_email($dados['user_email']);
            $user_senha =  $dados['user_senha'];
            $cep = sanitize_text_field($dados['user_cep']);
            $logradouro = sanitize_text_field($dados['user_logradouro']);
            $numero = sanitize_text_field($dados['user_numero']);
            $complemento = sanitize_text_field($dados['user_complemento']);
            $bairro = sanitize_text_field($dados['user_bairro']);
            $cidade = sanitize_text_field($dados['user_cidade']);
            $uf = sanitize_text_field($dados['user_uf']);




            //Criamos um Array que vai para o BD do WP
            //com ele apontamos login, senha, nome e cria um id com essas informações
            $WP_array = array(
                'user_login'    =>  $user_cpf,
                'user_email'    =>  $user_email,
                'user_pass'     =>  $user_senha,
                'user_url'      =>  '',
                'display_name'  =>  $first_name,
                'first_name'    =>  $first_name,
                'last_name'     =>  $last_name,
                'nickname'      =>  $first_name,
                'description'   =>  '',
            );
            $idUser = wp_insert_user($WP_array);
            // Atribua o papel de "Autor" ao usuário
            $user = new WP_User($idUser);
            $user->set_role('author');


            //Função para salvar no BD do WP 
            update_user_meta($idUser, 'user_nome_completo', $user_nome);
            update_user_meta($idUser, 'user_cpf', $user_cpf);
            update_user_meta($idUser, 'user_codigo_entidade',  $user_codigo_entidade);
            update_user_meta($idUser, 'user_codigo_operador',  $user_codigo_operador);
            update_user_meta($idUser, 'user_telefone', $user_celular);
            update_user_meta($idUser, 'user_email', $user_email);
            update_user_meta($idUser, 'user_cep', $cep);
            update_user_meta($idUser, 'user_logradouro', $logradouro);
            update_user_meta($idUser, 'user_numero', $numero);
            update_user_meta($idUser, 'user_complemento', $complemento);
            update_user_meta($idUser, 'user_bairro', $bairro);
            update_user_meta($idUser, 'user_cidade', $cidade);
            update_user_meta($idUser, 'user_uf', $uf);


            $retorno  = array(
                'status' => 'sucesso',
                'message' => 'Dados salvos com sucesso!',
            );
            return new WP_REST_Response($retorno, 200);
        }





        // Transforma nome completo em  primeiro nome nome do meio e nome final
        public static function split_name($nameFull)
        {
            if (empty($nameFull) || is_null($nameFull)) {
                return ""; // Retorne um valor padrão caso esteja vazia ou nula
            }
            $arr = explode(' ', $nameFull);
            $num = count($arr);
            $first_name = $middle_name = $last_name = null;
            if ($num >  0) {
                $first_name = $arr[0];
            }
            if ($num >= 2) {
                $last_name = $arr[$num - 1];
            }
            if ($num > 2) {
                $middle_name = implode(' ', array_slice($arr, 1, $num - 2));
            }
            return  compact('first_name', 'middle_name', 'last_name');
        }



>>>>>>> 000cbfd0d0ff433a36185ab289998ba22076ddec

        //Chamada JS/css/jquey-mask
        //Ops Se não for usar no WP onde ja tem a api jquery tem que instalar manualmente
        public static function enqueue_scripts()
        {
            $version = time();
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
<<<<<<< HEAD
            wp_enqueue_script('atualizar-script', plugins_url('wp-plugin-update-usuario/assets/formularioUpdate.js'), array('jquery'), $version, true);
           
=======
            wp_enqueue_script('update-script', plugins_url('wp-plugin-formulario-usuario/assets/js/formularioUpdate.js'), array('jquery'), $version, true);
>>>>>>> 000cbfd0d0ff433a36185ab289998ba22076ddec
        }
    }

    ?>