<?php defined( 'SYSPATH' ) OR die( 'No direct access allowed.' ); ?>
<section id="helpLoading" ><img src="<?php echo url::base(); ?>images/template/keyboardHelp.png"  alt="Help" width="1000" height="649" border="0" usemap="#help_keyboardMap" id="help_keyboard" />
  <map name="help_keyboardMap" id="help_keyboardMap">
    <area shape="poly" coords="183,170,260,190,344,233,326,187,324,150,332,106,292,137,275,94,281,71,306,58,357,53,407,59,450,74,448,104,768,105,777,99,856,85,907,94,938,117,944,150,929,184,909,211,869,159,858,229,827,299,904,270,986,260,928,229,960,183,986,128,986,96,977,73,938,55,881,56,773,95,772,61,756,55,762,29,738,10,716,25,739,21,753,29,748,48,728,59,725,38,714,59,600,59,590,77,589,102,565,102,572,82,568,58,507,57,489,78,477,95,470,94,470,42,453,42,452,67,322,20,267,21,237,47,231,106,240,156" href="javascript:;" id="keyboardHelp_sort" />
    <area shape="poly" coords="68,350,90,324,104,284,113,324,162,316,308,313,308,282,289,285,273,276,271,261,282,258,279,271,291,276,326,277,344,287,344,294,357,289,394,285,394,277,408,281,412,287,488,287,488,276,503,279,504,292,523,284,545,268,579,256,622,261,633,277,626,290,606,306,645,309,610,324,576,350,569,293,594,304,606,292,600,281,573,275,545,278,545,285,563,287,560,295,552,302,552,309,444,311,440,336,426,340,414,329,410,324,392,326,380,321,346,323,316,323,260,319,179,322,117,333,130,351,130,351" href="javascript:;" id="keyboardHelp_look" />
    <area shape="poly" coords="19,448,101,401,149,352,173,304,170,389,186,437,207,465,152,444,145,479,147,514,172,532,202,537,264,536,319,527,368,512,434,488,454,489,474,492,488,505,488,523,493,522,502,508,517,491,531,495,519,505,527,510,535,505,567,505,580,522,579,487,599,488,597,520,603,508,877,507,863,492,820,487,786,492,740,489,704,468,698,455,639,471,686,429,709,378,717,426,742,457,707,453,724,475,742,480,784,480,817,475,837,475,870,483,888,500,891,480,902,505,916,499,925,484,914,469,892,476,885,474,892,466,900,456,909,463,925,469,931,477,929,501,916,516,904,520,904,528,911,533,910,545,897,552,641,553,635,608,619,598,606,579,599,551,590,552,585,577,578,551,547,552,549,563,555,569,528,568,536,560,532,535,521,549,503,551,487,535,479,543,472,558,468,582,468,591,468,601,456,603,439,609,433,587,431,558,431,538,436,535,437,499,384,522,300,545,239,560,164,563,105,549,75,516,74,461,84,440" href="javascript:;" id="keyboardHelp_move" />
    <area shape="poly" coords="276,379,274,413,292,413,305,436,317,438,319,411,387,407,388,395,394,391,390,383,361,386,366,369,348,361,336,374,352,368,358,377,352,386,344,372,339,386,297,385" href="javascript:;" id="keyboardHelp_jump" />
  </map>
</section>		
<section id="content_loading" ></section>		
<section id="notifications" ></section>		
<section id="actionModule" >Une action est possible</section>		

<!-- menu top -->
<header id="content_header" >
		<img src="<?php echo url::base(); ?>images/template/icn_user.png"  alt="icn_user" id="menu_user" width="12" height="12"/>
		<img src="<?php echo url::base(); ?>images/template/icn_logout.png"  alt="icn_logout" id="control_logout" width="12" height="12"/>
</header>

<!-- overlay action -->
<section id="content_action" ></section>		

<!-- windows items / stuffs / keys -->
<section id="content_elements" >
		<div id="load_user_item"></div>
		<div id="load_user_stuff"></div>
		<div id="load_user_key"></div>
</section>		

<!-- menu info user -->
<?php if( isset( $info_user ) && $info_user ) echo $info_user; ?>

<!-- overlay -->
<section id="overlay_global">
		<div id="overlay_close"></div>
		<div id="overlay_left">
				<div id="overlay_right">
						<div id="overlay_top">
								<div id="overlay_top_left"></div>
								<div id="overlay_top_right"></div>
								<div class="clear"></div>
						</div>
						<div id="overlay_content"></div>
						<div class="clear"></div>
						<div id="overlay_bottom">
								<div id="overlay_bottom_left"></div>
								<div id="overlay_bottom_right"></div>
								<div class="clear"></div>
						</div>
				</div>
		</div>
</section>

<!-- menu bottom -->
<?php if( isset( $bottom ) && $bottom ) : ?>
		<section id="content_footer"><?php echo $bottom; ?></section>
<?php endif ?>
		
<!-- la map -->
<section id="content_body" ></section>
<section id="alertUser"><img src="<?php echo url::base(); ?>images/template/bg-alert.png" /></section>
<section id="cible" ></section>

<div id="debugWebGL" ></div>
