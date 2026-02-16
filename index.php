<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Práctica 2 – Servicios Web</title>

<style>
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(135deg, #003366, #0055a5);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card {
    background: white;
    width: 450px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    text-align: center;
}

.card h1 {
    margin-top: 0;
    color: #003366;
}

.card h2 {
    font-size: 18px;
    color: #555;
    margin-bottom: 30px;
}

.btn {
    display: block;
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    background: #003366;
    color: white;
    text-decoration: none;
    font-size: 16px;
    border-radius: 6px;
    transition: background 0.3s;
}

.btn:hover {
    background: #0055a5;
}

.footer {
    margin-top: 25px;
    font-size: 13px;
    color: #777;
}
</style>

</head>
<body>

<div class="card">
    <h1>Práctica 2</h1>
    <h2>Manejo de Información con XML, XPath y XQuery</h2>

    <a class="btn" href="frontend/tesis_form.php">➕ Registrar Tesis</a>
    <a class="btn" href="frontend/tesis_listado.php">📄 Ver Listado de Tesis</a>

    <div class="footer">
        <p>Módulo 3 – Registro de Tesis y Protocolos</p>
        <p>Equipo 3</p>
    </div>
</div>

</body>
</html>
