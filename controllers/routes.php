<?php
// =============== CORTAR RUTA ===============
Router::connect('/', array('controller' => 'productos', 'action' => 'inicio'));
Router::connect('/registro', array('controller' => 'usuarios', 'action' => 'add'));
Router::connect('/perfil', array('controller' => 'usuarios', 'action' => 'perfil_datos'));
Router::connect('/historial', array('controller' => 'usuarios', 'action' => 'historial'));
Router::connect('/informacion_corporativa', array('controller' => 'pages', 'action' => 'display','corporativa'));
Router::connect('/faq', array('controller' => 'pages', 'action' => 'display','faq'));
Router::connect('/catalogo/*', array('controller' => 'productos', 'action' => 'catalogo'));
Router::connect('/detalle/*', array('controller' => 'productos', 'action' => 'view'));
Router::connect('/sale', array('controller' => 'productos', 'action' => 'sale'));
Router::connect('/cyber', array('controller' => 'productos', 'action' => 'cybermonday2016'));
Router::connect('/cyberday', array('controller' => 'productos', 'action' => 'cybermonday2016'));
Router::connect('/blackfriday', array('controller' => 'productos', 'action' => 'cybermonday2016'));


Router::connect('/cyber/women', array('controller' => 'productos', 'action' => 'cybermonday2016','mujer', 'url' => array('categoria' => 'mujer')));
Router::connect('/cyber/men', array('controller' => 'productos', 'action' => 'cybermonday2016', 'hombre', 'url' => array('categoria' => 'hombre')));
Router::connect('/cyber/girls', array('controller' => 'productos', 'action' => 'cybermonday2016', 'nina', 'url' => array('categoria' => 'nina')));
Router::connect('/cyber/boys', array('controller' => 'productos', 'action' => 'cybermonday2016', 'nino', 'url' => array('categoria' => 'nino')));

Router::connect('/blackfriday/women', array('controller' => 'productos', 'action' => 'cybermonday2016','mujer', 'url' => array('categoria' => 'mujer')));
Router::connect('/blackfriday/men', array('controller' => 'productos', 'action' => 'cybermonday2016', 'hombre', 'url' => array('categoria' => 'hombre')));
Router::connect('/blackfriday/girls', array('controller' => 'productos', 'action' => 'cybermonday2016', 'nina', 'url' => array('categoria' => 'nina')));
Router::connect('/blackfriday/boys', array('controller' => 'productos', 'action' => 'cybermonday2016', 'nino', 'url' => array('categoria' => 'nino')));


Router::connect('/semanasanta', array('controller' => 'productos', 'action' => 'cybermonday2016'));
Router::connect('/semanasanta/women', array('controller' => 'productos', 'action' => 'cybermonday2016','mujer', 'url' => array('categoria' => 'mujer')));
Router::connect('/semanasanta/men', array('controller' => 'productos', 'action' => 'cybermonday2016', 'hombre', 'url' => array('categoria' => 'hombre')));
Router::connect('/semanasanta/girls', array('controller' => 'productos', 'action' => 'cybermonday2016', 'nina', 'url' => array('categoria' => 'nina')));
Router::connect('/semanasanta/boys', array('controller' => 'productos', 'action' => 'cybermonday2016', 'nino', 'url' => array('categoria' => 'nino')));

// =============== CATALOGO NORMAL ===============
//	- MUJER
Router::connect('/women', array('controller' => 'productos', 'action' => 'catalogo', 'mujer'));
Router::connect('/women/talla/*', array('controller' => 'productos', 'action' => 'tallas', 'mujer'));
Router::connect('/women/color/*', array('controller' => 'productos', 'action' => 'color', 'mujer'));
Router::connect('/new-women', array('controller' => 'productos', 'action' => 'catalogo', 'mujer','url' => array('group' => 'new')));
//	- HOMBREF
Router::connect('/men', array('controller' => 'productos', 'action' => 'catalogo', 'hombre'));
Router::connect('/men/talla/*', array('controller' => 'productos', 'action' => 'tallas', 'hombre'));
Router::connect('/men/color/*', array('controller' => 'productos', 'action' => 'color', 'hombre'));
Router::connect('/new-men', array('controller' => 'productos', 'action' => 'catalogo', 'hombre','url' => array('group' => 'new')));
//	- NIÑO
Router::connect('/boys', array('controller' => 'productos', 'action' => 'catalogo', 'nino'));
Router::connect('/boys/talla/*', array('controller' => 'productos', 'action' => 'tallas', 'nino'));
Router::connect('/boys/color/*', array('controller' => 'productos', 'action' => 'color', 'nino'));
Router::connect('/boys/conluces', array('controller' => 'productos', 'action' => 'catalogo', 'nino', 'url' => array('style' => 'con_luces')));

