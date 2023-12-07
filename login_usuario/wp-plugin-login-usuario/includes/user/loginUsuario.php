<?php

/*Plugins Login usuario*/
class LoginUsuario
{
    public static function init()
    {
        add_shortcode('login_usuario', 'LoginUsuario::formLoginUser');
        add_action('wp_enqueue_scripts', 'LoginUsuario::enqueue_scripts', 500);
        add_action('rest_api_init', function () {
            register_rest_route('api/user', '/logar', array(
                'methods' => 'POST',
                'callback' => array('LoginUsuario', 'logar'),
            ));
        });
    }

    public static function formLoginUser()
    {
        //Condição para usar o formulario somente logado
        if (is_user_logged_in()) {
            echo "<div class='sucesso-login'>VOCÊ ESTÁ LOGADO, REDIRECIONANDO...</div>";
            wp_redirect(get_site_url() . "/login");
            exit();
        }
        ?>
        <!-- Formulario em HTML -->
        <form name="formLoginUser" id="formLoginUser" action="#" autocomplete="off">
            <label for="loginUsuario-cpf">CPF</label>
            <input type="text" name="loginUsuario-cpf" id="loginUsuario-cpf" class="mascara-cpf" value="" required />

            <label for="loginUsuario-senha">Senha</label>
            <input type="password" name="loginUsuario-senha" id="loginUsuario-senha" value="" required />

            <button type="button" id="btn-usuario-login">ENTRAR</button>
        </form>
        <?php
    }

    // Salvar dados vindo da validação do JS
    public static function logar($request)
    {
        $dados = $request->get_params();
        $user_cpf = sanitize_text_field($dados['user_cpf']);
        $user_senha = sanitize_text_field($dados['user_senha']);
        $user_cpf = preg_replace('/\D/', '', $user_cpf);
        $credentials = array();
        $credentials['user_login'] =  $user_cpf;
        $credentials['user_password'] = $user_senha;
        $user = wp_signon($credentials, "");

        if (is_wp_error($user)) {
            $retorno = array(
                'status' => 'erro',
                'message' => 'LOGIN INVALIDO',
            );
            return new WP_REST_Response($retorno, 200);
        }

        wp_clear_auth_cookie();
        do_action('wp_login', $user->ID);
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);

        $retorno  = array(
            'status' => 'sucesso',
            'message' => 'Login efetuado com sucesso!',
        );
        return new WP_REST_Response($retorno, 200);
    }

    public static function enqueue_scripts()
    {
        $version = time();
        wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
        wp_enqueue_script('formulario-login-usuario', plugins_url('wp-plugin-login-usuario/assets/js/loginUsuario.js'), array('jquery'), $version, true);
    }
}

?>
