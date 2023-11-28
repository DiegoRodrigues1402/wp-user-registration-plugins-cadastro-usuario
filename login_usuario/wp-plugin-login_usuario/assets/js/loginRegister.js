// Use IIFE para evitar conflitos
(function ($) {
	// Use noConflict para liberar o identificador "$" e evitar conflitos com outros scripts
	$.noConflict();
	//criar mascara para os campos
	$('.mascara-cpf').mask('000.000.000-00');
	$('.mascara-telefone').mask('(00)00000-0000');
	$('.mascara-cep').mask('00000.000');




	// Manipula o evento de clique no botão
	$("#btn-usuario-formulario").click(function () {
		let ret = validaCampos();
		if (ret === true) {
			// Perguntar ao usuário se ele realmente quer enviar
			if (!confirm("Confirma o envio dos dados!")) return;
			//alert("ENVIANDO DADOS");
			salvarDados();
		}
	});



	function validaCampos() {
		if (
			$('#usuario-nome').val().trim() == '' ||
			!/\s/.test($('#usuario-nome').val().trim()) ||
			!/^[A-Za-z ]+$/.test($('#usuario-nome').val().trim())
		) {
			alert('POR FAVOR INFORME SEU NOME COMPLETO')
			document.getElementById('usuario-nome').focus()
			return false
		}
		//Valida CPF
		if ($('#usuario-cpf').val().trim() == '') {
			alert('POR FAVOR INFORME SEU CPF')
			document.getElementById('usuario-cpf').focus()
			return false
		}
		var cpf = $('#usuario-cpf').val().trim()
		if (validaCPF(cpf) == false) {
			alert('CPF INVÁLIDO')
			document.getElementById('usuario-cpf').focus()
			return false
		}

		if ($('#usuario-codigo-entidade').val().trim() == '') {
			alert('POR FAVOR INFORME O CODIGO DA SUA ENTIDADE')
			document.getElementById('usuario-codigo-entidade').focus()
			return false
		}
		if ($('#usuario-codigo-operador').val().trim() == '') {
			alert('POR FAVOR INFORME O SEU CODIGO OPERADOR')
			document.getElementById('usuario-codigo-operador').focus()
			return false
		}

		if ($('#usuario-celular').val().trim() == '') {
			alert('POR FAVOR INFORME O SEU CELULAR')
			document.getElementById('usuario-celular').focus()
			return false
		}


		// Validação do email
		var email = $('#usuario-email').val().trim()
		if (email === '') {
			alert('POR FAVOR INFORME SEU E-MAIL')
			document.getElementById('usuario-email').focus()
			return false
		} else if (!validarEmail(email)) {
			alert('E-MAIL INVÁLIDO! POR FAVOR INFORME UM E-MAIL VÁLIDO')
			document.getElementById('usuario-email').focus()
			return false
		}


		//Validação senha
		if ($('#usuario-senha').val().trim() == '') {
			alert('POR FAVOR INFORME UMA SENHA')
			document.getElementById('usuario-senha').focus()
			return false
		}
		var senha = $('#usuario-senha').val().trim()
		if (senha.length < 6 || senha.length > 15) {
			alert('A SENHA DEVE CONTER ENTRE 6 E 15 CARACTERES')
			document.getElementById('usuario-senha').focus()
			return false
		}
		if ($('#usuario-confirmar-senha').val().trim() == '') {
			alert('POR FAVOR CONFIRME A SENHA')
			document.getElementById('usuario-confirmar-senha').focus()
			return false
		}
		if (
			$('#usuario-senha').val().trim() !=
			$('#usuario-confirmar-senha').val().trim()
		) {
			alert('A SENHA E A CONFIRMAÇÃO DA SENHA ESTÃO DIFERENTES')
			document.getElementById('usuario-confirmar-senha').focus()
			return false
		}

		if (isSequencial(senha)) {
			alert('A SENHA NÃO PODE SER SEQUENCIAL');
			document.getElementById('usuario-senha').focus();
			return false;
		}

		// Verifica se a senha contém pelo menos um número, uma letra e um caractere maiúsculo
		if (!containsNumber(senha) || !containsLetter(senha) || !containsUppercase(senha) || !containsSpecialChar(senha)) {
			alert('A SENHA DEVE CONTER PELO MENOS UM NÚMERO, UMA LETRA, UM CARACTERE MAIÚSCULO E UM CARACTERE ESPECIAL');
			document.getElementById('usuario-senha').focus();
			return false;
		}


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

	// Funcação valida CPF
	function validaCPF(cpf) {
		cpf = cpf.replace(/\D/g, ''); // Adicione esta linha para remover caracteres não numéricos

		// Verifica se o CPF tem todos os dígitos iguais, o que o torna inválido
		if (/^(\d)\1+$/.test(cpf)) {
			return false;
		}
		var soma = 0;
		var resto;

		for (var i = 1; i <= 9; i++) {
			soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
		}

		resto = (soma * 10) % 11;
		if ((resto == 10) || (resto == 11)) resto = 0;
		if (resto != parseInt(cpf.substring(9, 10))) return false;

		soma = 0;

		for (var i = 1; i <= 10; i++) {
			soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
		}

		resto = (soma * 10) % 11;
		if ((resto == 10) || (resto == 11)) resto = 0;
		if (resto != parseInt(cpf.substring(10, 11))) return false;

		return true;
	}



	// Define o evento blur para o campo de CEP
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




	// função para validar se o email existe
	function validarEmail(email) {
		var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return regex.test(email);
	}







	function isSequencial(str) {
		// Função para verificar se a senha é sequencial
		for (let i = 0; i < str.length - 2; i++) {
			if (
				str.charCodeAt(i) === str.charCodeAt(i + 1) - 1 &&
				str.charCodeAt(i) === str.charCodeAt(i + 2) - 2
			) {
				return true;
			}
		}
		return false;
	}

	function containsNumber(str) {
		// Verifica se a senha contém pelo menos um número
		return /\d/.test(str);
	}

	function containsLetter(str) {
		// Verifica se a senha contém pelo menos uma letra
		return /[a-zA-Z]/.test(str);
	}

	function containsUppercase(str) {
		// Verifica se a senha contém pelo menos um caractere maiúsculo
		return /[A-Z]/.test(str);
	}

	function containsSpecialChar(str) {
		// Verifica se a senha contém pelo menos um caractere especial
		var specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;
		return specialCharRegex.test(str);
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
			user_termoAceite: $('#usuario-aceito-termos').is(':checked'),
		}
		console.log(dados);

		// Pega o domínio atual
		var dominioAtual = window.location.origin;
		//transfere a var dados para o servidor
		$.ajax({
			url: dominioAtual + '/wp-json/api/user/salvarDadosUsuario',
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