//	- NIÑA
Router::connect('/girls', array('controller' => 'productos', 'action' => 'catalogo', 'nina'));
Router::connect('/girls/talla/*', array('controller' => 'productos', 'action' => 'tallas', 'nina'));
Router::connect('/girls/color/*', array('controller' => 'productos', 'action' => 'color', 'nina'));

// =============== OUTLET ===============
Router::connect('/outlet', array('controller' => 'productos', 'action' => 'outlet'));
Router::connect('/outlet/mujer', array('controller' => 'productos', 'action' => 'outlet', 'url' => array('categoria' => 'mujer')));
Router::connect('/outlet/women', array('controller' => 'productos', 'action' => 'outlet', 'url' => array('categoria' => 'mujer')));
Router::connect('/outlet/men', array('controller' => 'productos', 'action' => 'outlet', 'url' => array('categoria' => 'hombre')));
Router::connect('/outlet/boys', array('controller' => 'productos', 'action' => 'outlet', 'url' => array('categoria' => 'nino')));
Router::connect('/outlet/girls', array('controller' => 'productos', 'action' => 'outlet', 'url' => array('categoria' => 'nina')));

// =============== PERFORMANCE ===============
Router::connect('/performance', array('controller' => 'productos', 'action' => 'grupos', 'performance'));
Router::connect('/primavera', array('controller' => 'productos', 'action' => 'grupos', 'coleccion_10'));
//Router::connect('/semanasanta', array('controller' => 'productos', 'action' => 'grupos', 'semanasanta2'));

// =============== APPAREL (ropa) ===============
Router::connect('/ropa', array('controller' => 'productos', 'action' => 'ropa'));
Router::connect('/ropa/women', array('controller' => 'productos', 'action' => 'ropa', 'url' => array('categoria' => 'women')));

Router::connect('/ropa/men', array('controller' => 'productos', 'action' => 'ropa', 'url' => array('categoria' => 'men')));

Router::connect('/mujer/appael', array('controller' => 'productos', 'action' => 'ropa', 'url' => array('categoria' => 'women', 'style' => 'ropa_q2')));
Router::connect('/hombre/appael', array('controller' => 'productos', 'action' => 'ropa', 'url' => array('categoria' => 'men', 'style' => 'ropa_q2')));




// =============== ESCOLAR ===============
Router::connect('/escolares', array('controller' => 'productos', 'action' => 'escolar'));
Router::connect('/escolares/boys', array('controller' => 'productos', 'action' => 'escolar','url' => array('gender' => 'boys')));
Router::connect('/escolares/girls', array('controller' => 'productos', 'action' => 'escolar','url' => array('gender' => 'girls')));
Router::connect('/escolares/memoryfoam', array( 'controller' => 'productos', 'action' => 'escolar','url' => array('style' => 'memory_foam')));


/*
 *	=============== PERSONALIZADOS ===============
 *	Los links se encuentran agrupados en:
 *	-	atajos directos, generalmente sin perfilamiento por genero
 *	-	por genero:
 *		-	mujer
 *		-	hombre
 *		-	kids
 *		-	niño
 *		-	niña
 *	Lo anterior, ordenados alfabeticamente...
 */
Router::connect('/botas', array('controller' => 'productos', 'action' => 'grupos', 'botas2016'));
Router::connect('/golf', array('controller' => 'productos', 'action' => 'grupos', 'golf'));

Router::connect('/demistyle', array('controller' => 'productos', 'action' => 'grupos', 'demistyle'));
Router::connect('/GOflex', array('controller' => 'productos', 'action' => 'grupos', 'go_flex'));
Router::connect('/gorun', array('controller' => 'productos', 'action' => 'grupos', 'gorun'));
Router::connect('/gorun/*', array('controller' => 'productos', 'action' => 'grupos', 'gorun'));
Router::connect('/gorun2', array('controller' => 'productos', 'action' => 'categoria', 'gorun2'));
Router::connect('/GOrunUltraR', array('controller' => 'productos', 'action' => 'grupos', 'gorun_ultra_r'));
Router::connect('/gowalk', array('controller' => 'productos', 'action' => 'grupos', 'gowalk'));
Router::connect('/GOwalk/*', array('controller' => 'productos', 'action' => 'grupos', 'gowalk'));
Router::connect('/GOwalk3', array('controller' => 'productos', 'action' => 'grupos', 'gowalk3'));
Router::connect('/GOwalk3-men', array('controller' => 'productos', 'action' => 'grupos', 'men_gowalk3'));
Router::connect('/lonasyalpargatas', array('controller' => 'productos', 'action' => 'grupos', 'LONAS_Y_ALPARGATAS'));
Router::connect('/memoryfoam', array('controller' => 'productos', 'action' => 'grupos', 'memory_foam'));
Router::connect('/MemoryFoam', array('controller' => 'productos', 'action' => 'grupos', 'memory_foam'));
Router::connect('/memoryfoamKIDS', array('controller' => 'productos', 'action' => 'grupos', 'kids%2Cmemory_foam'));
Router::connect('/kidsMemoryFoam', array('controller' => 'productos', 'action' => 'grupos', 'memorykids'));

