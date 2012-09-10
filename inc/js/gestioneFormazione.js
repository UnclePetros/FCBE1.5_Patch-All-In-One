var campo_width = 0;
var campo_height = 0;
var avatar_width = 0;
var avatar_height = 0;
var numPanchina = 7;
var tipoPanchina = "";
var ie7LeftFix = 0;
var sostituzionePerSchema = false;
var panchinaFissaSchema = new Array(1,2,2,2);

function init(){
	campo_width = parseInt($('#campo').css('width'));
	campo_height = parseInt($('#campo').css('height'));
	avatar_width = parseInt($('#avatar1').css('width'));
	avatar_height = parseInt($('#avatar1').css('height'));
	tipoPanchina = $('#panchina').attr('tipoPanchina');
	tipoSostituzione = $('#panchina').attr('tipoSostituzione');
	if($('#panchina').attr('tipoPanchina') != 'fissa') numPanchina = parseInt($('#modulo').attr('numPanchina'));
	if($('#panchina').attr('tipoSostituzione') == 'schema') sostituzionePerSchema = true;
	if(sostituzionePerSchema) $('#panchina').css('background-image',"url('immagini/panchina_high_schema.png')");
	
	if ( ($.browser.msie) && ($.browser.version == '7.0') ){
		ie7LeftFix = 170;
	}
}

function toggle( elem ) {
	$(elem).parent().next().toggle('slow', 
			function(){
				if($(elem).parent().next().css('display') == 'none') 
					$(elem).attr('src','immagini/circle_grey.png');
				else 
					$(elem).attr('src','immagini/circle_green.png');
				}
	);
}

function gestisciInserimentoPortiereSuNome( elem ){
	gestisciInserimentoPortiere( $(elem).parent().next().next().children().first());
}

function gestisciInserimentoPortiere( elem ){
	
	if($(elem).children().first().attr('src') == 'immagini/checkbox_off.png'){
		inserisciPortiere(elem);
	}
	else{ 
		rimuoviPortiere(elem);
	}
}

function inserisciPortiere( elem ){
	
	var schierabile_titolare = false;
	if($('#avatar1').attr('ruolo') == 'P' && $('#avatar1_nome').text() == '')
		schierabile_titolare = true;
	
	if(schierabile_titolare){
		$('#avatar1_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
		$('#avatar1').css('background-image','none');
		$('#avatar1_nome').removeClass().addClass('nome_avatar');
		$('#avatar1_nome').text($(elem).attr('cognome'));
		$(elem).children().first().attr('src','immagini/checkbox_on.png');
		$('#avatar1').find('input').val($(elem).attr('codice'));
	}
	else{
		var schierabile_panchina = false;
		if($('#avatar12_nome').text() == '')
			schierabile_panchina = true;
		
		if(schierabile_panchina ){
			$('#avatar12_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
			$('#avatar12').css('background-image','none');
			$('#avatar12_nome').removeClass().addClass('nome_avatar');
			$('#avatar12_nome').text($(elem).attr('cognome'));
			$('#avatar12').attr('ruolo','P');
			$('#avatar12').attr('posPanc','0');
			$(elem).children().first().attr('src','immagini/checkbox_on_p.png');
			$('#avatar12').find('input').val($(elem).attr('codice'));
		}
		else{
			//alert("non ammesso!");
		}
	}
	verificaErrori();
}

function rimuoviPortiere( elem ){
	
	var rimuovibile = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'P' && $('#avatar'+i+'_nome').text() == $(elem).attr('cognome'))
			rimuovibile = true;
		else
			i++;
	}
	while(rimuovibile == false && i<=18);
	
	if(rimuovibile){
		$('#avatar'+i+'_img').find("img").remove();
		$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
		$('#avatar'+i+'_nome').text("");
		$(elem).children().first().attr('src','immagini/checkbox_off.png');
		$('#avatar'+i).find('input').val("");
		if (i>=12 ){
			$('#avatar'+i).attr('ruolo','');
		}
		verificaErrori();	
	}
	else{
		//alert("rimozione non ammessa!");
	}
}

function gestisciInserimentoDifensoreSuNome( elem ){
	gestisciInserimentoDifensore( $(elem).parent().next().next().children().first());
}

