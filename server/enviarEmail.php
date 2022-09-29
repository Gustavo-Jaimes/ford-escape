<?php 

	use PHPMailer\PHPMailer\PHPMailer;
				
	require_once '../PHPMailer/src/PHPMailer.php';
	require_once '../PHPMailer/src/SMTP.php';
	require_once '../PHPMailer/src/Exception.php';


	if(isset($_POST["correoSolicitante"])) {

		$nombreSolicitante = '';
        $telefonoSolicitante = '';
		$correoSolicitante = '';
		$versionSolicitante = '';
        $fechaTSolicitante = '';

		$nombreSolicitante_error = '';
        $telefonoSolicitante_error = '';
		$correoSolicitante_error = '';
		$versionSolicitante_error = '';
        $fechaTSolicitante_error = '';
		$captcha_error = '';

		if(empty($_POST["nombreSolicitante"])) {
			$nombreSolicitante_error = 'El nombre es requerido';
		}
		else {
			$nombreSolicitante = $_POST["nombreSolicitante"];
		}

        if(empty($_POST["telefonoSolicitante"])) {
			$telefonoSolicitante_error = 'El telefono es requerido';
		}
		else {
			$telefonoSolicitante = $_POST["telefonoSolicitante"];
		}

		if(empty($_POST["correoSolicitante"])) {
			$correoSolicitante_error = 'El email es requerido';
		}
		else {
			if(!filter_var($_POST["correoSolicitante"], FILTER_VALIDATE_EMAIL)) {
				$correoSolicitante_error = 'Email invalido, intenta de nuevo';
			}
			else {
				$correoSolicitante = $_POST["correoSolicitante"];
			}
		}
		
		if(empty($_POST["versionSolicitante"])) {
			$versionSolicitante_error = 'La version es requerida';
		}
		else {
			$versionSolicitante = $_POST["versionSolicitante"];
		}

		if(empty($_POST["fechaTSolicitante"])) {
			$fechaTSolicitante_error = 'La fecha es requerida';
		}
		else {
			$fechaTSolicitante = $_POST["fechaTSolicitante"];
		}

		if(empty($_POST['g-recaptcha-response'])) {
			$captcha_error = 'Debes completar el Captcha';
		}
		else {
			$captcha = $_POST['g-recaptcha-response'];
			$secret_key = '6LcwdLYaAAAAAAi7pnc8e6WFeSvBulSLRv1ashBj';
			$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
			$response_data = json_decode($response, TRUE);
			$error = json_encode($response_data);

			if(!$response_data['success']) {
				$captcha_error = $error;
			}
		}

		if($nombreSolicitante_error == '' && $telefonoSolicitante_error == '' && $correoSolicitante_error == '' && $versionSolicitante_error == '' && $fechaTSolicitante_error == '' && $captcha_error == '') {

			$nombreSoli = $_POST['nombreSolicitante'];
            $telefonoSoli = $_POST['telefonoSolicitante']; 
			$correoSoli = $_POST['correoSolicitante'];
			$versionSoli = $_POST['versionSolicitante'];
            $fechaSoli = $_POST['fechaTSolicitante'];


			date_default_timezone_set("America/Mexico_City");
			$fecha = date('d/m/Y');
			$hora = date('H:i');

			$mail = new PHPMailer(true);		
			$mail->isSMTP();
			$mail->isMail();
			$mail->SMTPDebug = 0;		
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Host = 'smtp.ionos.mx';
			$mail->Port = '587';
			
			$mail->Username = 'noreply@galletamkt.com';
			$mail->Password = 'GalletaMKT77%';

			$mail->setFrom('noreply@galletamkt.com', 'Nuevo prospecto para la Escape: '.$versionSoli.'');

			$mail->AddAddress('noreply@galletamkt.com');
			$mail->WordWrap = 50;
	
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			$mail->Subject = 'Version '.$versionSoli.'';
			$mail->Body = '
			<h3 align="center">Detalles del solicitante</h3>
			<p>Fecha y hora de contacto: '.$fecha.' '.$hora.'</p>
			<table border="1" width="100%" cellpadding="5" cellspacing="5">
				<tr>
					<td width="30%">Version</td>
					<td width="70%">'.$versionSoli.'</td>
				</tr>
				<tr>
					<td width="30%">Nombre</td>
					<td width="70%">'.$nombreSoli.'</td>
				</tr>
                <tr>
					<td width="30%">Telefono</td>
					<td width="70%">'.$telefonoSoli.'</td>
				</tr>
				<tr>
					<td width="30%">Correo</td>
					<td width="70%">'.$correoSoli.'</td>
				</tr>
				<tr>
					<td width="30%">Fecha tentativa de compra</td>
					<td width="70%">'.$fechaSoli.'</td>
				</tr>
			</table>
			';

			if($mail->send()) {
				$data = array(
					'success' => true
				);
			}
			else {
				$data = array(
					'success' => false
				);
			}
		}
		else {
			$data = array(
				'nombreSolicitante_error' => $nombreSolicitante_error,
                'telefonoSolicitante_error'  => $telefonoSolicitante_error,
				'correoSolicitante_error' => $correoSolicitante_error,
				'versionSolicitante_error' => $versionSolicitante_error,
                'fechaTSolicitante_error' => $fechaTSolicitante_error,
				'captcha_error'  => $captcha_error
			);
		}
		echo json_encode($data);
	}

?>