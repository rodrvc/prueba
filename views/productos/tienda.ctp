<?= $this->element('menu'); ?>

<div class="wrapper">		
	<div class="cont-catalogo">
		<div class="seleccion">
			<span class="ordenar"> ver por: </span> 
			<a href="#" class="zonas" title="Zona Norte">Zona Norte</a>
			<a href="#" class="zonas" title="Zona Norte">Zona Centro</a>	
			<a href="#" class="zonas sin" title="Zona Norte">Zona Sur</a>		
		</div>
		
		<div class="tiendas">
			<div class="divisor">Zona Norte</div>
			<?
			$x = 0;
			foreach ( $tiendas as $tienda ):
				$x++;
				if ( $tienda['Tienda']['zona_id'] == 1 ): ?>
					<div class="t-bloques <?= ( ($x % 2) == 1 )? 'derec':''; ?>">
						<div class="imagen-s"><span class="nuevo">new</span><img src="img/tienda-skechers.png" alt="" class="img-tienda" /></div>
						<div class="info-s">
							<h2 class="n-tiendas">Mall Portal Florida Center</h2>
							<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
							<p class="otra-info">Local 2041, 2do Nivel<br />
							La Florida - Santiago<br />
							Fono: (2) 283 1351
							</p>
							<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
							<div class="compartir"></div>
						</div>
					</div>
				<? endif; ?>
			<? endforeach; ?>
			<? /*
			<div class="t-bloques derec">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			   */?>
			   
			<div class="divisor zona-c">Zona Centro</div>
			<div class="t-bloques">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" class="img-tienda" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			
			<div class="t-bloques derec">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			<div class="t-bloques">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" class="img-tienda" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			
			<div class="t-bloques derec">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			<div class="t-bloques">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" class="img-tienda" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			
			<div class="t-bloques derec">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			
			<div class="divisor zona-s">Zona Centro</div>
			<div class="t-bloques">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" class="img-tienda" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
			
			<div class="t-bloques derec">
				<div class="imagen-s"><img src="img/tienda-skechers.png" alt="" /></div>
				<div class="info-s">
					<h2 class="n-tiendas">Mall Portal Florida Center</h2>
					<h3 class="n-direccion">Av. Vicuña Mackenna #6100</h3>
					<p class="otra-info">Local 2041, 2do Nivel<br />
					La Florida - Santiago<br />
					Fono: (2) 283 1351
					</p>
					
					<a href="#" title="ver mapa" class="ver-map">Ver Mapa<span class="ir-flecha">&nbsp;</span></a>
					<div class="compartir"></div>
				</div>
			</div>
		</div>
	</div>
</div>