function gestisciInserimentoDifensore( elem ){
	
	if($(elem).children().first().attr('src') == 'immagini/checkbox_off.png'){
		inserisciDifensore(elem);
	}
	else{ 
		rimuoviDifensore(elem);
	}
}

function inserisciDifensore( elem ){
	
	var schierabile_titolare = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'D' && $('#avatar'+i+'_nome').text() == '')
			schierabile_titolare = true;
		else
			i++;
	}
	while(schierabile_titolare == false && i<=11);
	
	if(schierabile_titolare){
		$('#avatar'+i+'_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
		$('#avatar'+i).css('background-image','none');
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar');
		$('#avatar'+i+'_nome').text($(elem).attr('cognome'));
		$(elem).children().first().attr('src','immagini/checkbox_on.png');
		$('#avatar'+i).find('input').val($(elem).attr('codice'));
	}
	else{
		var schierabile_panchina = false;
		var i=13, numd=0, pos=0;
		do{
			if($('#avatar'+i).attr('ruolo') == 'D') numd++;
			if($('#avatar'+i+'_nome').text() == '' && !schierabile_panchina){
				if(tipoPanchina != 'fissa')
					schierabile_panchina = true;
				else{
					if(panchinaFissaSchema[1] > numd)
						schierabile_panchina = true;
				}
				pos = i;
			}
			i++;
		}
		while(i<=11+numPanchina);
		
		if(schierabile_panchina ){
			$('#avatar'+pos+'_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
			$('#avatar'+pos).css('background-image','none');
			$('#avatar'+pos+'_nome').removeClass().addClass('nome_avatar');
			$('#avatar'+pos+'_nome').text($(elem).attr('cognome'));
			$('#avatar'+pos).attr('ruolo','D');
			$(elem).children().first().attr('src','immagini/checkbox_on_p.png');
			$('#avatar'+pos).find('input').val($(elem).attr('codice'));
			if(!sostituzionePerSchema) {
				$('#avatar'+pos).attr('posPanc',""+(10+numd+1));
				riordinaPanchina();
			}
			else {
				$('#avatar'+pos).attr('posPanc',""+(pos));
				posizionaPanchinaPerSchema();
			}
		}
		else{
			//alert("non ammesso!");
		}
	}
	verificaErrori();
}

function rimuoviDifensore( elem ){
	var rimuovibile = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'D' && $('#avatar'+i+'_nome').text() == $(elem).attr('cognome'))
			rimuovibile = true;
		else
			i++;
	}
	while(rimuovibile == false && i<=18);
	
	if(rimuovibile){
		$('#avatar'+i+'_img').find("img").remove();
		$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
		$('#avatar'+i+'_nome').text("");
		$(elem).children().first().attr('src','immagini/checkbox_off.png');
		$('#avatar'+i).find('input').val("");
		if (i>=12 ){
			$('#avatar'+i).attr('ruolo','');
			if(!sostituzionePerSchema) {
				$('#avatar'+i).css('background-image',"none");
				$('#avatar'+i+'_nome').removeClass();
			}
			else{
				$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
				$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
			}
		}
		if(!sostituzionePerSchema) posizionaPanchina();
		else posizionaPanchinaPerSchema();
		verificaErrori();
	}
	else{
		//alert("rimozione non ammessa!");
	}
}

function gestisciInserimentoCentrocampistaSuNome( elem ){
	gestisciInserimentoCentrocampista( $(elem).parent().next().next().children().first());
}

function gestisciInserimentoCentrocampista( elem ){
	
	if($(elem).children().first().attr('src') == 'immagini/checkbox_off.png'){
		inserisciCentrocampista(elem);
	}
	else{ 
		rimuoviCentrocampista(elem);
	}
}

