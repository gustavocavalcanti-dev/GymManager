<?php $basePath=defined('BASE_PATH')?BASE_PATH:''; include dirname(__DIR__).'/layouts/header.php'; ?>
<div class="page-head"><div><h1 class="page-title">Cadastrar Novo Plano</h1><p class="subtitle">Defina valor, duração e benefícios do plano.</p></div></div>
<div class="card"><?php if(!empty($erros)):?><div class="alert alert-danger"><ul><?php foreach($erros as $erro):?><li><?=htmlspecialchars((string)$erro)?></li><?php endforeach;?></ul></div><?php endif;?>
<form action="<?=$basePath?>/planos/store" method="post"><?=Security::campoCSRF()?><div class="form-grid">
<div class="form-group"><label class="form-label">Nome</label><input class="form-control" name="nome" required value="<?=htmlspecialchars((string)($dados['nome']??''))?>"></div>
<div class="form-group"><label class="form-label">Valor (R$)</label><input class="form-control" type="number" min="0" step="0.01" name="valor" required value="<?=htmlspecialchars((string)($dados['valor']??''))?>"></div>
<div class="form-group"><label class="form-label">Duração em meses</label><input class="form-control" type="number" min="1" name="duracao_meses" required value="<?=htmlspecialchars((string)($dados['duracao_meses']??1))?>"></div>
<div class="form-group"><label class="form-label">Status</label><select class="form-control" name="status"><option value="ativo" <?=($dados['status']??'')==='ativo'?'selected':''?>>Ativo</option><option value="inativo" <?=($dados['status']??'')==='inativo'?'selected':''?>>Inativo</option></select></div>
<div class="form-group" style="grid-column:1/-1"><label class="form-label">Descrição / benefícios</label><textarea class="form-control" rows="5" name="descricao"><?=htmlspecialchars((string)($dados['descricao']??''))?></textarea></div>
</div><div class="form-actions"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="<?=$basePath?>/planos">Cancelar</a></div></form></div>
<?php include dirname(__DIR__).'/layouts/footer.php'; ?>
