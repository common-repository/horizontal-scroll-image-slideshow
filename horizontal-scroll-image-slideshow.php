<?php
/*
Plugin Name: Horizontal scroll image slideshow
Plugin URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/
Description: Horizontal scroll image slideshow lets you showcase images in a horizontal scroll like fashion, one image at a time and in a continuous manner, with no breaks between the first and last image.  
Author: Gopi Ramasamy
Version: 10.1
Author URI: http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/
Donate link: http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: horizontal-scroll-image-slideshow
Domain Path: /languages
*/

function hsis_show() 
{
	$atts = array();
	$atts["dir"] = get_option('hsis_dir');
	if ($atts["dir"] == "") {
		$hsis_pluginurl = plugins_url() . "/horizontal-scroll-image-slideshow/gallery/widget.xml";
		$atts["dir"] = $hsis_pluginurl;
	}
	$atts["width"] = get_option('hsis_width');
	$atts["height"] = get_option('hsis_height');
	$atts["speed"] = get_option('hsis_speed');
	$atts["bgcolor"] = get_option('hsis_bgcolor');
	echo hsis_show_shortcode($atts);
}

function hsis_show_shortcode($atts) 
{
	//[horizontal-scroll-image-slideshow dir="http://www.gopiplus.com/wp-content/plugins/horizontal-scroll-image-slideshow/gallery/widget.xml" width="200" height="167" speed="2000" bgcolor=""]
	if ( ! is_array( $atts ) ) {
		return '';
	}
	$hsis_dir = isset($atts['dir']) ? $atts['dir'] : '';
	$hsis_width = isset($atts['width']) ? $atts['width'] : '200';
	$hsis_height = isset($atts['height']) ? $atts['height'] : '167';
	$hsis_speed = isset($atts['speed']) ? $atts['speed'] : '2000';
	$hsis_bgcolor = isset($atts['bgcolor']) ? $atts['bgcolor'] : 'white';
	
	$hsis_pluginurl = plugins_url() . "/horizontal-scroll-image-slideshow/";
	if ($hsis_dir == "") {
		$hsis_dir = plugins_url() . "/horizontal-scroll-image-slideshow/gallery/widget.xml";
	}
	
	if(!is_numeric($hsis_width)) {
		$hsis_width = 200;
	} 
	
	if(!is_numeric($hsis_height)) {
		$hsis_height = 167;
	} 

	if(!is_numeric($hsis_speed)) {
		$hsis_speed = 2000;
	} 
		
	$doc = new DOMDocument();
	$doc->load( $hsis_dir );
	$images = $doc->getElementsByTagName( "image" );
	$vs_count = 0;
	$hsis_package = "";
	foreach( $images as $image )
	{
	  $paths = $image->getElementsByTagName( "path" );
	  $path = $paths->item(0)->nodeValue;
	  $targets = $image->getElementsByTagName( "target" );
	  $target = $targets->item(0)->nodeValue;
	  $titles = $image->getElementsByTagName( "title" );
	  $title = $titles->item(0)->nodeValue;
	  $links = $image->getElementsByTagName( "link" );
	  $link = $links->item(0)->nodeValue;
	  
	  $hsis_package = $hsis_package . "hsis_slideimages[$vs_count]='<a href=\'$link\' target=\'$target\'><img src=\'$path\' border=\'0\' title=\'$title\' alt=\'$title\'></a>'; ";
	  $vs_count++;
	}
	   
	$hsis = '<script language="JavaScript1.2">';
		$hsis .= "var hsis_scrollerwidth='" . $hsis_width . "px';";
		$hsis .= "var hsis_scrollerheight='" . $hsis_height . "px';";
		$hsis .= "var hsis_scrollerbgcolor='" . $hsis_bgcolor . "';";
		$hsis .= "var hsis_pausebetweenimages=" . $hsis_speed . ";";
		$hsis .= "var hsis_slideimages=new Array();";
		$hsis .= $hsis_package;
    $hsis .= "</script>";

    $hsis .= '<script language="JavaScript1.2" src="' . $hsis_pluginurl . '/horizontal-scroll-image-slideshow.js"></script>';
	
    $hsis .= '<ilayer id="hsis_main" width=&{hsis_scrollerwidth}; height=&{hsis_scrollerheight}; bgColor=&{hsis_scrollerbgcolor}; visibility=hide> <layer id="first" left=1 top=0 width=&{hsis_scrollerwidth}; >';
		$hsis .= '<script language="JavaScript1.2">';
			$hsis .= 'if (document.layers)';
				$hsis .= 'document.write(hsis_slideimages[0]);';
		$hsis .= '</script>';
    $hsis .= '</layer><layer id="second" left=0 top=0 width=&{hsis_scrollerwidth}; visibility=hide>';
    $hsis .= '<script language="JavaScript1.2">';
    	$hsis .= 'if (document.layers)';
    		$hsis .= 'document.write(hsis_slideimages[1]);';
    $hsis .= '</script>';
    $hsis .= '</layer></ilayer>';
	
    $hsis .= '<script language="JavaScript1.2">';
    $hsis .= 'if (ie||dom)'; 
	$hsis .= "{ ";
		$hsis .= "document.writeln('<div id=\"hsis_main2\" style=\"position:relative;width:'+hsis_scrollerwidth+';height:'+hsis_scrollerheight+';overflow:hidden;background-color:'+hsis_scrollerbgcolor+'\">');";
		$hsis .= "document.writeln('<div style=\"position:absolute;width:'+hsis_scrollerwidth+';height:'+hsis_scrollerheight+';clip:rect(0 '+hsis_scrollerwidth+' '+hsis_scrollerheight+' 0);left:0px;top:0px\">');";
		$hsis .= "document.writeln('<div id=\"hsis_first2\" style=\"position:absolute;width:'+hsis_scrollerwidth+';left:1px;top:0px;\">');";
		$hsis .= "document.write(hsis_slideimages[0]);";
		$hsis .= "document.writeln('</div>');";
		$hsis .= "document.writeln('<div id=\"hsis_second2\" style=\"position:absolute;width:'+hsis_scrollerwidth+';left:0px;top:0px\">');";
		$hsis .= "document.write(hsis_slideimages[1]);";
		$hsis .= "document.writeln('</div>');";
		$hsis .= "document.writeln('</div>');";
		$hsis .= "document.writeln('</div>');";
    $hsis .= "}";
    $hsis .= "</script>";
	
	return $hsis;
}

