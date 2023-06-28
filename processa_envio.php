<?php
    require "bibliotecas/phpmailer/Exception.php";
    require "bibliotecas/phpmailer/OAuth.php";
    require "bibliotecas/phpmailer/OAuthTokenProvider.php";
    require "bibliotecas/phpmailer/PHPMailer.php";
    require "bibliotecas/phpmailer/POP3.php";
    require "bibliotecas/phpmailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array('codigo_status' => null, 'descricao_status' => null);

        public function __get($atributo){
            return $this->$atributo;
        }

        public function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        public function mensagemValida(){
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
                return false;
            }
            return true;
        }
    }

    $mensagem = new Mensagem();

    $mensagem->__set('para' , $_POST['para']);
    $mensagem->__set('assunto' , $_POST['assunto']);
    $mensagem->__set('mensagem' , $_POST['mensagem']);

    if(!$mensagem->mensagemValida()){
        echo "Mensagem inválida!";
        header('Location: index.php');
    }

 
	$mail = new PHPMailer(true);
	try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output (2)
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'email@gmail.com';                     //SMTP username
        $mail->Password   = 'senha';                               //SMTP password
        $mail->SMTPSecure = 'seguranca';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = porta;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('caiocesarama@gmail.com', 'Caio César');
        $mail->addAddress($mensagem->__get('para'));     //Add a recipient
        //$mail->addReplyTo('', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto');
        $mail->Body    = $mensagem->__get('mensagem');
        $mail->AltBody = "É necesário um cliente de suporte HTML para renderização da mensagem!";

        $mail->send();
        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'Mensagem enviada com sucesso!';
	} catch (Exception $e) {
        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = "Não foi possivel enviar este e-mail! Por favor tente novamente mais tarde. Detalhes do erro: " . $mail->ErrorInfo;
        //pode ser inserida uma lógica para armazenar o erro para futura análise do programador
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Mail Send</title>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
    <body>
        <div class="container">
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

            <div class="row">
                <div class="col-md-12">
                    <?php
                        if($mensagem->status['codigo_status'] == 1){
                    ?>

                    <div class="container">
                        <hi class="display-4 text-success">Sucesso</hi>
                        <p><?=$mensagem->status['descricao_status']?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>

                    <?php }if($mensagem->status['codigo_status'] == 2){ ?>

                    <div class="container">
                        <hi class="display-4 text-danger">Ops!</hi>
                        <p><?=$mensagem->status['descricao_status']?></p>
                        <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                    </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
