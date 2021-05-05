<?php
    include "config.php";
    include "header.php";
?>

<html>
    <head>
        <title>Real-Estate</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> 
        <link rel="stylesheet" href="stylesheets/contact.css">
    </head>

    <body>
    <div class="container-fluid contact-form mt-0">
		<div class='row-auto mt-0'>
			<div class='col'>
				<h1 class='h1 mb-5 text-center display-4'>
					<?php 
						if(isset($_GET['result']))
							switch($_GET['result']){
								case "invalidemail":
									echo "Invalid Email";
									break;
								case "sent":
									echo "Message Sent!";
									break;
							}
					?>
				</h1>
			</div>
		</div>
			<div class='row-auto shadow-lg'>
				<div class="contact-image">
					<img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
				</div>
				<form action="include/send-message.inc.php" method="post">
					<h3 class="h3 display-2">Drop Us a Message</h3>
					<div class="row-fluid">
						<div class="form-row">
							<div class="form-group w-100">
								<input type="text" name="name" class="form-control m-1" placeholder="Your Name *" value="<?php if(isset($_SESSION['userUid'])) echo $_SESSION['userUid'] ?>" required/>
								<input type="email" name="email" class="form-control m-1" placeholder="Your Email *" value="" required/>
							</div>

							<div class="form-group w-100">
								<input type="text" name="topic" class="form-control m-1" placeholder="Property being enquired" value="<?php if(isset($_GET['id'])) echo "Enquiry for property #".$_GET['id'] ?>"/>
							</div>

							<div class="form-group w-100">
								<textarea class="form-control m-1" name="message" placeholder="Your Message *" rows=8 required></textarea>
							</div>
						</div>

						<button class="btn btn-primary btn-block" type="submit" name="submit-form">Send Message</button>
					</div>
				</form>
			</div>
        </div>
    </body>
</html>