function inserisciCentrocampista( elem ){
	
	var schierabile_titolare = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'C' && $('#avatar'+i+'_nome').text() == '')
			schierabile_titolare = true;
		else
			i++;
	}
	while(schierabile_titolare == false && i<=11);
	
	if(schierabile_titolare){
		$('#avatar'+i+'_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
		$('#avatar'+i).css('background-image','none');
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar');
		$('#avatar'+i+'_nome').text($(elem).attr('cognome'));
		$(elem).children().first().attr('src','immagini/checkbox_on.png');
		$('#avatar'+i).find('input').val($(elem).attr('codice'));
	}
	else{
		var schierabile_panchina = false;
		var i=13, numc=0, pos=0;
		do{
			if($('#avatar'+i).attr('ruolo') == 'C') numc++;
			if($('#avatar'+i+'_nome').text() == '' && !schierabile_panchina){
				if(tipoPanchina != 'fissa')
					schierabile_panchina = true;
				else{
					if(panchinaFissaSchema[2] > numc)
						schierabile_panchina = true;
				}
				pos=i;
			}
			i++;
		}
		while(i<=11+numPanchina);
		
		if(schierabile_panchina ){
			$('#avatar'+pos+'_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
			$('#avatar'+pos).css('background-image','none');
			$('#avatar'+pos+'_nome').removeClass().addClass('nome_avatar');
			$('#avatar'+pos+'_nome').text($(elem).attr('cognome'));
			$('#avatar' +pos).attr('ruolo','C');
			$(elem).children().first().attr('src','immagini/checkbox_on_p.png');
			$('#avatar'+pos).find('input').val($(elem).attr('codice'));
			if(!sostituzionePerSchema) {
				$('#avatar'+pos).attr('posPanc',""+(20+numc+1));
				riordinaPanchina();
			}
			else {
				$('#avatar'+pos).attr('posPanc',""+(pos));
				posizionaPanchinaPerSchema();
			}
			verificaErrori();
		}
		else{
			//alert("non ammesso!");
		}
	}
	verificaErrori();
}

function rimuoviCentrocampista( elem ){
	var rimuovibile = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'C' && $('#avatar'+i+'_nome').text() == $(elem).attr('cognome'))
			rimuovibile = true;
		else
			i++;
	}
	while(rimuovibile == false && i<=18);
	
	if(rimuovibile){
		$('#avatar'+i+'_img').find("img").remove();
		$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
		$('#avatar'+i+'_nome').text("");
		$(elem).children().first().attr('src','immagini/checkbox_off.png');
		$('#avatar'+i).find('input').val("");
		if (i>=12 ){
			$('#avatar'+i).attr('ruolo','');
			if(!sostituzionePerSchema){
				$('#avatar'+i).css('background-image',"none");
				$('#avatar'+i+'_nome').removeClass();
			}
			else{
				$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
				$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
			}
		}
		if(!sostituzionePerSchema) posizionaPanchina();
		else posizionaPanchinaPerSchema();
		verificaErrori();
	}
	else{
		//alert("rimozione non ammessa!");
	}
}

function gestisciInserimentoAttaccanteSuNome( elem ){
	gestisciInserimentoAttaccante( $(elem).parent().next().next().children().first());
}

function gestisciInserimentoAttaccante( elem ){
	
	if($(elem).children().first().attr('src') == 'immagini/checkbox_off.png'){
		inserisciAttaccante(elem);
	}
	else{ 
		rimuoviAttaccante(elem);
	}
}

function inserisciAttaccante( elem ){
	
	var schierabile_titolare = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'A' && $('#avatar'+i+'_nome').text() == '')
			schierabile_titolare = true;
		else
			i++;
	}
	while(schierabile_titolare == false && i<=11);
	
	if(schierabile_titolare){
		$('#avatar'+i+'_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
		$('#avatar'+i).css('background-image','none');
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar');
		$('#avatar'+i+'_nome').text($(elem).attr('cognome'));
		$(elem).children().first().attr('src','immagini/checkbox_on.png');
		$('#avatar'+i).find('input').val($(elem).attr('codice'));
	}
	else{
		var schierabile_panchina = false;
		var i=13, numa=0, pos=0;
		do{
			if($('#avatar'+i).attr('ruolo') == 'A') numa++;
			if($('#avatar'+i+'_nome').text() == '' && !schierabile_panchina){
				if(tipoPanchina != 'fissa')
					schierabile_panchina = true;
				else{
					if(panchinaFissaSchema[3] > numa)
						schierabile_panchina = true;
				}
				pos=i;
			}
			i++;
		}
		while(i<=11+numPanchina);
		
		if(schierabile_panchina){
			$('#avatar'+pos+'_img').append("<img src='immagini/t_"+$(elem).attr('squadra')+".png'/>");
			$('#avatar'+pos).css('background-image','none');
			$('#avatar'+pos+'_nome').removeClass().addClass('nome_avatar');
			$('#avatar'+pos+'_nome').text($(elem).attr('cognome'));
			$('#avatar' +pos).attr('ruolo','A');
			$(elem).children().first().attr('src','immagini/checkbox_on_p.png');
			$('#avatar'+pos).find('input').val($(elem).attr('codice'));
			if(!sostituzionePerSchema) {
				$('#avatar'+pos).attr('posPanc',""+(30+numa+1));
				riordinaPanchina();
			}
			else {
				$('#avatar'+pos).attr('posPanc',""+(pos));
				posizionaPanchinaPerSchema();
			}
			verificaErrori();
		}
		else{
			//alert("non ammesso!");
		}
	}
	verificaErrori();
}

