<div id="modalDireccion" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" rel="titulo"></h4>
            </div>
            <div class="modal-body">

                <?= $this->Form->create('Direccion', array('action' => 'edit', 'class' => 'form-horizontal','class' => 'form-horizontal','inputDefaults' => array('class' => 'form-control requerido','div' => false,'label' => false))); ?>
                <div class="alert alert-warning hidden"><i class="fa fa-warning"></i> Se ha producido un problema</div>
                <div class="alert alert-success hidden"><i class="fa fa-warning"></i> Registro guardado exitosamente</div>
                <?= $this->Form->hidden('id'); ?>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Calle:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('calle', array('maxlength'=>'19')); ?>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Numero:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('numero', array('maxlength'=>'5')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Depto:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('depto', array('class' => 'form-control','maxlength'=>'24')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Región:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('region_id', array('empty' => '-- Seleccione una región', 'options' => $regiones)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Comuna:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('comuna_id', array('empty' => '-- Seleccione una región', 'options' => $comunas)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Código postal:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('codigo_postal'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Teléfono:
                    </label>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->input('telefono', array('size' => 12)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Celular:
                    </label>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $this->Form->input('celular', array('class' => 'form-control', 'size' => 12)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        Guardar dirección como:
                    </label>
                    <div class="col-sm-7">
                        <?= $this->Form->input('nombre'); ?>
                    </div>
                </div>
                <a href="#" class="azul btn btn-primary btn-block btn-lg" rel="btnGuardarDireccion">Agregar nueva direccion</a>
                <?= $this->Form->end(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="container">
    <h1 class="titulo-categoria">Devolucion</h1>
    <div class="panel panel-skechers">
        <div class="panel-body">
            <div class="cont-carro">
                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-sm">
                            <h4>Estimad@ <?= $authUser['nombre'].' '.$authUser['apellido_paterno'].' '.$authUser['apellido_materno']; ?>.</h4>
                            <p>La información que nos proporcionarás es privada y no será utilizada sin tu consentimiento. Si deseas cerrar tu perfil, ponte en contacto con nostros al correo <a href="mailto: ventas@skechers.com">ventas@skechers.com</a> y lo borraremos por ti.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="tab-content tab-content-skechers">
                            <div class="tab-pane active">
                                <legend class="texto-info">Motivos de devolucion</legend>
                            </div>
                            <div>
                                <p>completar formulario</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


