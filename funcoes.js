jQuery(function($){


	//  #####     #    ######  ####### #     #    #     #####
	// #     #   # #   #     # #     # ##    #   # #   #     #
	// #        #   #  #     # #     # # #   #  #   #  #
	// #       #     # ######  #     # #  #  # #     #  #####
	// #       ####### #   #   #     # #   # # #######       #
	// #     # #     # #    #  #     # #    ## #     # #     #
	//  #####  #     # #     # ####### #     # #     #  #####

	$('.lotada').click(function(){
		var id = $(this).attr('id_carona');
		$.ajax({
			url: 'processa.php',
			type: 'POST',
			data: {'acao': 'carona_lotada', 'id_carona':id},
			success: function(data){
				location.reload();
			}
		});
	});

	$('.nlotada').click(function(){
		var id = $(this).attr('id_carona');
		$.ajax({
			url: 'processa.php',
			type: 'POST',
			data: {'acao': 'carona_nlotada', 'id_carona':id},
			success: function(data){
				location.reload();
			}
		});
	});

	$('#ida_volta').change(function(){
		if( $('#ida_volta :selected').val() == 'volta'){
			$('#origem').val("R. Princesa Isabel, 60 Feliz - RS");
			$('#destino').val('');
			$('#destino').attr('placeholder', "Marque o local no mapa abaixo");
			$("#temp").val("volta");
		}
		else if ( $('#ida_volta :selected').val() == 'ida'){
			$('#destino').val("R. Princesa Isabel, 60 Feliz - RS");
			$('#origem').val('');
			$('#origem').attr('placeholder', "Marque o local no mapa abaixo");
			$("#temp").val("ida");
		}
	});

	//MODAL DE PEDIR CARONA
	$('#pedir_carona').click(function(){
		$('#modalPedirCarona').modal('show');
	});

	//COMO A MENSAGEM DO PEDIR CARONA TEM QUE ESTAR EM UMA DIV, AO DAR SUBMIT NO FORM ELE ADICIONA O CONTEUDO DA MENSAGEM AO INPUT REAL DA MENSAGEM
	$('#form_pedir').submit(function(){
		$('#input_mensagem').val( $('#div_mensagem').html());
	});

	//ADICIONA A(S) DATA(S) SELECIONADA(S) AO CAMPO DE MENSAGEM
	$("#form_pedir").on("change",".dias_carona", function() {
		$('span#dias').text('');
		$(':checked.dias_carona').each(function(){
			$('span#dias').text($('span#dias').text() + " " + this.value);
		});
	});

	//CONFIGURA AS DATAS DO CALENDÁRIO DEPENDENDO DA CARONA
	$("#form_pedir").on("click",".date", function() {
		$.ajax({
			url: 'processa.php',
			type: 'POST',
			data: {acao: 'dias_carona', carona: $('#id_carona').val()},
			success: function(data){

				var temp = data.split('/');
				var dias = temp[0].split('-');

				var dia_final= new Date(temp[1]);

				dia_final.setHours(dia_final.getHours()+2);

				var desabilitar = Array(0,1,2,3,4,5,6);
				var habilitar = Array();
				var nome_dias = Array('dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab');

				for(i = 0; i < dias.length; i++){

					var index = nome_dias.indexOf(dias[i]);

					if(index != -1){
						habilitar.push(desabilitar[index]);
						desabilitar.splice( index, 1 );
					}
				}

				var hoje = new Date();
				var ano = hoje.getFullYear();
				var mes = hoje.getMonth()+1; // +1 PORQUE ELE INICIA EM 0 --> JANEIRO = 0, DEZEMBRO = 11
				var dia = hoje.getDate();

				if(dia<10) {
				    dia='0'+dia;
				}
				if(mes<10) {
				    mes='0'+mes;
				}
				hoje = dia+"-"+mes+"-"+ano;

				var ano_final = dia_final.getFullYear();
				var mes_final = dia_final.getMonth()+1;
				var dia_final = dia_final.getDate();

				if(dia_final<10) {
				    dia_final='0'+dia_final;
				}
				if(mes_final<10) {
				    mes_final='0'+mes_final;
				}

				dia_final = dia_final+"-"+mes_final+"-"+ano_final;

				$('.date').datepicker('setStartDate', hoje);
				$('.date').datepicker('setEndDate', dia_final);
				$('.date').datepicker('setDaysOfWeekDisabled', desabilitar);
				$('.date').datepicker('setDaysOfWeekHighlighted', habilitar);

			},error: function(){}
		});
	});

	//ADICIONA A DATA SELECIONADA À MENSAGEM
	$('#form_pedir').on('change', '.date', function() {
		$('#dia').text($('#dia_carona').val());
    });

	//TROCA O CONTEUDO EXIBIDO DEPENDENDO DO QUE FOR SELECIONADO
	$('select#pedido').change(function(){
		var nome = $('#input_nome').val();
		var dias = $('#input_dias').val();

		var origem = $('#origem').val();
		var destino = $('#destino').val();
		var saida = $('#saida').val();
		var chegada = $('#chegada').val();

		var mensagem= "Dados da carona: \
		Origem: "+origem+"\
		Destino: "+destino+"\
		Saida: "+saida+"\
		Chegada: "+chegada;

		var umDia = "\
		<input type='hidden' name='tipo' value='um'>\
		<div class='form-group'>\
			<div class='input-group date' data-provide='datepicker'>\
				<input type='text' name='dia' id='dia_carona' class='form-control' readonly/>\
				<span class='input-group-addon'>\
					<span class='glyphicon glyphicon-th'></span>\
				</span>\
			</div>\
		</div>\
		<div class='form-group label-floating'>\
			<label class='control-label'>Mensagem: </label>\
			<div contenteditable='true' class='form-control' id='div_mensagem'>Olá "+ nome +", gostaria de pedir carona para o dia <span id='dia'></span><br>"+mensagem+"</div>\
		</div>";

		var variosDias = "\
		<input type='hidden' name='tipo' value='varios'>\
		<div class='form-group'>\
			<p>A carona funciona nas: "+ dias +"</p>\
			<div class='form-group label-floating'>\
				<label class='control-label'>Mensagem: </label>\
				<div contenteditable='true' class='form-control' id='div_mensagem'>Olá "+ nome +", gostaria de pedir carona para as <span id='dias'></span><br>"+mensagem+"</div>\
			</div>\
			Em qual destes dias gostaria de pedir carona?\
		</div>";

		if( $('#pedido :selected').val() == 'um'){
			var temp = $('#check-dias').clone();
			$('#campos').html(umDia);
			$('#campos').append(temp);
			$('#check-dias').hide();

			$('.date').datepicker({
		    	language: "pt-BR",
				disableTouchKeyboard: true,
				autoclose: true
			});
		}
		else if( $('#pedido :selected').val() == 'varios'){
			var temp = $('#check-dias').clone();
			$('#campos').html(variosDias);
			$('#campos').append(temp);
			$('#check-dias').show();

		}
	});


	//EXCLUIR CARONA
	$('#excluir_carona_admin').click(function(){

		$.ajax({
			url: 'processa.php',
			data: {acao: 'excluir_carona_admin', id: $(this).val()},
			type: "POST",
			success:function(data){
				if(data){
					location.reload();
				}else{
					alert("Carona não excluída, erro");
				}
			},error: function(){}
		});
	});

	$('#excluir_carona').click(function(){
		$.ajax({
			url: 'processa.php',
			data: {acao: 'excluir_carona', id: $(this).val()},
			type: "POST",
			success:function(data){
				if(data){
					location.reload();
				}else{
					alert("Carona não excluída, erro");
				}
			},error: function(){}
		});
	});

	//AVISAR QUE ESTÁ SAINDO
	$('#saindo').click(function(){

		var dia = $(this).attr('dia');
		var hora = $(this).attr('hora');
		var users = $(this).attr('users');

		var conf = confirm("Enviar aviso que está saido para a carona do dia: "+dia+" , às "+hora+" horas para pegar os usuários: "+users+" ?");

		if(conf){

			var id = getUrlParameter('id');
			$.ajax({
				url: 'processa.php',
				data: {'acao':'avisar_saida', 'id_carona': id},
				type: 'POST',
				success: function(data){
					if(data){
						alert("Aviso enviado");
					}else{
						alert('erro');
					}
				},error:function(){}
			});
		}
	});

	$('#mensagem_carona').click(function(){
		$('#modal_mensagem_carona').modal('show');
	});
	$('#enviar_mensagem_carona').click(function(){
		var id = getUrlParameter('id');
		$.ajax({
			url: 'processa.php',
			data: {'acao':'mensagem_carona', 'id_carona': id, 'mensagem': $('#mensagem').text()},
			type: 'POST',
			success: function(data){
				if(data){
					alert("Mensagem enviada");
				}else{
					alert('Ocorreu um erro');
				}
			},error:function(){}
		});
	});


	// #     # ####### #     #    #       #     # ####### #     #  #####     #     #####  ####### #     #
	// ##    # #     # #     #   # #      ##   ## #       ##    # #     #   # #   #     # #       ##   ##
	// # #   # #     # #     #  #   #     # # # # #       # #   # #        #   #  #       #       # # # #
	// #  #  # #     # #     # #     #    #  #  # #####   #  #  #  #####  #     # #  #### #####   #  #  #
	// #   # # #     #  #   #  #######    #     # #       #   # #       # ####### #     # #       #     #
	// #    ## #     #   # #   #     #    #     # #       #    ## #     # #     # #     # #       #     #
	// #     # #######    #    #     #    #     # ####### #     #  #####  #     #  #####  ####### #     #

	//PESQUISA OS DESTINATARIOS
	$('#dest').keyup(function(){

		if($('#dest').val().length > 0){

			$.ajax({
				url: "processa.php",
				data: {'acao': 'listar_nomes', 'nome' : $('#dest').val()},
				type: "POST",
				success:function(data){

					if(data){
						$("#lista_nomes option").remove();
						var lista = JSON.parse(data);

						for(var i=0; i<lista.length; i++){
							temp = lista[i];
							temp_nome = temp[0];
							temp_id = temp[1];

							$('#lista_nomes').append($('<option>', {
								text: temp[0],
								id_usuario: temp[1]
							}));
						}
					}else{
						$("#lista_nomes option").remove();
						$("#lista_nomes").append($('<option>Nada encontrado</option>'));
					}
				},
				error: function(){}
			});
		}else{
			$('#nomes option').remove();
		}
	});

	//QUANDO CLICA EM UM NOME NA LISTA ELE ADICIONA O ID DO NOME CLICADO AO INPUT DE ID'S DE DESTINATARIOS
	$('#dest').on('input', function(){
		var nome = this.value;
		$('#lista_nomes').find('option').each(function(){

			if(this.value == nome){
				$('#dest').attr('id_dest', $(this).attr('id_usuario'));
				$('#dest').val($(this).text());
			}
		});
	});

	//ADICIONA OS DESTINATARIOS NOS CAMPOS
	$('#add').click(function(){
		$('#ddd').html(
			$('#ddd').html() + "<button type='button' onclick='removerNome(this)' value=" + $('#dest').attr('id_dest') + ">" + $('#dest').val() + " <span style='color:red' class='glyphicon glyphicon-remove'></span></button>"
		);

		$('#id_destinatario').val(
			$('#id_destinatario').val() + $('#dest').attr('id_dest') + ","
		);

		$('#dest').val('');
		$('#dest').attr('id_dest', '');
	});



	// ####### ### #       ####### ######  #######  #####
	// #        #  #          #    #     # #     # #     #
	// #        #  #          #    #     # #     # #
	// #####    #  #          #    ######  #     #  #####
	// #        #  #          #    #   #   #     #       #
	// #        #  #          #    #    #  #     # #     #
	// #       ### #######    #    #     # #######  #####



	$('#filtrar_mensagens').click(function(){

		var $tbody = $('table#recebidas tbody');

		var linhas = $tbody.find('tr').get();
		var ordem = $('input[name=order]:checked').val();

		linhas.sort(function(a, b){
			if(ordem == 'id'){
				var x = parseInt($(b).attr('id_mensagem'));
				var y = parseInt($(a).attr('id_mensagem'));
			}
			else if(ordem == 'lida'){
				var x = parseInt($(a).attr('lida'));
				var y = parseInt($(b).attr('lida'));
			}
			else if(ordem == 'sistema'){
				var x = parseInt($(a).attr('id_remetente'));
				var y = parseInt($(b).attr('id_remetente'));
			}
			else if (ordem == 'usuario'){
				var x = $(a).attr('nome_remetente');
				var y = $(b).attr('nome_remetente');
			}
			if(x < y){
				return -1;
			}
			else if(x > y){
				return 1;
			}

			return 0;
		});

		$.each(linhas, function(indice, linha){
			$tbody.append(linha);
		});
	});

	//FILTRO DE CARONAS
	$('#filtrar_caronas').click(function(){
		d = Array();
		t = Array();

		$(":checked.turno").each(function(){
			t.push(this.value);
		});

		$(":checked.dias").each(function(){
			d.push(this.value);
		});

		$.ajax({
			url: 'processa.php',
			data: {acao: 'filtrarCaronas', dias: d, turno: t},
			type: 'POST',
			success:function(data){
				$('#corpo_caronas').html(data);
			}
		});
	});

	//FILTRO DE DENUNCIAS
	$('#filtrar_denuncias').click(function(){

		var $tbody = $('table#lista_denuncias tbody');

		var linhas = $tbody.find('tr').get();
		var ordem = $('input[name=order]:checked').val();


		linhas.sort(function(a, b){
			if(ordem == 'id'){
				var x = parseInt($(a).attr('id_denuncia'));
				var y = parseInt($(b).attr('id_denuncia'));
			}
			else if(ordem == 'status'){
				var x = $(a).attr('status');
				var y = $(b).attr('status');
			}

			if(x < y){
				return -1;
			}
			else if(x > y){
				return 1;
			}

			return 0;
		});


		$.each(linhas, function(indice, linha){
			$tbody.append(linha);
		});
	});

	//FILTRO DE CARONAS CRIADAS PELO USUÁRIOS
	$('#filtrar_minhas_caronas').click(function(){
		var $tbody = $('table#minhas_caronas tbody');

		var linhas = $tbody.find('tr').get();
		var ordem = $('input[name=order]:checked').val();

		if(ordem == 'status'){
			linhas.sort(function(a, b){
				var x = $(a).attr('ativo');
				var y = $(b).attr('ativo');

				if(x == true && y == false){
					return -1;
				}
				else if(x == false && y == true){
					return 1;
				}
				return 0;
			});
		}
		else if(ordem == 'id'){
			linhas.sort(function(a, b){
				var x = $(a).attr('id_carona');
				var y = $(b).attr('id_carona');

				if(x > y){
					return 1;
				}
				else if(x < y){
					return -1;
				}
				return 0;
			});
		}
		$.each(linhas, function(indice, linha){
			$tbody.append(linha);
		});

	});

	//  #####     #    ######     #     #####  ####### ######  #######
	// #     #   # #   #     #   # #   #     #    #    #     # #     #
	// #        #   #  #     #  #   #  #          #    #     # #     #
	// #       #     # #     # #     #  #####     #    ######  #     #
	// #       ####### #     # #######       #    #    #   #   #     #
	// #     # #     # #     # #     # #     #    #    #    #  #     #
	//  #####  #     # ######  #     #  #####     #    #     # #######

	//FORM CADASTRO DE USUARIO: VALIDA CADA CAMPO AO SAIR DELE
	$("#cadastro :input").blur(function(){
			var name = $(this).attr("name");
			var texto = this.value;

			if(name == "senha"){

				if(texto.trim().length < 6){
					$(this).parent().addClass("has-error has-feedback");
					$(this).parent().children("span").remove(".glyphicon");
					$(this).after("<span class='glyphicon glyphicon-warning-sign form-control-feedback'></span>");
					$(this).removeClass("valido");
				}
				else{
					$(this).parent().removeClass("has-error").addClass("valido has-success has-feedback");
					$(this).parent().children("span").remove(".glyphicon");
				}
			}

			else if(name != "telefone"){

				if(texto.trim().length < 2){
					$(this).parent().addClass("has-error has-feedback").removeClass("valido has-success");
					$(this).parent().children("span").remove(".glyphicon");
					$(this).after("<span class='glyphicon glyphicon-warning-sign form-control-feedback'></span>");


					//REMOVE O AVISO DE LOGIN/EMAIL JÁ USADO, POIS NESTES CASOS OS CAMPOS NAO ESTAO DE ACORDO COM O MINIMO DE CARACTERES
					if (name == "login"){
						$("#statusLogin").html("");
					}
					if (name == "email"){
						$("#statusEmail").html("");
					}

				}
				else {
					if (name == "login"){
						verificarLogin(this);

					}
					else if (name == "email"){
						verificarEmail(this);
					}
					else{
						$(this).parent().removeClass("has-error").addClass("has-success valido has-feedback");
						$(this).parent().children("span").remove(".glyphicon");
					}
				}
			}

		verificarBotaoSubmit();
	});

	// ######  #######  #####  ####### #######
	// #     # #       #     #    #    #     #
	// #     # #       #          #    #     #
	// ######  #####    #####     #    #     #
	// #   #   #             #    #    #     #
	// #    #  #       #     #    #    #     #
	// #     # #######  #####     #    #######

	//FAZ O DROPDOWN DE JAVASCRIPT FUNCIONAR
	$(".select").dropdown({"optionClass": "withripple"});

	//FAZ COM QUE AO CLICAR EM UMA TABELA LEVE PARA OUTRA PÁGINA
	$('.link-row').click(function() {
		if($(this).is('tr')){
			window.location = $(this).data('href');
		}else if($(this).is('td')){
			window.location = $(this).parent().data('href');
		}
    });


	//ATIVA OS MODALS
	$('#myModal').modal('show');


	//AJEITA O FLOATING DO MATERIAL DESIGN NOS CAMPOS QUANDO JÁ TEM ALGUM VALOR
    $('input').each(function(){
        if( this.value != "" ){
            $(this).parent().removeClass('is-empty');
        }
    });

	//MASCARA DOS FORMS
	$(":input#telefone").mask('(99) 99999-9999');
	$("#placa").mask('aaa-9999');
	$(".tempo").mask('99:99');


	//ATIVA OS POPOVER'S (ELES ESTÃO SENDO USADOS PRA VER AS DENUNCIAS QUE CADA USUÁRIO SOFREU EM SEU PERFIL)
    $('[data-toggle="popover"]').popover();

	//TIRA O PADDING DOS PANEL's QUE POSSUEM ALGUMA TABELA
	$("div.panel-body:has('table')").css({"padding":"0", "overflow":"auto"});


});




 //####### #     # #     #  #####  ####### #######  #####
 //#       #     # ##    # #     # #     # #       #     #
 //#       #     # # #   # #       #     # #       #
 //#####   #     # #  #  # #       #     # #####    #####
 //#       #     # #   # # #       #     # #             #
 //#       #     # #    ## #     # #     # #       #     #
 //#        #####  #     #  #####  ####### #######  #####


