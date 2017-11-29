<!DOCTYPE html>
<html>
	
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$titulo?></title>
</head>


<body align="center" bgcolor="#f7f7f7">

<!-- Tabela fundo cinza -->
<table bgcolor="#f7f7f7" align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family: Arial, Verdana, Geneva, Tahoma, sans-serif; color:#4E5460; text-decoration: none; -webkit-text-size-adjust:none;">
	<tr align="center">
		<td align="center" width="580">
			<!-- Cabeçalho START -->
			<table bgcolor="#f7f7f7" align="center" border="0" cellpadding="0" cellspacing="0" width="554">
				<tr align="center" height="15"></tr>
				
				<tr align="center" height="40">
					<td valign="center" align="center">
					<span style="color:#84888F; line-height:13px; font-size:11px;">
						Email enviado atrav&eacute;s do site: <?=$this->config->item('site')?>!<br />
						<br />
					</span>
					</td>
				</tr>
			</table>
			<!-- Cabeçalho END -->
			
			<table bgcolor="#f7f7f7" align="center" border="0" cellpadding="0" cellspacing="0" width="554">
				<tr align="center">
                    <td align="center" width="27" valign="top">
                        <img src="http://www.zord.com.br/sistema/images/emails/estrutura-email/news-shadow-left-top.png" />
                    </td>
					<td align="center" rowspan="2" width="500">
						<!-- Detalhe START -->
						<table bgcolor="#f7f7f7" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr align="center">
								<td align="center">
								<img src="http://www.zord.com.br/sistema/images/emails/estrutura-email/news-detalhe-superior.png" width="500" border="0" align="center" style="display:block" />
								</td>
							</tr>
						</table>
						<!-- Detalhe END -->
					
						<!-- Tabela Branca START -->
						<table bgcolor="#ffffff" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr align="center">
								<td align="center" width="10"></td>

								<td align="center" width="480">
									<!-- Logotipo START -->
									<table bgcolor="#ffffff" align="center" width="450" cellspacing="0" cellpadding="0" border="0">
										<tr height="30"></tr>
										
										<tr align="center" height="45">
											<!-- START -->
											<td width="200" align="left">
												<a href="<?=$this->config->item('site')?>" target="_blank">
													<h1><?=$this->config->item('empresa')?></h1>
												</a>
											</td>
											<!-- END -->
										
											<td width="50"></td>
										
											<!-- Data START -->
											<td width="200" align="right">
												<span style="color:#84888F; line-height:13px; font-size:12px;">
												
												</span>
											</td>
											<!-- Data END -->
										</tr>
										
										<tr height="15"></tr>
									</table>
									<!-- Logotipo END -->

									<!-- Linha_Cinza START -->
									<hr size="0.5" color="#DFDFDF">
									<!-- Linha_Cinza END -->

									
									<!-- Texto START -->
									<table bgcolor="#ffffff" align="center" width="450" cellspacing="0" cellpadding="0" border="0">
										<tr height="35"></tr>
										
										<!-- Introdução START -->
										<tr>
											<td align="left">
												<span style="font-size: 14px; line-height: 5px;">
												<p style="font-weight: bold; font-size: 26px;">
												<?=$titulo?>
												</p>
												<p style="line-height: 20px;"><?=$mensagem?></p>
												<br />
												</span>
											</td>
										</tr>
										
										<!-- Introdução END -->
																																																					
										<tr height="10"></tr>
										
										<!-- Lembre-se START -->
										<tr>
											<td align="justify">
												<span style="font-size:24px;">
												<b>Aten&ccedil;&atilde;o!</b><br />
												<br />
												</span>
											</td>
										</tr>
										<!-- Lembre-se END -->
										
										
										
										<tr height="30"></tr>
										
										<!-- Texto Final START -->
										<tr>
											<td align="justify">
												<span style="line-height:19px; font-size:12px;">
												Atenciosamente,<br />
												<?=$this->config->item('empresa')?><br />
												</span>
											</td>
										</tr>
										<!-- Texto Final END -->
										
										
									</table>
									<!-- Texto END -->


									<!-- Dúvidas START (com colunas laterais) 0 -->
									<table bgcolor="#ffffff" align="center" width="450" cellspacing="0" cellpadding="0" border="0">
										<tr align="center" height="90">
											<td width="5"></td>
										</tr>
									</table>
									<!-- Dúvidas END -->
								</td>

								<td width="10"></td>

							</tr>
						</table>
						<!-- Tabela Branca START -->
						
						
					</td>
                    <td width="27" valign="top"><img src="http://www.zord.com.br/sistema/images/emails/estrutura-email/news-shadow-right-top.png" /></td>
				</tr>

                <tr>
                    <td width="27" valign="bottom"><img src="http://www.zord.com.br/sistema/images/emails/estrutura-email/news-shadow-left-bottom.png" /></td>
                    <td width="27" valign="bottom"><img src="http://www.zord.com.br/sistema/images/emails/estrutura-email/news-shadow-right-bottom.png" /></td>
                </tr>
            </table>


			
		</td>
	</tr>
</table>

</body>
</html>