function rimuoviAttaccante( elem ){
	var rimuovibile = false;
	var i=1;
	do{
		if($('#avatar'+i).attr('ruolo') == 'A' && $('#avatar'+i+'_nome').text() == $(elem).attr('cognome'))
			rimuovibile = true;
		else
			i++;
	}
	while(rimuovibile == false && i<=18);
	
	if(rimuovibile){
		$('#avatar'+i+'_img').find("img").remove();
		$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
		$('#avatar'+i+'_nome').text("");
		$(elem).children().first().attr('src','immagini/checkbox_off.png');
		$('#avatar'+i).find('input').val("");
		if (i>=12 ){
			$('#avatar'+i).attr('ruolo','');
			if(!sostituzionePerSchema){
				$('#avatar'+i).css('background-image',"none");
				$('#avatar'+i+'_nome').removeClass();
			}
			else{
				$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
				$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
			}
		}
		if(!sostituzionePerSchema) posizionaPanchina();
		else posizionaPanchinaPerSchema();
		verificaErrori();
	}
	else{
		//alert("rimozione non ammessa!");
	}
}

//Funzione per ordinare sul campo gli avatar vuoti dei giocatori, sulla base del modulo scelto
function disponiModuloInCampo( elem ){
	
	var modulo = $(elem).val();
	
	for(var i=1; i<=11; i++){
		$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
		$('#avatar'+i).find(".nome_avatar").empty();
		$('#avatar'+i).find(".nome_avatar").removeClass('nome_avatar').addClass('nome_avatar_clean');
		$('#avatar'+i).find("img").remove();
		$('#avatar'+i).find('input').val("");
	}
	
	$('#avatar12').css('background-image',"url('immagini/avatar2.png')");	
	$('#avatar12_nome').empty();
	$('#avatar12_nome').removeClass().addClass('nome_avatar_clean');
	$('#avatar12').find("img").remove();
	$('#avatar12').attr('ruolo','');
	$('#avatar12').find('input').val("");	

	for(var i=2; i<=numPanchina; i++){
		if(!sostituzionePerSchema){
			$('#avatar'+(11+i)).css('background-image',"none");
			$('#avatar'+(11+i)+'_nome').removeClass();
		}
		else{
			$('#avatar'+(11+i)).css('background-image',"url('immagini/avatar2.png')");
			$('#avatar'+(11+i)+'_nome').removeClass().addClass('nome_avatar_clean');
		}
		$('#avatar'+(11+i)+'_nome').empty();
		$('#avatar'+(11+i)).find("img").remove();
		$('#avatar'+(11+i)).attr('ruolo','');
		$('#avatar'+(11+i)).find('input').val("");
	}
	
	disponiFormazioneInCampo(modulo);
	
	$('.check').attr('src','immagini/checkbox_off.png');
	verificaErrori();
}

