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
				</ul>
				
				<div class="tab-content">
				
					<div id="facebook" class="tab-pane active">								
						<form method="post" action="<?php echo URL?>autopost/changeFanpage">
							<fieldset>
							<p>Jeżeli chcesz publikować na swoim prywatnym profilu pomiń ten krok klikając zapisz (bez zaznaczonego FanPage).</p>
								<?php 
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
				</div>
			</div>
	</div>
	<!-- end: page -->
	
</section>