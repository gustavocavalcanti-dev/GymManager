<?php $basePath=defined('BASE_PATH')?BASE_PATH:''; include dirname(__DIR__).'/layouts/header.php'; ?>
<div class="page-head"><div><h1 class="page-title">Editar Professor</h1><p class="subtitle">Atualize os dados profissionais e de contato.</p></div></div>
<div class="card"><?php if(!empty($erros)):?><div class="alert alert-danger"><ul><?php foreach($erros as $erro):?><li><?=htmlspecialchars((string)$erro)?></li><?php endforeach;?></ul></div><?php endif;?>
<form action="<?=$basePath?>/professores/update" method="post"><?=Security::campoCSRF()?><input type="hidden" name="id" value="<?=(int)$professor['id']?>">
<div class="form-grid">
<div class="form-group"><label class="form-label">Nome completo</label><input class="form-control" name="nome" required value="<?=htmlspecialchars((string)($professor['nome']??''))?>"></div>
<div class="form-group"><label class="form-label">CPF</label><input class="form-control" name="cpf" maxlength="14" required value="<?=htmlspecialchars((string)($professor['cpf']??''))?>"></div>
<div class="form-group"><label class="form-label">E-mail</label><input class="form-control" type="email" name="email" required value="<?=htmlspecialchars((string)($professor['email']??''))?>"></div>
<div class="form-group"><label class="form-label">Telefone</label><input class="form-control" name="telefone" value="<?=htmlspecialchars((string)($professor['telefone']??''))?>"></div>
<div class="form-group"><label class="form-label">Especialidade</label><input class="form-control" name="especialidade" value="<?=htmlspecialchars((string)($professor['especialidade']??''))?>"></div>
<div class="form-group"><label class="form-label">CREF</label><input class="form-control" name="cref" value="<?=htmlspecialchars((string)($professor['cref']??''))?>"></div>
<div class="form-group"><label class="form-label">Status</label><select class="form-control" name="status"><option value="ativo" <?=($professor['status']??'')==='ativo'?'selected':''?>>Ativo</option><option value="inativo" <?=($professor['status']??'')==='inativo'?'selected':''?>>Inativo</option></select></div>
</div>
<div class="form-actions"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="<?=$basePath?>/professores">Cancelar</a></div></form></div>
<?php include dirname(__DIR__).'/layouts/footer.php'; ?>