//Funzione per ordinare graficamente sul campo la formazione attualmente salvata
//Viene richiamata solo al caricamento della pagina gestione_rapida_formazione.php
function disponiFormazioneInCampo( elem ){
	if(elem == "") return;
	
	var modulo = elem.split("-");
	modulo[0] = parseInt(modulo[0]);
	modulo[1] = parseInt(modulo[1]);
	modulo[2] = parseInt(modulo[2]);
	
	//posizionamento Portiere
	$('#avatar1').attr('ruolo','P');
	$('#avatar1').css("position","relative");
	$('#avatar1').css("left",""+((campo_width-avatar_width)/2-ie7LeftFix)+"px");
	$('#avatar1').css("top","20px");
	$('#avatar1').show('slow');
	
	//posizionamento difensori
	for(var i=1; i<=modulo[0]; i++){
		$('#avatar'+(i+1)).attr('ruolo','D');
		$('#avatar'+(i+1)).css("position","relative");
		$('#avatar'+(i+1)).css("left",""+((i-1)*(campo_width/modulo[0])+(campo_width/modulo[0]-avatar_width)/2-ie7LeftFix)+"px");
		$('#avatar'+(i+1)).css("top",""+(-(i-1)*avatar_height+22*3)+"px");
		
		//codice per schieramento a mezza luna
		var top = parseInt($('#avatar'+(i+1)).css("top"));
		if(i<=modulo[0]/2){
			$('#avatar'+(i+1)).css("top",""+(top+10-(i-1)*30)+"px");
		}
		else{
			$('#avatar'+(i+1)).css("top",""+(top+10-(modulo[0]-i)*30)+"px");
		}
		
		$('#avatar'+(i+1)).show('slow');
	}
	
	//posizionamento centrocampisti
	for(var i=1; i<=modulo[1]; i++){
		$('#avatar'+(i+1+modulo[0])).attr('ruolo','C');
		$('#avatar'+(i+1+modulo[0])).css("position","relative");
		$('#avatar'+(i+1+modulo[0])).css("left",""+((i-1)*(campo_width/modulo[1])+(campo_width/modulo[1]-avatar_width)/2-ie7LeftFix)+"px");
		$('#avatar'+(i+1+modulo[0])).css("top",""+(-(i+modulo[0]-2)*avatar_height+20*6)+"px");
		
		//codice per schieramento a mezza luna
		var top = parseInt($('#avatar'+(i+1+modulo[0])).css("top"));
		if(i<=modulo[1]/2){
			$('#avatar'+(i+1+modulo[0])).css("top",""+(top+10-(i-1)*30)+"px");
		}
		else{
			$('#avatar'+(i+1+modulo[0])).css("top",""+(top+10-(modulo[1]-i)*30)+"px");
		}
		
		$('#avatar'+(i+1+modulo[0])).show('slow');
	}
	
	//posizionamento attaccanti
	for(var i=1; i<=modulo[2]; i++){
		$('#avatar'+(i+1+modulo[0]+modulo[1])).attr('ruolo','A');
		$('#avatar'+(i+1+modulo[0]+modulo[1])).css("position","relative");
		$('#avatar'+(i+1+modulo[0]+modulo[1])).css("left",""+((i-1)*(campo_width/modulo[2])+(campo_width/modulo[2]-avatar_width)/2-ie7LeftFix)+"px");
		$('#avatar'+(i+1+modulo[0]+modulo[1])).css("top",""+(-(i+modulo[0]+modulo[1]-3)*avatar_height+20*9)+"px");
		
		//codice per schieramento a mezza luna
		var top = parseInt($('#avatar'+(i+1+modulo[0]+modulo[1])).css("top"));
		if(i<=modulo[1]/2){
			$('#avatar'+(i+1+modulo[0]+modulo[1])).css("top",""+(top-10+(i-1)*30)+"px");
		}
		else{
			$('#avatar'+(i+1+modulo[0]+modulo[1])).css("top",""+(top-10+(modulo[2]-i)*30)+"px");
		}
		
		$('#avatar'+(i+1+modulo[0]+modulo[1])).show('slow');
	}
	
	if(!sostituzionePerSchema) posizionaPanchina();
	else posizionaPanchinaPerSchema();
}

