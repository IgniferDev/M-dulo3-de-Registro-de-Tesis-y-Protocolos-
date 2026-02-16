<?php
include 'header.php';
require_once '../backend/funciones_tesis.php';

$edit = false;
$tesis = [];

if (isset($_GET['edit'])) {
    $tesis = obtenerTesisPorClave($_GET['edit']);
    $edit = true;
}
?>

<h3><?= $edit ? 'Editar Tesis' : 'Registrar Tesis' ?></h3>

<?php if (isset($_GET['msg'])): ?>
<p class="<?= $_GET['ok'] ? 'success' : 'error' ?>">
<?= htmlspecialchars($_GET['msg']) ?>
</p>
<?php endif; ?>

<form method="POST" action="../backend/tesis_controller.php">

<input type="hidden" name="original" value="<?= $tesis['clave'] ?? '' ?>">

Clave:<br>
<input name="clave" value="<?= $tesis['clave'] ?? '' ?>" required><br><br>

Título:<br>
<textarea name="titulo"><?= $tesis['titulo'] ?? '' ?></textarea><br><br>

Matrícula:<br>
<input name="matricula" value="<?= $tesis['matricula'] ?? '' ?>"><br><br>

Director:<br>
<input name="director" value="<?= $tesis['director'] ?? '' ?>"><br><br>

Co-director:<br>
<input name="codirector" value="<?= $tesis['codirector'] ?? '' ?>"><br><br>

Avance:<br>
<input type="number" name="avance" value="<?= $tesis['avance'] ?? '' ?>"><br><br>

Estatus:<br>
<select name="estatus">
<?php foreach (['Registrado','En Revisión','Aprobado','Defendido'] as $e): ?>
<option <?= (($tesis['estatus'] ?? '')==$e)?'selected':'' ?>><?= $e ?></option>
<?php endforeach; ?>
</select><br><br>

<button type="submit" name="<?= $edit ? 'editar' : 'crear' ?>">
<?= $edit ? 'Actualizar' : 'Guardar' ?>
</button>

<a href="tesis_listado.php">
<button type="button">Cancelar</button>
</a>

</form>

<?php include 'footer.php'; ?>
