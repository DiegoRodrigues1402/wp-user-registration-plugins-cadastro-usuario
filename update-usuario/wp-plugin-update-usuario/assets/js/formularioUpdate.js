// Use IIFE para evitar conflitos
(function ($) {
	// Use noConflict para liberar o identificador "$" e evitar conflitos com outros scripts
	$.noConflict();
	//criar mascara para os campos

	$('.mascara-telefone').mask('(00)00000-0000');
	$('.mascara-cep').mask('00000.000');




	// Manipula o evento de clique no botão
	$("#btn-atualizar-dados").click(function () {
		let ret = atualizarDados();
		if (ret === true) {
			// Perguntar ao usuário se ele realmente quer enviar
			if (!confirm("Confirmar o envio dos dados!")) return;
			//alert("ENVIANDO DADOS");
			salvarDadosAtualizados();
		}
	});


	function atualizarDados() {
		
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

		return true
	}
	$("#usuario-cep").on('blur', function () {
		var cep = $(this).val().replace(/\D/g, ''); // Remove os caracteres não numéricos do CEP
		if (cep.length === 8) { // Verifica se o CEP possui 8 dígitos
			buscarEnderecoPorCepUser(cep);
		}
	});

	// Função para buscar o endereço pelo CEP e auto preencher os campos referentes ao endereço
	function buscarEnderecoPorCepUser(cep) {
		$.ajax({
			url: `https://viacep.com.br/ws/${cep}/json/`,
			dataType: 'json',
			success: function (data) {
				if (!data.erro) {
					$('#usuario-logradouro').val(data.logradouro).prop('disabled', true)
					$('#usuario-numero').focus()
					$('#usuario-bairro').val(data.bairro).prop('disabled', true)
					$('#usuario-cidade').val(data.localidade).prop('disabled', true)
					$('#usuario-uf').val(data.uf).prop('disabled', true)
				} else {
					$('#usuario-logradouro').val(data.logradouro).removeAttr('disabled').focus()
					$('#usuario-numero').val(data.numero).removeAttr('disabled')
					$('#usuario-complemento').val(data.complemento).removeAttr('disabled')
					$('#usuario-bairro').val(data.bairro).removeAttr('disabled')
					$('#usuario-cidade').val(data.localidade).removeAttr('disabled')
					$('#usuario-uf').val(data.uf).removeAttr('disabled')
				}
			}
		});
	}


	// salvar os dados validados e adicionar no dados
	function salvarDadosAtualizados() {
		var dados = {
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