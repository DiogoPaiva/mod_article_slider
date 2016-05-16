<?php

defined('_JEXEC') or die('Restricted access');
if ($crapIE6 == true) {
    $slideShow_width = "auto";
}

$contentWrapHeight = ($button_style != "hide") ? $slideShow_height - 21 : $slideShow_height;
$textBoxHeight = ($show_readmore) ? $contentWrapHeight - $ReadMore_font_size - 25 : $contentWrapHeight - 20;
$klixoSliderMod = 'div#klixoSlider_' . $module->id;
$navBarWidth = ($currentBrowser === 'IE7') ? count($items) * 14 . 'px' : 'auto';
$autoplay= "false";

if($params->get("auto_play", '') =="1"): $autoplay="true";
endif; 


if (count($items) > 0):
    ?>
    <script type="text/javascript">
	
	jQuery(document).ready(function(){
	
	
jQuery('#moduleSlideShow87').bxSlider({
				   nextSelector:'#next',
                   prevSelector:'#prev',
				   minSlides: 1,
				   maxSlides:3,
				   auto: <?php echo $autoplay; ?>,
				   moveSlides: 1,
				   infiniteLoop: false,
				   hideControlOnEnd: true,
				   responsive: true,
				   slideWidth: '320',
				   pager: false
                });

	});
	
    </script>

<?php if($params->get("featured", '') =="child_c"): ?>
		<div class="article_slider <?php echo $moduleclass_sfx; ?>" id="klixoSlider_<?php echo $module->id; ?>">
			<div id="moduleSlideShow<?php echo $module->id; ?>">
				<?php
				$index = 0;
				foreach ($items as $key => $item) {
					$index++;
					?>
					<div id="slide_<?php echo $index ?>" class="slide_item">
						<div class ="contentBoxWrapper">
							<div class="imagem">
								<a class="img_wrapper" href="<?php echo $item->link ?>" target="_self">
									<img alt="<?php echo $item->sub_title ?>" src="<?php echo $item->getParams()->get('image'); ?>"/>
								</a>
							</div>
							<div class="tit_area">
							   <h3><a href="<?php echo $item->link ?>" target="_self"><?php echo $item->sub_title ?></a> </h3>      
							   <div class="descr"><?php echo strip_tags($item->description) ?></div>
							</div>
						</div>
					</div>
				<?php } ?> 
			</div>		
		</div>
		<div class="setas"><div id="prev"></div><div id="next"></div></div>
<?php endif; ?>
<?php if($params->get("featured", '') =="show"): ?>
	<div class="article_slider <?php echo $moduleclass_sfx; ?>" id="klixoSlider_<?php echo $module->id; ?>">
        <div id="moduleSlideShow<?php echo $module->id; ?>">
            <?php
            $index = 0;
            foreach ($items as $key => $item) {
                $index++; ?>
				<div id="slide_<?php echo $index ?>" class="slide_item">
                    <div class ="contentBoxWrapper">
							<div class="imagem">
							<div class="image_padding">
								<a class="img_wrapper" href="<?php echo $item->link ?>" target="_self">
									<?php $images = json_decode($item->images); ?>
									<img src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo $item->sub_title ?>" />
								</a>
							</div>
                            </div>
						   <div class="tit_area">
										<h3> 
											<a href="<?php echo  $item->link ?>" target="<?php echo $target; ?>" onclick="<?php echo $clickEvent ?>">
											<?php echo $item->sub_title ?>
											</a>
										</h3>
									       
									<div class="descr">
									<a href="<?php echo  $item->link ?>" target="<?php echo $target; ?>" onclick="<?php echo $clickEvent ?>">
										<?php echo $item->sub_content ?>
										</a>
									</div>
                           </div> 
                    </div>
                </div>
            <?php } ?> 
        </div>		
		<div class="setas"><div id="prev"></div><div id="next"></div></div>
	  </div>

  <?php endif; ?>
  <?php endif; ?>	