function hsis_install() 
{
	add_option('hsis_title', "Slideshow");
	add_option('hsis_width', "250");
	add_option('hsis_height', "167");
	add_option('hsis_bgcolor', "white");
	add_option('hsis_speed', "2000");
	
	$hsis_pluginurl = plugins_url() . "/horizontal-scroll-image-slideshow/gallery/widget.xml";
	add_option('hsis_dir', $hsis_pluginurl);
}

function hsis_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('hsis_title');
	echo $after_title;
	hsis_show();
	echo $after_widget;
}

function hsis_admin_option() 
{
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32 icon32-posts-post"><br>
		</div>
		<h2><?php _e('Horizontal scroll image slideshow', 'horizontal-scroll-image-slideshow'); ?></h2>
		<?php
		$hsis_title = get_option('hsis_title');
		$hsis_width = get_option('hsis_width');
		$hsis_height = get_option('hsis_height');
		$hsis_bgcolor = get_option('hsis_bgcolor');
		$hsis_speed = get_option('hsis_speed');
		$hsis_dir = get_option('hsis_dir');
	
		if (isset($_POST['hsis_form_submit']) && $_POST['hsis_form_submit'] == 'yes')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('hsis_form_setting');
				
			$hsis_title 	= stripslashes(sanitize_text_field($_POST['hsis_title']));
			$hsis_width 	= stripslashes(intval($_POST['hsis_width']));
			$hsis_height 	= stripslashes(intval($_POST['hsis_height']));
			$hsis_bgcolor 	= stripslashes(sanitize_text_field($_POST['hsis_bgcolor']));
			$hsis_speed 	= stripslashes(intval($_POST['hsis_speed']));
			$hsis_dir 	= stripslashes(sanitize_text_field($_POST['hsis_dir']));
			
			if(!is_numeric($hsis_width) || $hsis_width == 0) { $hsis_width = 205; }
			if(!is_numeric($hsis_height) || $hsis_height == 0) { $hsis_height = 150; }
			if(!is_numeric($hsis_speed) || $hsis_speed == 0) { $hsis_speed = 2000; }
			
			update_option('hsis_title', $hsis_title );
			update_option('hsis_width', $hsis_width );
			update_option('hsis_height', $hsis_height );
			update_option('hsis_bgcolor', $hsis_bgcolor );
			update_option('hsis_speed', $hsis_speed );
			update_option('hsis_dir', $hsis_dir );
			?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.', 'horizontal-scroll-image-slideshow'); ?></strong></p>
			</div>
			<?php
		}
		?>
		<h3><?php _e('Plugin setting', 'horizontal-scroll-image-slideshow'); ?></h3>
		<form name="hsis_form" method="post" action="#">
			
			<label for="tag-title"><?php _e('Title', 'horizontal-scroll-image-slideshow'); ?></label>
			<input name="hsis_title" type="text" value="<?php echo $hsis_title; ?>"  id="hsis_title" size="70" maxlength="100">
			<p><?php _e('Please enter your widget title.', 'horizontal-scroll-image-slideshow'); ?></p>
			
			<label for="tag-title"><?php _e('Width', 'horizontal-scroll-image-slideshow'); ?></label>
			<input name="hsis_width" type="text" value="<?php echo $hsis_width; ?>"  id="hsis_width" maxlength="4">
			<p><?php _e('Please enter your slideshow width. <br />This width should be the largest image width in your slideshow.', 'horizontal-scroll-image-slideshow'); ?> (Example: 205)</p>
			
			<label for="tag-title"><?php _e('Height', 'horizontal-scroll-image-slideshow'); ?></label>
			<input name="hsis_height" type="text" value="<?php echo $hsis_height; ?>"  id="hsis_height" maxlength="4">
			<p><?php _e('Please enter your slideshow height, Only Number.', 'horizontal-scroll-image-slideshow'); ?> (Example: 150)</p>
			
			<label for="tag-title"><?php _e('Bgcolor', 'horizontal-scroll-image-slideshow'); ?></label>
			<input name="hsis_bgcolor" type="text" value="<?php echo $hsis_bgcolor; ?>"  id="hsis_bgcolor" maxlength="20">
			<p><?php _e('Please enter slideshow bgcolor,', 'horizontal-scroll-image-slideshow'); ?> (Example: white)</p>
			
			<label for="tag-title"><?php _e('Speed', 'horizontal-scroll-image-slideshow'); ?></label>
			<input name="hsis_speed" type="text" value="<?php echo $hsis_speed; ?>"  id="hsis_speed" maxlength="5">
			<p><?php _e('Please enter your slideshow speed, Only Number.', 'horizontal-scroll-image-slideshow'); ?> (Example: 2000)</p>
			
			<label for="tag-title"><?php _e('Gallery XML file path', 'horizontal-scroll-image-slideshow'); ?></label>
			<input name="hsis_dir" type="text" value="<?php echo $hsis_dir; ?>"  id="hsis_dir" size="70" maxlength="1000">
			<p><?php _e('Please enter gallery XML file path.', 'horizontal-scroll-image-slideshow'); ?></p>
			<?php $hsis_pluginurl = plugins_url() . "/horizontal-scroll-image-slideshow/gallery/widget.xml"; ?>
			<p><?php _e('Example : ', 'horizontal-scroll-image-slideshow'); ?><?php echo $hsis_pluginurl; ?></p>
			
			<br />
			<input type="hidden" name="hsis_form_submit" value="yes"/>
			<input name="hsis_submit" id="hsis_submit" class="button" value="Submit" type="submit" />
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/"><?php _e('Help', 'horizontal-scroll-image-slideshow'); ?></a>
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/"><?php _e('Short Code', 'horizontal-scroll-image-slideshow'); ?></a>
			<?php wp_nonce_field('hsis_form_setting'); ?>
		</form>
		</div>
	</div>
	<?php
}

