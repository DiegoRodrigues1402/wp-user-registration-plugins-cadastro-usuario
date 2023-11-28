 <?php
    /*Plugins 
 Formulario para cadastro de usuario*/
    class Update
    {
        public static function init()
        {
            add_shortcode('update_cadastro', 'Update::formSenha');
            add_action('wp_enqueue_scripts', 'Update::enqueue_scripts', 500);
            //caminho Endpoint da API
            
        }


        public static function formSenha()
        {      //Condição para usar o formulario somente logado
            // para fazer teste comentar o if
            if (is_user_logged_in()) {
                echo "<div class='sucesso-login'>VOCÊ JÁ ESTÁ LOGADO, REDIRECIONANDO...</div>";
                wp_redirect(get_site_url());
                exit();
            }
    ?>
         <!--Formulario em HTML-->
         <form name="formUsuario" id="formUsuario" action="#" autocomplete="off">
             <label for="usuario-nome">Nome completo</label>
             <input type="text" name="usuario-nome" id="usuario-nome" value="" require />
             <label for="usuario-cpf">CPF</label>
             <input type="text" name="usuario-cpf" id="usuario-cpf" class="mascara-cpf" value="" require />
             <label for="usuario-codigo-entidade">Codigo entidade</label>
             <input type="text" name="usuario-codigo-entidade" id="usuario-codigo-entidade" value="" require />
             <label for="usuario-codigo-operador">Codigo operador</label>
             <input type="text" name="usuario-codigo-operador" id="usuario-codigo-operador" value="" require />
             <label for="usuario-celular">Celular</label>
             <input type="text" name="usuario-celular" id="usuario-celular" class="mascara-telefone" value="" require />
             <label for="usuario-email">E-mail</label>
             <input type="email" name="usuario-email" id="usuario-email" value="" require />
             <label for="usuario-senha">Senha</label>
             <input type="password" name="usuario-senha" id="usuario-senha" value="" require />
             <label for="usuario-confirmar-senha">Confirmar senha</label>
             <input type="password" name="usuario-confirmar-senha" id="usuario-confirmar-senha" value="" require />
             <label for="usuario-cep">Cep</label>
             <input type="text" name="usuario-cep" id="usuario-cep" class="mascara-cep" value="" require />
             <label for="usuario-logradouro">Logradouro</label>
             <input type="text" name="usuario-logradouro" id="usuario-logradouro" value="" require />
             <label for="usuario-numero">Numero</label>
             <input type="text" name="usuario-numero" id="usuario-numero" value="" require />
             <label for="usuario-complemento">Complemento</label>
             <input type="text" name="usuario-complemento" id="usuario-complemento" value="" />
             <label for="usuario-bairro">Bairro</label>
             <input type="text" name="usuario-bairro" id="usuario-bairro" value="" require />
             <label for="usuario-cidade">Cidade</label>
             <input type="text" name="usuario-cidade" id="usuario-cidade" value="" require />
             <label for="usuario-uf">UF</label>
             <input type="text" name="usuario-uf" id="usuario-uf" value="" require />
             <br>
             <label for="usuario-aceito-termos" style="display: block;">
                 <input type="checkbox" name="usuario-aceito-termos" id="usuario-aceito-termos">
                 Concordo com o tratamento dos meus dados pessoais e dos dados pessoais do(s) meu(s)
                 dependente(s) para recebimento de informações.
             </label>
             <br>
             <button type="button" id="btn-update-formulario">CRIAR CONTA</button>
         </form>

 <?php

        }




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




            //validando cpf existe no BD
            // Se o CPF já estiver em uso, retorne um JSON indicando o erro encerra o programa
            // Remove caracteres não numéricos e retira as mascaras
            $user_cpf = preg_replace('/\D/', '', $user_cpf);
            $user_id_by_cpf = username_exists($user_cpf);
            if ($user_id_by_cpf) {
                $retorno = array(
                    'status' => 'erro',
                    'message' => 'CPF JÁ CADASTRADO',
                );
                return new WP_REST_Response($retorno, 200);
            }



            //validando se email ja esta no BD
            // Verificar se o email já está em uso
            $user_id_by_email = email_exists($user_email);
            // Se o email já estiver em uso, retorne um JSON indicando o erro
            if ($user_id_by_email) {
                $retorno = array(
                    'status' => 'erro',
                    'message' => 'E-MAIL JÁ CADASTRADO',
                );
                return new WP_REST_Response($retorno, 200);
            }

            //chamada para função split_name
            $arrayName = Update::split_name($user_nome);
            $first_name = $arrayName['first_name'];
            $middle_name =  $arrayName['middle_name'];
            $last_name = $arrayName['last_name'];
            if (!empty($middle_name) && !is_null($middle_name)) {
                $last_name = $middle_name . " " . $last_name;
            }


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




        //Chamada JS/css/jquey-mask
        //Ops Se não for usar no WP onde ja tem a api jquery tem que instalar manualmente
        public static function enqueue_scripts()
        {
            $version = time();
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
            wp_enqueue_script('update-script', plugins_url('wp-plugin-formulario-usuario/assets/js/formularioUpdate.js'), array('jquery'), $version, true);
           
        }
    }

    ?>