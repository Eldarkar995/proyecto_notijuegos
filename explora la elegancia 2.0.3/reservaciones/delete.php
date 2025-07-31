//delete.php
<?php
require 'db.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM reservaciones WHERE id = $delete_id";

    if (mysqli_query($conn, $delete_query)) {
        header("Location: index.php?mensaje=¡Reservacion eliminada exitosamente!");
    } else {
        echo "Error al eliminar la reservacion: " . mysqli_error($conn);
    }
}
?>