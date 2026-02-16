<?php
include 'header.php';
require_once '../backend/funciones_tesis.php';

$tesis = obtenerTesis();
?>

<h3>Listado de Tesis Registradas</h3>

<table>
<tr>
<th>Clave</th>
<th>Título</th>
<th>Matrícula</th>
<th>Director</th>
<th>Línea</th>
<th>Avance</th>
<th>Modalidad</th>
<th>Estatus</th>
<th>Acciones</th>
</tr>

<?php foreach ($tesis as $t): ?>
<tr>
<td><?= $t['clave'] ?></td>
<td><?= $t['titulo'] ?></td>
<td><?= $t['matricula'] ?></td>
<td><?= $t['director'] ?></td>
<td><?= $t['linea'] ?></td>
<td><?= $t['avance'] ?>%</td>
<td><?= $t['modalidad'] ?></td>
<td><?= $t['estatus'] ?></td>
<td>
<a href="tesis_form.php?edit=<?= $t['clave'] ?>">✏️ Editar</a> |
<a href="../backend/tesis_controller.php?del=<?= $t['clave'] ?>"
onclick="return confirm('¿Eliminar?')">🗑 Eliminar</a>
</td>

</tr>
<?php endforeach; ?>
</table>

<?php include 'footer.php'; ?>
