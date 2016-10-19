<?php
/**
 * @author     Michał Boszke <boszkem@gmail.com>
 * @copyright  2016
 * @version    v 1.0
 */
?>

<section role="main" class="content-body">
	<header class="page-header">
		<h2>
			Dodawanie do serwisów społecznościowych
			<small>Ustawienia</small>
		</h2>
	
		<div class="right-wrapper pull-right">	
			<ol class="breadcrumbs">
				<li>
				</li>
			</ol>
			<a class="sidebar-right-toggle"></a>
		</div>
	</header>

	<!-- start: page -->
	<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-tabs tabs-primary">
					<li>
						<a href="#facebook" data-toggle="tab">Facebook</a>
					</li>
					<li>
						<a href="#twitter" data-toggle="tab">Twitter</a>
					</li>
				</ul>
				
				<div class="tab-content">
				
					<div id="facebook" class="tab-pane active">			
						<div id="przyciskUzyskajToken" <?php if (!($this->daneIstnieja)) echo "class=\"hidden\"";  ?> >
							<!--<form method="post" action="<?php echo $this->loginUrl ?>">-->
								<div class="row">
									<div class="col-md-9">
										<!-- <input type="submit" value="Uaktualnij Token" class="btn btn-info"> -->
										<a href="<?php echo $this->loginUrl; ?>">Uaktualnij token</a>
									</div>
								</div>
							<!--</form>-->			
						</div>
							
						<form method="post" action="<?php echo URL?>autopost/changeSettingFB">
							<h3>Dane FB:</h3>					
							<hr class="dotted tall">
							<fieldset>
								<div class="form-group">
									<label class="col-md-3 control-label">Application ID:</label>
									<div class="col-md-8">
										<input class="form-control" type="text" name="fb_app_id" id="fb_app_id" value="<?php if(!empty($this->fb_app_id)) echo $this->fb_app_id; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Application Secret:</label>
									<div class="col-md-8">
										<input class="form-control" type="text" name="fb_app_secret" id="fb_app_secret" value="<?php if(!empty($this->fb_app_secret)) echo $this->fb_app_secret; ?>">
									</div>
								</div>
								
								
								<?php 
								/*
								$page_list = $this->page_list;
									//wyświetlanie radiobuttonów
									if (!empty($page_list) && ($this->daneIstnieja) == true && !empty($this->fb_app_token))
									{
										echo '<div class="form-group">';
										echo '<label class="col-md-3 control-label">Wybór Fan Page:</label>';
										echo '<div class="col-md-8">';
							
										//wyświetlanie tyle ile jest fanpage'ów
										for ($i=0; $i<count($page_list); $i++)
										{
											echo '<div class="radio">';
											echo "<label><input type=\"radio\" 
													name=".'radio_list_page'." 
													value=\"".$page_list[$i][1]."\"";
													if ($page_list[$i][1] == $this->fb_app_token) echo "checked"; //zaznaczony radiobuuton który ma taki sam access token co ten w bazie danych
													echo " />";
													echo $page_list[$i][0]."</label>";
											echo '</div>';
										}
										echo '</div>';
										echo '</div>';
									}
									*/
								?>
								
							</fieldset>
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-9 col-md-offset-3">
										<input type="submit" value="Zapisz" class="btn btn-primary">
									</div>
								</div>
							</div>
						</form>
					</div>
					
					<div id="twitter" class="tab-pane">
						<form method="post" action="<?php echo URL?>autopost/changeSettingTT">
							<h3>Dane Twitter:</h3>					
							<hr class="dotted tall">
							<fieldset>
								<div class="form-group">
									<label class="col-md-3 control-label">Consumer Key:</label>
									<div class="col-md-8">
										<input class="form-control" type="text" name="tt_consumer_key" id="tt_consumer_key" value="<?php if(!empty($this->tt_consumer_key)) echo $this->tt_consumer_key; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Consumer Secret:</label>
									<div class="col-md-8">
										<input class="form-control" type="text" name="tt_consumer_secret" id="tt_consumer_secret" value="<?php if(!empty($this->tt_consumer_secret)) echo $this->tt_consumer_secret; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Access Token:</label>
									<div class="col-md-8">
										<input class="form-control" type="text" name="tt_access_token" id="tt_access_token" value="<?php if(!empty($this->tt_access_token)) echo $this->tt_access_token; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Access Token Secret:</label>
									<div class="col-md-8">
										<input class="form-control" type="text" name="tt_access_token_secret" id="tt_access_token_secret" value="<?php if(!empty($this->tt_access_token_secret)) echo $this->tt_access_token_secret; ?>">
									</div>
								</div>
							</fieldset>
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-9 col-md-offset-3">
										<input type="submit" value="Zapisz" class="btn btn-primary">
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
	</div>
	<!-- end: page -->
	
</section>