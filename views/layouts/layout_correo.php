<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="<? echo $config->get('media') .'librerias/Bootstrap/Bootstrap3.3.7/dist/css/bootstrap.min.css';?>">
</head>
<body>
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8" style="background: #2a2b31;padding:10px 0px 0px 0px;">
			<center>
				<p style="color:#00a1e1;font-size:20px;">{TITTLE}</p>
				<br>
				<p style="padding:0 0 35px 0;font-size:15px;font-family:Helvetica,Arial,sans-serif;color:#ffffff;" align="center">{HEADER}</p>
			</center>
		</div>
		<div class="col-md-2">
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8" style="background: #F5F7FA;padding:30px 15px 30px 15px;">
			<center>
				<p style="padding:0 0 30px 0;font-size:14px;font-family:Helvetica,Arial,sans-serif;color:#000000;text-decoration:none" align="center">{BODY}</p>
				<br>
				<div class="row">
					<div class="col-md-2">
					</div>
					<div class="col-md-8" style="padding:0 0 10px 0;border-radius:3px;background-color:#1476fb; ">
						<center>
							<p style="font-size:20px; color: white;">{URL}</p>
						</center>
					</div>
					<div class="col-md-2">
					</div>
				</div>
			</center>
		</div>
		<div class="col-md-2">
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8" style="padding:30px 15px 30px 15px; background-color: #D6D9DD;">
			<center>
				<p style="padding:0 0 0 0;font-size:14px;font-family:Helvetica,Arial,sans-serif;color:#000000" align="center">{FOOTER_BODY}</p>
				<br>
			</center>
		</div>
		<div class="col-md-2">
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8" style="">
			<center>
				<p style="font-size:12px;line-height:18px;font-family:Helvetica,Arial,sans-serif;color:#666666" align="center">{FOOTER}</p>
			</center>
		</div>
		<div class="col-md-2">
		</div>
	</div>
	<br><br><br>
	<div class="row">
		<center>
			<p>{FIRMA}</p>
		</center>
	</div>
</body>
</html>