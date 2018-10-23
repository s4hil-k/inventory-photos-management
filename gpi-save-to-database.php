<?
include_once('gpi-db-connect.php');
$gpi_sku = $_POST['gpi_sku'];
$gpi_original_filename = $_FILES['uploadfile']['name'];
$gpi_original_filename_extension = substr($gpi_original_filename, strpos($gpi_original_filename, ".") + 1);    
$gpi_resized_filename = $_POST['gpi_sku'] . "." . $gpi_original_filename_extension;

$gpi_add_sku = "INSERT INTO crush_inventory_photos (gpi_sku, gpi_original_filename, gpi_resized_filename) VALUES ('$gpi_sku', '$gpi_original_filename', '$gpi_resized_filename')";

if ( $gpi_database_connection->query($gpi_add_sku) === TRUE ) {
}


if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

  if ( $gpi_add_sku ) {
    echo '
      <div class="alert alert-success" role="alert">
        <p>The image was successfully saved to the database.</p>
      </div>
    ';
  } else {
    echo '
      <div class="alert alert-danger" role="alert">
        <p>The image was not saved to the database.</p>
      </div>
    ';
  }

}
?>