function posizionaPanchina(){
	//posizionamento panchina
	var nump=0,numd=0,numc=0,numa=0,posti_liberi=0;
	for(var i=1; i<=numPanchina; i++){
		$('#avatar'+(11+i)).css("position","relative");
		if($('#avatar'+(11+i)).attr('ruolo') == 'P'){
			$('#avatar'+(11+i)).css("left",""+(5-ie7LeftFix)+"px");
			$('#avatar'+(11+i)).css("top","0px");
			nump++;
		}
		else if($('#avatar'+(11+i)).attr('ruolo') == 'D') {
			$('#avatar'+(11+i)).css("left",""+((avatar_width+15)-ie7LeftFix)+"px");
			$('#avatar'+(11+i)).css("top",""+(numd*(avatar_height+5)-(i-1)*(avatar_height))+"px");
			numd++;
			$('#avatar'+(11+i)).attr('posPanc',''+(10+numd));
		}
		else if($('#avatar'+(11+i)).attr('ruolo') == 'C') {
			$('#avatar'+(11+i)).css("left",""+(2*(avatar_width+15)-ie7LeftFix)+"px");
			$('#avatar'+(11+i)).css("top",""+(numc*(avatar_height+5)-(i-1)*(avatar_height))+"px");
			numc++;
			$('#avatar'+(11+i)).attr('posPanc',''+(20+numc));
		}
		else if($('#avatar'+(11+i)).attr('ruolo') == 'A') {
			$('#avatar'+(11+i)).css("left",""+(3*(avatar_width+15)-ie7LeftFix)+"px");
			$('#avatar'+(11+i)).css("top",""+(numa*(avatar_height+5)-(i-1)*(avatar_height))+"px");
			numa++;
			$('#avatar'+(11+i)).attr('posPanc',''+(30+numa));
		}
		$('#avatar'+(11+i)).show('slow');
	}
	var height = Math.max(nump,numd,numc,numa)*(avatar_height+2);
	if(height < 200) height = 200;
	$('#panchina').css('height',""+height+"px");
}

function posizionaPanchinaPerSchema(){
	//posizionamento panchina
	var nump=0,numd=0,numc=0,numa=0,posti_liberi=0;
	for(var i=1; i<=numPanchina; i++){
		$('#avatar'+(11+i)).css("position","relative");
		if(i<=4){
			$('#avatar'+(11+i)).css("left",""+((avatar_width+15)*(i-1)-ie7LeftFix)+"px");
			$('#avatar'+(11+i)).css("top",""+((1-i)*avatar_height)+"px");
		}
		else{
			$('#avatar'+(11+i)).css("left",""+((avatar_width+15)*(i%4-1)-ie7LeftFix)+"px");
			$('#avatar'+(11+i)).css("top",""+((1-i)*avatar_height+avatar_height+5)+"px");
		}
		$('#avatar'+(11+i)).attr('posPanc',''+(i));
		$('#avatar'+(11+i)).show('slow');
	}
	var height = Math.max(nump,numd,numc,numa)*(avatar_height+2);
	if(height < 200) height = 200;
	$('#panchina').css('height',""+height+"px");
}

function riordinaPanchina(){
	var arrayPanchina = new Array();
	arrayPanchina = panchinaToArray();
	arrayPanchina = insertionSort(arrayPanchina);
	arrayToPanchina(arrayPanchina);
}

function panchinaToArray(){
	var arr = new Array();
	arr[0] = new Array();
	arr[1] = new Array();
	arr[2] = new Array();
	arr[3] = new Array();
	var i=0;
	for(i=13;i<=18;i++){
		arr[0][i-13] = parseInt($('#avatar'+i).attr('posPanc'));
		if($('#avatar'+i+'_img').find('img').length)
			arr[1][i-13] = $('#avatar'+i+'_img').find('img').attr('src');
		else
			arr[1][i-13] = "";
		arr[2][i-13] = $('#avatar'+i+'_nome').text();
		arr[3][i-13] = $('#avatar'+i).find('input').val();
	}
	return arr;
}

function arrayToPanchina(arr){
	var i = 0;
	for(i=13;i<=18;i++){
		
		//azzera avatar
		$('#avatar'+i+'_img').find("img").remove();
		$('#avatar'+i).css('background-image',"url('immagini/avatar2.png')");
		$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar_clean');
		$('#avatar'+i+'_nome').text("");
		$('#avatar'+i).attr('ruolo','');
		$('#avatar'+i).css('background-image',"none");
		$('#avatar'+i+'_nome').removeClass();
		//fine azzeramento
		
		if(arr[2][i-13] != ""){
			$('#avatar'+i).attr('ruolo', posToRuolo(arr[0][i-13]));
			$('#avatar'+i).attr('posPanc', arr[0][i-13]);
			$('#avatar'+i+'_img').append("<img src='"+arr[1][i-13]+"'/>");
			$('#avatar'+i).css('background-image','none');
			$('#avatar'+i+'_nome').removeClass().addClass('nome_avatar');
			$('#avatar'+i+'_nome').text(arr[2][i-13]);
			$('#avatar'+i).find('input').val(arr[3][i-13]);
			
		}
		posizionaPanchina();
	}
}

