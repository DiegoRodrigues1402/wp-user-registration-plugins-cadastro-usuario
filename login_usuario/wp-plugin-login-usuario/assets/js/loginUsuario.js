(function (jQuery) {
    // Use noConflict para liberar o identificador "$" e evitar conflitos com outros scripts
    jQuery.noConflict();

    // Criar máscara para os campos
    jQuery('.mascara-cpf').mask('000.000.000-00');

    // Manipula o evento de clique no botão
    jQuery("#btn-usuario-login").click(function () {
        let ret = CamposLogin();
        if (ret === true) {
            salvarDadosLogin();
        }
    });

    function CamposLogin() {
        // Valida CPF
        if (jQuery('#loginUsuario-cpf').val().trim() == '') {
            alert('POR FAVOR INFORME SEU CPF');
            document.getElementById('loginUsuario-cpf').focus();
            return false;
        }

        // Validação senha
        if (jQuery('#loginUsuario-senha').val().trim() == '') {
            alert('POR FAVOR INFORME UMA SENHA');
            document.getElementById('loginUsuario-senha').focus();
            return false;
        }

        return true;
    }

    // Salvar os dados validados e adicionar nos dados
    function salvarDadosLogin() {
        var dadosLogar = {
            user_cpf: jQuery('#loginUsuario-cpf').val().trim(),
            user_senha: jQuery('#loginUsuario-senha').val().trim(),
        };
        

        $("#btn-usuario-login").prop("disabled", true);
        $("#btn-usuario-login").text("VALIDANDO");

        // Pega o domínio atual
        var dominioAtual = window.location.origin;
        console.log(dominioAtual);

        // Transfere a var dados para o servidor
        jQuery.ajax({
            url: dominioAtual + '/wp-json/api/user/logar',
            method: 'POST',
            data: dadosLogar,
            success: function (data) {
                console.log(data);
                if (data.status === 'sucesso') {
                    // alert("SALVO COM SUCESSO");
                    $("#msm-carregando").css("display", "block");
                    window.location.href = dominioAtual;
                } else {
                    alert(data.message);
                    $("#btn-usuario-login").prop("disabled", false);
                    $("#btn-usuario-login").text("ENTRAR");
                }
            },
            error: function (data) {
                console.log(data);
                jQuery("#msg-form-cad").html("<h3>Algum erro</h3>");
                alert("ERRO INTERNO");
            }
        });
    }

})(jQuery);