Router::connect('/Resistance', array('controller' => 'productos', 'action' => 'grupos', 'resistance'));
Router::connect('/sport', array('controller' => 'productos', 'action' => 'grupos', 'sport'));
Router::connect('/StretchWeave', array('controller' => 'productos', 'action' => 'grupos', 'stretch_weave'));
Router::connect('/twinkle-toes', array('controller' => 'productos', 'action' => 'grupos', 'twinkle-toes', "Girls' Twinkle Toes"));
Router::connect('/urban-men', array('controller' => 'productos', 'action' => 'grupos', 'urban_men'));
Router::connect('/urban-men', array('controller' => 'productos', 'action' => 'grupos', 'sale_nov17'));
Router::connect('/summer', array('controller' => 'productos', 'action' => 'grupos', 'summer_2017'));
Router::connect('/women/sale', array('controller' => 'productos', 'action' => 'grupos', 'women_sale'));
Router::connect('/men/sale', array('controller' => 'productos', 'action' => 'grupos', 'men_sale'));
Router::connect('/boys/sale', array('controller' => 'productos', 'action' => 'grupos', 'nino_sale'));
Router::connect('/girls/sale', array('controller' => 'productos', 'action' => 'grupos', 'nina_sale'));
Router::connect('/fall', array('controller' => 'productos', 'action' => 'grupos', '2018_q1'));
Router::connect('/gorun6', array('controller' => 'productos', 'action' => 'grupos', 'run6'));
Router::connect('/dlites', array('controller' => 'productos', 'action' => 'grupos', 'dlites'));
Router::connect('/menurban', array('controller' => 'productos', 'action' => 'grupos', 'menurban'));
Router::connect('/luces', array('controller' => 'productos', 'action' => 'grupos', 'luces2'));
Router::connect('/running', array('controller' => 'productos', 'action' => 'grupos', 'running'));









//	- MUJER
Router::connect('/women/burst', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'burst')));
Router::connect('/women/goflex', array('controller' => 'productos', 'action' => 'grupos', 'go_flex'));
Router::connect('/women/gorunride5', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'gorunride5')));	// 2016-08-19
Router::connect('/women/gowalk', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'gowalk_link', 'title' => urlencode("gowalk"))));	// 2016-08-19
Router::connect('/women/gowalk4', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'gowalk4', 'title' => urlencode("gowalk 4"))));
Router::connect('/women/gowalksport', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'go_sport', 'title' => urlencode("gowalk sport"))));
Router::connect('/women/memoryfoam', array('controller' => 'productos', 'action' => 'grupos', 'memory_foam%2Csport', 'url' => array('categoria' => 'mujer')));	// 2016-08-19
Router::connect('/women/moderncomfort', array('controller' => 'productos', 'action' => 'grupos', 'modern_comfort', 'url' => array('categoria' => 'mujer')));
Router::connect('/women/originals', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'retro')));	// 2016-08-19
Router::connect('/women/performance', array('controller' => 'productos', 'action' => 'grupos', 'performance', 'url' => array('categoria' => 'mujer')));
Router::connect('/women/relaxedfit', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'relaxed_fit')));
Router::connect('/women/retro', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'retro')));
Router::connect('/women/running', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'running')));
Router::connect('/women/sandalias', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'sandalias')));
Router::connect('/women/sport', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'sport')));
Router::connect('/mujer/sport', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'sport_q3')));

Router::connect('/women/sport-memoryfoam', array('controller' => 'productos', 'action' => 'grupos', 'WMMFR' , "Womens’ Sport Memory Foam"));
Router::connect('/women/ultraR', array('controller' => 'productos', 'action' => 'grupos', 'gorun_ultra_r', 'url' => array('categoria' => 'mujer')));
Router::connect('/mujer/slipon', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'slip_on')));
Router::connect('/women/street', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'street')));
Router::connect('/women/you', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'you')));
Router::connect('/women/chunky', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'chunky')));
Router::connect('/women/gogolf', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'golf')));
Router::connect('/women/bobs', array('controller' => 'productos', 'action' => 'grupos', 'bob'));
Router::connect('/women/flexappeal', array('controller' => 'productos', 'action' => 'grupos', 'flex_appeal2', 'url' => array('categoria' => 'mujer')));
Router::connect('/women/skechknit', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'skech_knit')));
Router::connect('/women/heritage', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'heritage', 'otros' => 'dlites')));
Router::connect('/heritage', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'heritage', 'otros' => 'dlites')));

