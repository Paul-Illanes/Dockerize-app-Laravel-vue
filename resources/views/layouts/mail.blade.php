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
							<table  width="708" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
								<tr>
									<td>
										<a href="{{ url('/') }}" target="_blank">
											<!-- <img src="{{ asset('/images/mailing/top.jpg') }}" style="border:0; display:block; font-size:0;"> -->
										</a>
									</td>
								</tr>
								<tr>
									<td style="padding: 40px 0;">
										<table  width="708" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
											<tr>
												<td style="width:40px;"></td>
												<td style="font-size:16px; line-height:19px; font-family:arial; color:#707070; padding-bottom:20px; vertical-align:top;">
													@yield('message')
												</td>
												<td style="width:40px;"></td>									
											</tr>
											<tr>
												<td style="width:40px;"></td>
												<td style="font-size:22px; line-height:23px; font-family:arial; color:#E5243B; padding-bottom:20px; font-weight:bold; vertical-align:top;">
													@yield('code')
												</td>
												<td style="width:40px;"></td>									
											</tr>
											<tr>
												<td colspan="3" style="height:120px;"></td>
											</tr>
										</table>
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
