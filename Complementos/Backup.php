<?php
// ================================
// Configuración de la base de datos
// ================================
$Host = 'localhost';
$User = 'root';
$Pass = ''; // tu contraseña si aplica
$DB   = 'bd_risingfarms';

// ================================
// Carpeta base de backups en C:\backups
// ================================
$backup_base_dir = "C:\\backups\\";

// Subcarpeta por fecha: C:\backups\2025-10-23\
$fecha = date("Y-m-d");
$backup_dir = $backup_base_dir . $fecha . "\\";

// Crear carpeta si no existe
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

// ================================
// Nombre del archivo con fecha y hora
// ================================
$backup_file = $backup_dir . "BD_RC_" . date("Ymd_His") . ".sql";

// ================================
// Ruta completa de mysqldump (XAMPP en Windows)
// ================================
$mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

// ================================
// Comando de backup con vistas, triggers y procedimientos
// ================================
$command = "\"$mysqldump\" --host=$Host --user=$User --password=$Pass --routines --triggers --events $DB > \"$backup_file\"";

// ================================
// Ejecutar el backup
// ================================
exec($command, $output, $return_var);

if ($return_var === 0) {
    echo "✅ Backup exitoso: $backup_file\n";
} else {
    echo "❌ Error al hacer backup. Código: $return_var\n";
    echo "Comando ejecutado: $command\n";
}

// ================================
// Opcional: borrar backups de más de 7 días
// ================================
$folders = glob($backup_base_dir . "*", GLOB_ONLYDIR);
$hoy = time();

foreach ($folders as $folder) {
    if ($hoy - filemtime($folder) > 7 * 24 * 60 * 60) { // más de 7 días
        array_map('unlink', glob("$folder\\*.sql")); // borrar archivos dentro
        rmdir($folder); // borrar carpeta
    }
}
?>
