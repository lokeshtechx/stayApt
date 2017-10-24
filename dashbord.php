<?php
if (session_id() == '') {
                session_start();
   }
   if(!$_SESSION['is_logged']){
   	header("Location: ./index.php");
   }

require 'header.php';

?>


<!-- Content -->
<div id="content">

    <h3 class="heading-mosaic">Send Notifications</h3>
    <div class="row-fluid1">
        <div class="span6">

            <!-- Widget -->
            <div class="widget" data-toggle="collapse-widget">

                <!-- Widget heading -->
                <!--<div class="widget-head">
                    <h4 class="heading">Send Mass Notification</h4>

                </div>-->
				
                <!-- // Widget heading END -->

                <div class="widget-body">
                    <form action="" id="notyform" method="POST">
                        <div class="row-fluid" style="display:none;">
                            <?php /*<select name="app_type" class="span12" id="select2_1">
                                <option value="-1">Select App Type</option>
                                 <option value="0" selected>Send to All App Users</option>
                                  <?php
$apps = $db->GetAppList();
foreach ($apps as $key => $app) {
  ?>
    <option value="<?php echo $app['app_type']?>"><?php echo $app['app_type']?></option>
  <?php    
}
?>

                            </select> */?>

                        </div>
						
						<div class="row-fluid ">
						<label><b>Select App Type</b></label>
                            <select name="device_type" class="selectpicker span12" id="select_device_type">
                                
                                 <option value="ios" selected>iPhone</option>
								 <option value="android" >Android</option>

                            </select>

                        </div>
						
						
						<!--<hr>
						<div class="row-fluid">
                         <select name="categories[]" class="span12" id="select2_4" multiple="true" placeholder="Select Categories">
                                
                                  <?php
								  
								
$apps = $db->getAllCategories(true);
foreach ($apps as $key => $app) {
  ?>
    <option value="<?php echo $app['id']?>"><?php echo $app['cat_name']?></option>
  <?php    
}
?>

                            </select>
							</div>-->
							  <hr>
							  
							  
                        
                        <div class="row-fluid">
                            <select class="selectpicker span12" name="type" id="notytype">
                                <option value="1">Simple Notification </option>
                                <option value="5" selected>App Notification [Save in Phone]</option>
                                <option value="3">Image Notification</option>
                               
                            </select> 
                        </div>
						<hr>
                        <div class="clearfix"></div>
                        <input type="hidden" name="send_cat"/>
                        <div class="row-fluid">
                            <input type="text" name="title" placeholder="Title" class="span12 mTitle black" />
                        </div>
                        <div class="row-fluid">
                            <input type="hidden" name="tag" placeholder="Title" class="span12 mTitle black" />
                        </div>
                        <div class="row-fluid mMessage">
                            <input type="text" name="message" placeholder="Message" class="span12 mMsg black" />
                        </div>

                        <!--<div class="row-fluid mEmotion" style="display: none;">
                            <input type="text" name="emotion" placeholder="Image Link " class="span12 mEmo black" />
                        </div>-->
                        <div class="row-fluid mLink"  style="display: none;">
                            <div class="input-prepend  span12">
                                <span class="add-on">http://</span>
                                <input id="prependedInput"  class="span10 black" name="link" placeholder="Link to be open" type="text"/>
                            </div>

                        </div>
                        <hr>
                        <div class="row-fluid">
                            <button type="button" id="btn-loading" class="btn btn-primary sendnoty" data-loading-text="Loading...">Send Now</button>


                            <div class="toggle-button pull-right" data-toggleButton-style-enabled="info">
                                <input type="checkbox" checked="checked" class="mPreview"/>
                            </div>
                            <span class="pull-right">Preview Mode&nbsp;&nbsp;</span>

                        </div>
                    </form>
                </div>
            </div>
            <!-- // Widget END -->

        </div>

        <div class="span6 mPreviewContainer" id="ios-pic">
           <div class="news_mokup offset1">
                <div style="position: absolute;top: 200px;left: 602px;color: #000;"><img src="" class="pImg" style="width:45px;height:45px;" ></div>
                <div class="pTitle" style="position: absolute;top: 200px;left: 660px;color: #000;width:250px">Title</div>
                <div class="pMsg" style="position: absolute;top: 220px;left: 660px;color: #000;width:200px">Message</div>
                <img src="./assets/images/simple-ios.png"/>
            </div>
				 
            <div class="simple_mokup offset1 hide">
                <div class="pTitle hide" style="position: absolute;top: 170px;left: 680px;color: #FFF;width:250px">Title</div>
                <div class="pMsg" style="position: absolute;top: 170px;left: 680px;color: #FFF;width:250px">Message</div>
                <img src="./assets/images/simple.png"/>
            </div>
			
			

            <div class="webview_mokup offset1 hide">

                <img src="./assets/images/webview.png"/>
            </div>

            <div class="toast_mokup offset1 hide">
                <div class="pTitle" style="position: absolute;top: 620px;left: 650px;color: #FFF;width: 250px;">Title</div>
                <img src="./assets/images/toast.png"/>
            </div>
        </div>
		
		<div class="span6 mPreviewContainer" id="android-pic">
           <div class="news_mokup offset1">
                <div style="position: absolute;top: 200px;left: 602px;color: #000;"><img src="" class="pImg" style="width:45px;height:45px;" ></div>
                <div class="pTitle" style="position: absolute;top: 200px;left: 660px;color: #000;width:250px">Title</div>
                <div class="pMsg" style="position: absolute;top: 220px;left: 660px;color: #000;width:200px">Message</div>
                <img src="./assets/images/simple-android.png"/>
            </div>
				 
            <div class="simple_mokup offset1 hide">
                <div class="pTitle hide" style="position: absolute;top: 170px;left: 680px;color: #FFF;width:250px">Title</div>
                <div class="pMsg" style="position: absolute;top: 170px;left: 680px;color: #FFF;width:250px">Message</div>
                <img src="./assets/images/simple-android.png"/>
            </div>
			
			

            <div class="webview_mokup offset1 hide">

                <img src="./assets/images/webview.png"/>
            </div>

            <div class="toast_mokup offset1 hide">
                <div class="pTitle" style="position: absolute;top: 620px;left: 650px;color: #FFF;width: 250px;">Title</div>
                <img src="./assets/images/toast.png"/>
            </div>
        </div>
        <!-- // Widget END -->

    </div>

</div>
<!-- // Content END -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$("#ios-pic").show();
		$("#android-pic").hide();
		$("#select_device_type").change(function(){
		
			var app_type_val = $("#select_device_type").val();
			//alert(app_type_val);
			if(app_type_val=="ios"){
				
				$("#android-pic").hide();
				$("#ios-pic").show();
			}else if(app_type_val=="android"){
				
				$("#ios-pic").hide();
				$("#android-pic").show();
				
			}
	});
		
		$("#notytype").change(function(){
			var nt = $("#notytype").val();
			if(nt==3){
			$(".mLink").show();
			}else{
				$(".mLink").hide();
				
			}
		});
	});
</script>
<script type="text/javascript">
    var d=document.getElementsByClassName("dashbord");
    d[0].setAttribute("class", "active");
	
	
</script>

<?php
require_once 'footer.php';
?>