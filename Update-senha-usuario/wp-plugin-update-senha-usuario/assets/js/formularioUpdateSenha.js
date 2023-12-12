// Use IIFE para evitar conflitos
(function ($) {
	// Use noConflict para liberar o identificador "$" e evitar conflitos com outros scripts
	$.noConflict();
	//criar mascara para os campos




	// Manipula o evento de clique no botão
	$("#btn-atualizar-senha").click(function () {
		let ret = atualizarNovaSenha();
		if (ret === true) {
			// Perguntar ao usuário se ele realmente quer enviar
			if (!confirm("Confirmar o envio dos dados!")) return;
			//alert("ENVIANDO DADOS");
			salvarSenha();
		}
	});


	function atualizarNovaSenha() {

		if ($('#us-senha').val().trim() == '') {
			alert('POR FAVOR INFORME UMA SENHA')
			document.getElementById('us-senha').focus()
			return false
		}
		var senha = $('#us-senha').val().trim()
		if (senha.length < 6 || senha.length > 15) {
			alert('A SENHA DEVE CONTER ENTRE 6 E 15 CARACTERES')
			document.getElementById('us-senha').focus()
			return false
		}
		if ($('#us-confirmar-senha').val().trim() == '') {
			alert('POR FAVOR CONFIRME A SENHA')
			document.getElementById('us-confirmar-senha').focus()
			return false
		}
		if (
			$('#us-senha').val().trim() !=
			$('#us-confirmar-senha').val().trim()
		) {
			alert('A SENHA E A CONFIRMAÇÃO DA SENHA ESTÃO DIFERENTES')
			document.getElementById('us-confirmar-senha').focus()
			return false
		}

		if (isSequencial(senha)) {
			alert('A SENHA NÃO PODE SER SEQUENCIAL');
			document.getElementById('us-senha').focus();
			return false;
		}

		// Verifica se a senha contém pelo menos um número, uma letra e um caractere maiúsculo
		if (!containsNumber(senha) || !containsLetter(senha) || !containsUppercase(senha) || !containsSpecialChar(senha)) {
			alert('A SENHA DEVE CONTER PELO MENOS UM NÚMERO, UMA LETRA, UM CARACTERE MAIÚSCULO E UM CARACTERE ESPECIAL');
			document.getElementById('us-senha').focus();
			return false;
		}
		

		

		return true
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
	function salvarSenha() {
		var dados = {
			user_id: $('#us_id').val().trim(),
			user_senha_atualizada: $('#us-senha').val().trim(),
			

		}
		console.log(dados);

		// Pega o domínio atual
		var dominioAtual =  window.location.origin;
		console.log(dominioAtual);
		//transfere a var dados para o servidor
		
		$.ajax({
			url: dominioAtual + '/wp-json/api/user/atuaSenhaUsuario',
			method: 'POST',
			data: dados,
			success: function (data) {
				console.log(data);
				if (data.status === 'sucesso') {
					alert("SALVO COM SUCESSO");
					
				} else {
					alert(data.message);
				}
			},
			error: function (data) {
				console.log(data);
				$("#msg-form-cad").html("<h3>Algum erro</h3>");
				alert("ERRO INTERNO");
			}
		});
	}

})(jQuery);