Router::connect('/women/dlites', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'dlites')));





//	- HOMBRE
Router::connect('/men/sandalias', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'sandalias')));

Router::connect('/men/bototos', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'bota_botin')));
Router::connect('/men/burst', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'burst')));	// 2016-08-19
Router::connect('/men/gogolf', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'golf')));
Router::connect('/men/gorunride5', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'gorunride5')));	// 2016-08-19
Router::connect('/men/gowalk', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'gowalk_link', 'title' => urlencode("gowalk"))));
Router::connect('/men/marknason', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'mark_nason')));
Router::connect('/winter', array('controller' => 'productos', 'action' => 'grupos', 'winter_2018'));
Router::connect('/spring', array('controller' => 'productos', 'action' => 'grupos','spring_2018'));

Router::connect('/men/originals', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'retro')));	// 2016-08-19
Router::connect('/men/performance', array('controller' => 'productos', 'action' => 'grupos', 'performance', 'url' => array('categoria' => 'hombre')));
Router::connect('/men/relaxedfit', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'relaxed_fit')));
Router::connect('/men/retro', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'retro')));
Router::connect('/men/running', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'running')));
Router::connect('/men/sport', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'sport')));
Router::connect('/hombre/sport', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'sport_q3')));

Router::connect('/men/sport-memoryfoam', array('controller' => 'productos', 'action' => 'grupos', 'PMMFT',"Mens’ Sport Memory Foam"));
Router::connect('/men/ultraR', array('controller' => 'productos', 'action' => 'grupos', 'gorun_ultra_r', 'url' => array('categoria' => 'hombre')));

//	- KIDS
Router::connect('/kids', array('controller' => 'productos', 'action' => 'grupos', 'kids'));
Router::connect('/kids/sandalias', array('controller' => 'productos', 'action' => 'grupos', 'kids_sandalias'));
Router::connect('/kids/sport-memoryfoam', array('controller' => 'productos', 'action' => 'grupos', 'kids_sport_memoryfoam', "Kids' Memory Foam"));
Router::connect('/kids/memoryfoam', array('controller' => 'productos', 'action' => 'grupos', 'kids_memory_foam', "Kids' Memory Foam"));

Router::connect('/kids/slipon', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'slip_on')));
// Router::connect('/kids/sport-memoryfoam/boys', array('controller' => 'productos', 'action' => 'grupos', 'kids-sport-memoryfoam', 'url' => array('categoria' => 'nino')));
// Router::connect('/kids/sport-memoryfoam/girls', array('controller' => 'productos', 'action' => 'grupos', 'kids-sport-memoryfoam', 'url' => array('categoria' => 'nina')));

//	- NIÑO
Router::connect('/boys/starwars', array('controller' => 'productos', 'action' => 'grupos', 'starwars'));

Router::connect('/girls/energylights', array('controller' => 'productos', 'action' => 'grupos', 'e_lights_g'));
Router::connect('/boys/energylights', array('controller' => 'productos', 'action' => 'grupos', 'e_lights_b'));
Router::connect('/energylights', array('controller' => 'productos', 'action' => 'grupos', 'e_lights'));



//	- NIÑA
Router::connect('/girls/botines', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'botin')));
Router::connect('/girls/conluces', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'luces')));
Router::connect('/girls/gowalk', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'gowalk')));
Router::connect('/girls/skechair', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'skech_air')));
Router::connect('/girls/skech_air', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'skech_air')));
Router::connect('/girls/twinkletoes', array('controller' => 'productos', 'action' => 'catalogo', 'nina', 'url' => array('style' => 'twinkle_toes')));
Router::connect('/escolares/blancas', array('controller' => 'productos', 'action' => 'grupos', 'escolar_blanco'));


Router::connect('/women/gowalk4', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'gowalk4','title' => urlencode("gowalk 4"))));
Router::connect('/women/burst', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'burst')));
Router::connect('/women/skechair', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'skechair')));
Router::connect('/women/gorun400', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'gorun400')));
Router::connect('/men/gorun400', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'gorun400')));
Router::connect('/women/skech-knit', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'skech_knit')));
Router::connect('/men/memoryfoam', array('controller' => 'productos', 'action' => 'catalogo', 'hombre', 'url' => array('style' => 'memory_foam')));
Router::connect('/women/botasybotines', array('controller' => 'productos', 'action' => 'catalogo', 'mujer', 'url' => array('style' => 'bota_botin')));




// ADMIN
$admin = '4dm1n481';
Router::connect("/{$admin}", array('controller' => 'compras', 'action' => 'index', 'admin' => true));