function hsis_control()
{
	echo '<p><b>';
	_e('Horizontal scroll image slideshow', 'horizontal-scroll-image-slideshow');
	echo '.</b> ';
	_e('Check official website for more information', 'horizontal-scroll-image-slideshow');
	?> <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/horizontal-scroll-image-slideshow/"><?php _e('click here', 'horizontal-scroll-image-slideshow'); ?></a></p><?php
}

function hsis_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('horizontal-scroll-image-slideshow', __('Horizontal scroll image slideshow', 'horizontal-scroll-image-slideshow'), 'hsis_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('horizontal-scroll-image-slideshow', 
				array( __('Horizontal scroll image slideshow', 'horizontal-scroll-image-slideshow'), 'widgets'), 'hsis_control');
	} 
}

function hsis_deactivation() 
{
	delete_option('hsis_title');
	delete_option('hsis_width');
	delete_option('hsis_height');
	delete_option('hsis_bgcolor');
	delete_option('hsis_speed');
	delete_option('hsis_dir');
}

function hsis_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page( __('Horizontal scroll image slideshow', 'horizontal-scroll-image-slideshow'), 
				__('Horizontal scroll image slideshow', 'horizontal-scroll-image-slideshow'), 'manage_options', 'horizontal-scroll-image-slideshow', 'hsis_admin_option' );
	}
}

function hsis_textdomain() 
{
	  load_plugin_textdomain( 'horizontal-scroll-image-slideshow', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_shortcode('horizontal-scroll-image-slideshow', 'hsis_show_shortcode');
add_action('plugins_loaded', 'hsis_textdomain');
add_action('admin_menu', 'hsis_add_to_menu');
add_action("plugins_loaded", "hsis_widget_init");
register_activation_hook(__FILE__, 'hsis_install');
register_deactivation_hook(__FILE__, 'hsis_deactivation');
add_action('init', 'hsis_widget_init');
?>