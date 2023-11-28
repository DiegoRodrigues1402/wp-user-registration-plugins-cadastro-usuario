 <?php
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
            
             
             
             <br>
             <button type="button" id="btn-update-formulario">EDITAR CONTA</button>
         </form>

 <?php

        }





        //Chamada JS/css/jquey-mask
        //Ops Se não for usar no WP onde ja tem a api jquery tem que instalar manualmente
        public static function enqueue_scripts()
        {
            $version = time();
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
            wp_enqueue_script('atualizar-script', plugins_url('wp-plugin-update-usuario/assets/formularioUpdate.js'), array('jquery'), $version, true);
           
        }
    }

    ?>