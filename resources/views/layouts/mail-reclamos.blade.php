<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>DRH-ESSALUD</title>
</head>
<body style="margin:0; padding:0; background-color: #eeeeee;">
	<table  width="750" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">

		<tr>
			<td style="border:1px solid #cccccc; padding-top:20px; padding-bottom:20px; background-color: #fff;">
				<table  width="708" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
					<tr>
						<td style="border:1px dashed #707070; padding: 0; background-color: #fff;">
							<table  width="708" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto; padding: 0 5px;">
								<tr>
									<td>
										<a href="{{ url('/') }}" target="_blank">
											<!-- <img src="{{ asset('/images/mailing/top.jpg') }}" style="border:0; display:block; font-size:0;"> -->
										</a>
									</td>
								</tr>
								<tr>
									<td style="font-size:14px; line-height:19px; font-family:arial; color:#707070; padding-bottom:0; vertical-align:top;">
										@yield('message')
									</td>
								</tr>

								<tr>
									<td style="padding: 12px 0;">
										@yield('detalle')
									</td>
								</tr>								
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
			
	</table>
	<div align="center" style="font-size:16px; line-height:19px; font-family:arial; color:#707070; padding:20px;">
		© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
	</div>	
</body>
</html>