//VERIFICAR SE O LOGIN JÁ EXSITE
function verificarLogin(campo) {
	$.ajax({
		url: "processa.php",
		data:{'acao' : 'verificar_usuario', 'login':$(campo).val()},
		type: "POST",
		success:function(data){

			if(data){
				$("#statusLogin").html("Login em uso. Tente outro");
				$(campo).parent().removeClass("has-success valido").addClass("has-error has-feedback");
				$(campo).parent().children("span").remove(".glyphicon");
                $(campo).after("<span class='glyphicon glyphicon-error-sign form-control-feedback'></span>");
			}else{
				$("#statusLogin").html("Login disponível para uso");
				$(campo).parent().removeClass("has-error").addClass("has-success has-feedback valido");
                $(campo).parent().children("span").remove(".glyphicon");
			}
		}
	});

}

//VERIFICAR SE O EMAIL JÁ EXISTE
function verificarEmail(campo) {
	$.ajax({
		url: "processa.php",
		data:{'acao':'verificar_email', 'email':$(campo).val()},
		type: "POST",
		success:function(data){

			if(data){
				$("#statusEmail").html("Email em uso. Tente outro");
				$(campo).parent().removeClass("valido has-success").addClass("has-error has-feedback");
				$(campo).parent().children("span").remove(".glyphicon");
	            $(campo).after("<span class='glyphicon glyphicon-error-sign form-control-feedback'></span>");
			}else{
				$("#statusEmail").html("Email disponível para uso");
				$(campo).parent().addClass("has-success has-feedback valido").removeClass("has-error");
	            $(campo).parent().children("span").remove(".glyphicon");
			}
		},
		error:function (){}
	});
}

//VERIFICA SE O BOTAO NO FORM DE CADASTRO PODE SER HABILITADO
function verificarBotaoSubmit(){
	var certo = true;
	$("#cadastro :input:not([type=hidden], [type=tel])").each(function(i, sel){
		if ($(sel).attr('type')!='submit'  &&  !$(sel).parent().hasClass("valido")){
			certo = false;
		}
	});
	if (certo){
		$("#botaoCad").attr("disabled", false);
	}else{
		$("#botaoCad").attr("disabled", true);
	}
}

//PAGINA DE MENSAGEM
//REMOVE O NOME DO DESTINATARIO QUANDO O BOTAO DO NOME É CLICADO
function removerNome(botao){
	$(botao).remove();
	var botao = $(botao).val();
	var ids = $('#id_destinatario').val().split(',');

	index = ids.indexOf(botao);

	ids.splice(index, 1);
	ids.toString();
	$('#id_destinatario').val(ids);

}


//SERVE PRA PEGAR AS VARIAVEIS QUE PODEM SER PEGAR PELO $_GET
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
}
