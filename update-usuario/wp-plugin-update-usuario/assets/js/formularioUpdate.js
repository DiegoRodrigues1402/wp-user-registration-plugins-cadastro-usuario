// Use IIFE para evitar conflitos
(function ($) {
	// Use noConflict para liberar o identificador "$" e evitar conflitos com outros scripts
	$.noConflict();
	//criar mascara para os campos
	
	$('.mascara-telefone').mask('(00)00000-0000');
	$('.mascara-cep').mask('00000.000');




	// Manipula o evento de clique no botão
	$("#btn-update-formulario").click(function () {
		let ret = validaCampos();
		if (ret === true) {
			// Perguntar ao usuário se ele realmente quer enviar
			if (!confirm("Confirma o envio dos dados!")) return;
			//alert("ENVIANDO DADOS");
			salvarDados();
		}
	});



	function validaCampos() {
								
		//Validar endereço
		if ($('#usuario-cep').val().trim() == '') {
			alert('POR FAVOR INFORME SEU CEP')
			document.getElementById('usuario-cep').focus()
			return false
		}
		if ($('#usuario-logradouro').val().trim() == '') {
			alert('POR FAVOR INFORME SEU ENDEREÇO')
			document.getElementById('usuario-logradouro').focus()
			return false
		}
		if ($('#usuario-numero').val().trim() == '') {
			alert('POR FAVOR INFORME SEU NÚMERO')
			document.getElementById('usuario-numero').focus()
			return false
		}
		if ($('#usuario-bairro').val().trim() == '') {
			alert('POR FAVOR INFORME SEU BAIRRO')
			document.getElementById('usuario-bairro').focus()
			return false
		}
		if ($('#usuario-cidade').val().trim() == '') {
			alert('POR FAVOR INFORME SUA CIDADE')
			document.getElementById('usuario-cidade').focus()
			return false
		}
		if ($('#usuario-uf').val().trim() == '') {
			alert('POR FAVOR INFORME SEU ESTADO')
			document.getElementById('usuario-uf').focus()
			return false
		}
		if ($('#usuario-aceito-termos').is(':checked') == false) {
			alert('É PRECISO ACEITAR OS TERMOS')
			this.focus()
			return false
		}
		return true
	}

	

	// salvar os dados validados e adicionar no dados
	function salvarDados() {
		var dados = {
			user_nome: $('#usuario-nome').val().trim(),
			user_cpf: $('#usuario-cpf').val().trim(),
			user_codigo_entidade: $('#usuario-codigo-entidade').val().trim(),
			user_codigo_operador: $('#usuario-codigo-operador').val().trim(),
			user_telefone: $('#usuario-celular').val().trim(),
			user_email: $('#usuario-email').val().trim(),
			user_senha: $('#usuario-senha').val().trim(),
			user_cep: $('#usuario-cep').val().trim(),
			user_logradouro: $('#usuario-logradouro').val().trim(),
			user_numero: $('#usuario-numero').val().trim(),
			user_complemento: $('#usuario-complemento').val().trim(),
			user_bairro: $('#usuario-bairro').val().trim(),
			user_cidade: $('#usuario-cidade').val().trim(),
			user_uf: $('#usuario-uf').val().trim(),
			
		}
		console.log(dados);

		// Pega o domínio atual
		var dominioAtual = window.location.origin;
		//transfere a var dados para o servidor
		$.ajax({
			url: dominioAtual + '/wp-json/api/user/atualizarDados',
			method: 'POST',
			data: dados,
			success: function (data) {
				console.log(data);
				if (data.status === 'sucesso') {
					alert("SALVO COM SUCESSO");
					window.location.href = 'https://www.linkedin.com/in/diego-flores-rodrigues-0924ab42/'
				} else {
					alert(data.message);
				}
			},
			error: function (data) {
				console.log(data);
				$("#msg-form-cad").html("<h3>Algum erro</h3>");
			}
		});
	}

})(jQuery);