//function ruoloToPos(ruolo){
//	var pos = 0;
//	if(ruolo == 'P') pos = 0;
//	else if(ruolo == 'D') pos = 1;
//	else if(ruolo == 'C') pos = 2;
//	else if(ruolo == 'A') pos = 3;
//	return pos;
//}

function posToRuolo(pos){
	var ruolo = '';
	if(pos == 0) ruolo = 'P';
	else if(pos < 20) ruolo = 'D';
	else if(pos < 30) ruolo = 'C';
	else if(pos < 40) ruolo = 'A';
	return ruolo;
}

function insertionSort(arr) {
    var len = arr[0].length, i = -1, j, tmpPos,tmpImg,tmpNome, tmpCodice;
 
    while (len--) {
    	i++;
        tmpPos = arr[0][i];
        tmpImg = arr[1][i];
        tmpNome = arr[2][i];
        tmpCodice = arr[3][i];
        j = i;
        while (j-- && arr[0][j] > tmpPos) {
            arr[0][j + 1] = arr[0][j];
            arr[1][j + 1] = arr[1][j];
            arr[2][j + 1] = arr[2][j];
            arr[3][j + 1] = arr[3][j];
        }
        arr[0][j + 1] = tmpPos;
        arr[1][j + 1] = tmpImg;
        arr[2][j + 1] = tmpNome;
        arr[3][j + 1] = tmpCodice;
    }
    return arr;
}

function verificaErrori(){
	var posti_liberi_titolari=0,posti_liberi_panchina=0;
	var message = "";
	$('#message').empty();
	for(var i=1; i<=11; i++){
		if($('#avatar'+i+'_nome').text() == '')
			posti_liberi_titolari++;
	}
	
	if(posti_liberi_titolari > 0){
		//$('#campo').css('border','3px solid red');
		//$('#pulsanti').css('border','2px solid red');
		if(posti_liberi_titolari == 1)
			message += "* Manca "+posti_liberi_titolari+" titolare<img src='immagini/error2.png' valign='bottom' style='margin-left:3px'/>";
		else
			message += "* Mancano "+posti_liberi_titolari+" titolari<img src='immagini/error2.png' valign='bottom' style='margin-left:3px'/>";
	}
	else{
		//$('#campo').css('border','none');
		//$('#pulsanti').css('border','3px solid red');
	}
	
	for(var i=1; i<=numPanchina; i++){
		if($('#avatar'+(11+i)+'_nome').text() == '')
			posti_liberi_panchina++;
	}
	
	if(posti_liberi_panchina > 0){
		//$('#panchinaFull').css('border','3px solid red');
		//$('#pulsanti').css('border','2px solid red');
		if(message !="") message +="<br>";
		if(posti_liberi_panchina == 1)
			message += "* Manca "+posti_liberi_panchina+" riserva<img src='immagini/error2.png' valign='bottom' style='margin-left:3px'/>";
		else
			message += "* Mancano "+posti_liberi_panchina+" riserve<img src='immagini/error2.png' valign='bottom' style='margin-left:3px'/>";
	}
	else{
		//$('#panchinaFull').css('border','none');
		//$('#pulsanti').css('border','3px solid red');
	}
	$('#message').html("<div style='color:red;font-weight:bold'>"+message+"</div>");
	if(message != "") {
		$('input[name=salvaFormazione]').attr('disabled','disabled');
		$('input[name=salvaFormazione]').addClass('disabled');
		$('input[name=salvaFormazione]').css('cursor','default');
	}
	else {
		$('input[name=salvaFormazione]').removeAttr('disabled');
		$('input[name=salvaFormazione]').removeClass();
		$('input[name=salvaFormazione]').css('cursor','pointer');
	}
}

function azzeraFormazione(){
	$('.check').attr('src','immagini/checkbox_off.png');
	disponiModuloInCampo($('#modulo').get());
}
