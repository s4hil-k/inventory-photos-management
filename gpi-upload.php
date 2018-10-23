<?php

// ----------------------------------------- start edit here ---------------------------------------------//
$gpi_sku = $_POST['gpi_sku'];
$script_location = "https://www.ipullupull.com/inventory-photos/"; // location of the script
$maxlimit = 1048576; // max image limit
$original_folder = "original-images"; // folder where to save images
$resized_folder = "resized-images"; // folder where to save images

// requirements

$minwidth = 113; // minim width
$minheight = 85; // minim height
$maxwidth = 2560; // maxim width
$maxheight = 1920; // maxim height

// allowed extensions

$extensions = array(
	'.png',
	'.gif',
	'.jpg',
	'.jpeg',
	'.PNG',
	'.GIF',
	'.JPG',
	'.JPEG'
);

// ----------------------------------------- end edit here ---------------------------------------------//
// check that we have a file

if ((!empty($_FILES["uploadfile"])) && ($_FILES['uploadfile']['error'] == 0)) {

	// check extension
	$extension = strrchr($_FILES['uploadfile']['name'], '.');
	if (!in_array($extension, $extensions)) {
		echo '
			<div class="alert alert-danger" role="alert">
				<p>You cannot upload the file type.</p>
				<script language="javascript" type="text/javascript">window.top.window.formEnable();</script>
			</div>
		';
	} else {

		// get file size
		$filesize = $_FILES['uploadfile']['size'];

		// check filesize
		if ($filesize > $maxlimit) {

			echo '
				<div class="alert alert-danger" role="alert">
					<p>File size is too big.</p>
				</div>
			';

		} elseif ($filesize < 1) {

			echo '
				<div class="alert alert-danger" role="alert">
					<p>File size is empty.</p>
				</div>
			';

		} else {

			// temporary file
			$uploadedfile = $_FILES['uploadfile']['tmp_name'];

			// capture the original size of the uploaded image
			list($width, $height) = getimagesize($uploadedfile);

			// check if image size is lower
			if ($width < $minwidth || $height < $minheight) {
				echo 'Image is to small. Required minimum ' . $minwidth . 'x' . $minheight . '
		<script language="javascript" type="text/javascript">window.top.window.formEnable();</script>';
			} elseif ($width > $maxwidth || $height > $maxheight) {
				echo 'Image is to big. Required maximum ' . $maxwidth . 'x' . $maxheight . '
		<script language="javascript" type="text/javascript">window.top.window.formEnable();</script>';
			} else {

				// all characters lowercase
				$filename = strtolower($_FILES['uploadfile']['name']);


				// replace all spaces with _
				$filename = preg_replace('/\s/', '_', $filename);


				// extract filename and extension
				$pos = strrpos($filename, '.');
				$basename = substr($filename, 0, $pos);
				$ext = substr($filename, $pos + 1);


				// get random number
				$rand = time();


				// image name
				$image = $basename;


				// check if file exists
				$check = $original_folder . '/' . $image . "." . $ext;
				
				if (file_exists($check)) {

					echo '
						<div class="alert alert-danger" role="alert">
							<p>That image already exists.</p>
						</div>
					';

				} else {

					// check if it's animate gif
					$frames = exec("identify -format '%n' " . $uploadedfile . "");
					if ($frames > 1) {

						// yes it's animate image
						// copy original image
						copy($_FILES['uploadfile']['tmp_name'], $resized_folder . '/' . $image . "." . $ext);

						// orignal image location
						$write_image = $original_folder . '/' . $image . "-" . $original_folder . "." . $ext;

						// ennable form
						echo '<img src="' . $write_image . '" alt="' . $image . '.' . $ext . '" alt="' . $image . '.' . $ext . '" width="500" /><br />
<input type="text" name="location" value="[IMG]' . $script_location . '' . $write_image . '[/IMG]" class="location corners" />
<script language="javascript" type="text/javascript">window.top.window.formEnable();</script>';
					} else {

						// create an image from it so we can do the resize
						switch ($ext) {
						case "gif":
							$src = imagecreatefromgif($uploadedfile);
							break;

						case "jpg":
							$src = imagecreatefromjpeg($uploadedfile);
							break;

						case "jpeg":
							$src = imagecreatefromjpeg($uploadedfile);
							break;

						case "png":
							$src = imagecreatefrompng($uploadedfile);
							break;
						}

						// copy original image
						copy($_FILES['uploadfile']['tmp_name'], $original_folder . '/' . $image . '.' . $ext);

						// orignal image location
						$write_image = $original_folder . '/' . $image . '.' . $ext;

						// create first thumbnail image - resize original to 80 width x 80 height pixels
						$newheight = ($height / $width) * 800;
						$newwidth = 800;
						$tmp = imagecreatetruecolor($newwidth, $newheight);
						imagealphablending($tmp, false);
						imagesavealpha($tmp, true);
						$transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
						imagefilledrectangle($tmp, 0, 0, $newwidth, $newheight, $transparent);
						imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

						// write thumbnail to disk
						$write_thumbimage = $resized_folder . '/' . $gpi_sku . '.' . $ext;

						switch ($ext) {
							case "gif":
								imagegif($tmp, $write_thumbimage);
							break;

							case "jpg":
								imagejpeg($tmp, $write_thumbimage, 100);
							break;

							case "jpeg":
								imagejpeg($tmp, $write_thumbimage, 100);
							break;

							case "png":
								imagepng($tmp, $write_thumbimage);
							break;
						}

						// all is done. clean temporary files
						imagedestroy($src);
						imagedestroy($tmp);

						include_once('gpi-save-to-database.php');
					}
				}
			}
		}

	}
}
