<?php
require_once __DIR__ . '/../../includes/funciones.php';
iniciarSesion();
$rolSesion=$_SESSION['rol']??'';
$nombreSesion=$_SESSION['nombre']??'Usuario';
$path=$_SERVER['PHP_SELF']??'';
$inicio=dashboardPorRol($rolSesion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($tituloPagina ?? 'Sistema de Tutorías - UPDS') ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Hoja de Estilos Personalizada -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php if(isset($_SESSION['id_usuario'])): ?><nav class="navbar navbar-expand-xl navbar-dark navbar-custom shadow-sm sticky-top"><div class="container-fluid px-3 px-lg-4">
<a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?=$inicio?>"><span class="brand-mark"><i class="bi bi-mortarboard-fill text-warning"></i></span><span>Sistema de Tutorías <small class="opacity-75">UPDS</small></span></a>
<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navbarMain"><ul class="navbar-nav me-auto mb-2 mb-xl-0">
<?php if($rolSesion==='administrador'): ?><li class="nav-item"><a class="nav-link <?=$path==='/controllers/dashboard.php'?'active':''?>" href="/controllers/dashboard.php"><i class="bi bi-grid-1x2 me-1"></i>Inicio</a></li><li><span class="nav-section d-none d-xl-block">Gestión</span></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'usuarios')?'active':''?>" href="/controllers/usuarios_listar.php"><i class="bi bi-people me-1"></i>Usuarios</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'estudiantes')?'active':''?>" href="/controllers/estudiantes_listar.php"><i class="bi bi-person-badge me-1"></i>Estudiantes</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'tutores')?'active':''?>" href="/controllers/tutores_listar.php"><i class="bi bi-person-video3 me-1"></i>Tutores</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'carreras')?'active':''?>" href="/controllers/carreras_listar.php"><i class="bi bi-mortarboard me-1"></i>Carreras</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'materias')?'active':''?>" href="/controllers/materias_listar.php"><i class="bi bi-journal-bookmark me-1"></i>Materias</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'disponibilidad')?'active':''?>" href="/controllers/disponibilidad_listar.php"><i class="bi bi-calendar-week me-1"></i>Horarios</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'tutorias')?'active':''?>" href="/controllers/tutorias_listar.php"><i class="bi bi-calendar-check me-1"></i>Tutorías</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'evaluaciones')?'active':''?>" href="/controllers/evaluaciones_listar.php"><i class="bi bi-star me-1"></i>Evaluaciones</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'reportes')?'active':''?>" href="/controllers/reportes.php"><i class="bi bi-bar-chart-line me-1"></i>Reportes</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'auditoria')?'active':''?>" href="/controllers/auditoria_listar.php"><i class="bi bi-shield-check me-1"></i>Auditoría</a></li>
<?php elseif($rolSesion==='tutor'): ?><li class="nav-item"><a class="nav-link <?=$path==='/views/tutor/panel.php'?'active':''?>" href="/views/tutor/panel.php"><i class="bi bi-grid-1x2 me-1"></i>Inicio</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'tutorias')?'active':''?>" href="/controllers/tutorias_listar.php"><i class="bi bi-inbox me-1"></i>Solicitudes y sesiones</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'disponibilidad')?'active':''?>" href="/controllers/disponibilidad_listar.php"><i class="bi bi-calendar-week me-1"></i>Disponibilidad</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'tutor_mis_materias')?'active':''?>" href="/controllers/tutor_mis_materias.php"><i class="bi bi-journals me-1"></i>Mis materias</a></li>
<?php elseif($rolSesion==='estudiante'): ?><li class="nav-item"><a class="nav-link <?=$path==='/views/estudiante/panel.php'?'active':''?>" href="/views/estudiante/panel.php"><i class="bi bi-grid-1x2 me-1"></i>Inicio</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'tutorias')?'active':''?>" href="/controllers/tutorias_listar.php"><i class="bi bi-calendar-check me-1"></i>Mis tutorías</a></li><li class="nav-item"><a class="nav-link <?=str_contains($path,'evaluaciones')?'active':''?>" href="/controllers/evaluaciones_listar.php"><i class="bi bi-star me-1"></i>Evaluaciones</a></li><?php endif; ?></ul>
<div class="d-flex align-items-center gap-2"><a class="text-white text-decoration-none d-none d-md-flex align-items-center gap-2 px-2" href="/controllers/perfil.php"><span class="brand-mark" style="width:34px;height:34px"><i class="bi bi-person"></i></span><span><small class="d-block text-white-50"><?=e($rolSesion)?></small><strong style="font-size:.88rem"><?=e($nombreSesion)?></strong></span></a><a href="/controllers/logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Salir</a></div>
</div></div></nav><?php endif; ?><main class="container-fluid px-3 px-lg-4 py-4 flex-grow-1">
