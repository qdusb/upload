<!-- 弹窗 -->
<?php
$input_tips = "提交您的实际体验，可以给更多的用户消费参考噢。优质点评可获得1-10个万家币奖励！";
?>
<!-- #发布区 -->
<div class="comments_pub b1">
  <?php if ($show_name):?>
  <h2 class="fy fwn"><a href="<?php echo $newshop->getUrl();?>" style="color:#E64245;" target="_blank" title="<?php echo $newshop->getName();?>"><?php echo $newshop->getName();?></a> <span class="fz12 cor_2 ml10">(<span class="mo cor_6 mar2">*</span>号为必填)</span></h2>
  <?php endif;?>
  <div class="comments_pub_bg ov"> 
  <ul>
    <form  name="publish_comment" id="publish_comment" method="post" action="/newshop/default/publishComment?id=<?=$id?>" target="_top" enctype="multipart/form-data">
    <input type="hidden" value="<?php echo $sf_user->isAuthenticated()?1:2?>" id="is_logined" name="is_logined">    
    <div style="width:10px; height:10px; overflow: hidden;">
    <input type="hidden" name="submigFlag" value="publish">
    <input type="hidden" name="id" value="<?=$id?>">
    <input type="hidden" name="cur_act" id="cur_act" value=""/>
    <input type="hidden" name="from" value="<?=$from?>">
    <input type="hidden" name="coupon_id" value="<?=$coupon_id?>">
    <input type="hidden" name="show_more_button" value="<?=$show_more_button?>">
    <input type="hidden" name="errorcode" id="errorcode" value="1" />
    <input type="hidden" name="comment_type" id="comment_type" value="1" />
    <input type="hidden" name="limit" id="limit" value="<?=$limit?>" />
    <input type="hidden" name="backUrl" id="backUrl" value="<?php echo $backUrl?$backUrl:sfConfig::get('site_url').htmlentities($_SERVER["REQUEST_URI"]);?>"/>
    <input type="hidden" name="shop_grade" id="shop_grade" value="0"/>
    </div>
    <li>
      <input type="radio" name="is_gone" id="is_gone" value="1" checked="checked" onclick="sotrMore_1();"/>
      <label for="radio_co_1" class="">我去过，我要点评</label>
      <input type="radio" name="is_gone" value="0" id="is_gone" onclick="sotrMore_2();" />
      <label for="radio_co_2" class="">我想去，我说两句</label>
    </li>
    
    
    <li id="pop_01">
	  <span class="mo cor_6">*</span><label class="ml10"><?php echo $newshop->getTempSortTitle();?>评分：</label>
	  <span id="sp_grades"><?=NewshopPeer::DynamicStar('dp', 'dianping', 'shop_grade', '/images/newshop/img/icon_star_big_null.gif')?></span>
	  <span class="fwb cor_co_2 ml10" id="newshop_score">点击星星为商户打分</span>
	</li>
	
	<li id="pop_02">
	
      <?php if($showPerCost):?>
         <span class="mo cor_6">*</span><label class="ml10">人均消费：</label><input type="text" name="avg_fee" id="avg_fee_cm" class="ipt_co_2" /><label class="ml5">元</label>
      <?php else:?>
      <input type="hidden" id="avg_fee_cm" name="avg_fee" value="0"/>
      <?php endif;?>
	</li>
	
	<li class="cf">
				  <span class="mo cor_6 fl">*</span>
				  <label class="ml10 fl">评论内容：</label>
				  <div class="ov">
					<textarea name="content" onkeydown="countChar();" onkeyup="countChar();" id="content"   class="textarea_co" ><?php if($sf_context->getUser()->isAuthenticated()):?><?=$input_tips?><?php endif;?></textarea>
					<?php if(!$sf_context->getUser()->isAuthenticated()):?>
					<span class="login_tags">您需要登录后才可以评论<a target="_self" onclick='return checkForm(<?php echo $showPerCost?'1':'0'?>, "login")'  href="javascript:;">登录</a>|<a href="<?php echo sfConfig::get("site_url")?>/user/signup/step1" title="">注册</a></span>
					<?php endif;?>
				  </div>
				  <div style="padding-left: 75px;" class="mt15">
  				  <span id="msg_min_nums" class="mt15 dn">店评最少10个字符，您还需输入 <span class="fwb cor_10 "></span> 个字符!</span>
  				  <span id="msg_max_nums" class="mt15 dn">您还可以输入 <span class="fwb cor_10"></span> 个字符!</span>
				  </div>
	</li>
	

   <li class="cf" id="pop_03">
	<span class="mo fl">&ensp;</span><label class="ml10 fl">店铺印象：</label>
	  <div class="dit" style="padding-left:5px">
		<p><input type="text" class="ipt_co_3"  name="impress" id="impress"/><span class="cor_co_2 ml10">请不要含有特殊字符，多个印象用空格隔开</span></p>
		<?php include_partial('default/impress', array('newshop'=>$newshop, 'from'=>'general'))?>
	  </div>
	</li>

    <li class="cf">    
      <table width="98%">
        <tr>
          <td>
            <!-- 
            <div id="pop_04" class="file-box mt10"><span class="mo fl">&nbsp;</span><label class="ml10 fl">上传图片：</label>
              <input type='text' name='txtPicturePath' id='txtPicturePath' class='txt' />  
              <input type='button'  class ='btn'  value ='浏览...' style="background: #fff;"/>
              <input type="file" name="picture" class="file" id="picture" size="28" onchange="document.getElementById('txtPicturePath').value=this.value" />支持上传单张2M以下的gif、jpg、png文件
            </div>
             -->
            <div  class="file-box mt10" id="pop_05"><span class="mo fl">&nbsp;</span><label class="ml10 fl">消费方式：</label>
            <?php if($type=="coupon"):?>
             <input type="hidden"  name="comment_from" id="comment_from" value="<?php echo NewshopCommentPeer::COMMENT_FROM_COUPON?>" class="ipt_chect" />持万家优惠券消费
            <?php else:?>
               <input type="checkbox" class='ipt_chect' name="comment_from" id="comment_from" value="<?php echo NewshopCommentPeer::COMMENT_FROM_COUPON?>" /> 持万家优惠券消费
            <?php endif;?>
            </div>
            
             <?php if($type=="coupon" && count($coupon_shops)>2):?>
              <div  class="mt10" id="pop_06"><label class="ml10 fl">消费店铺：</label>
              <select name="selected_shop_id">
             <?php foreach ($coupon_shops as $coupon_shop):?>
             <option value="<?=$coupon_shop->getId()?>"><?=$coupon_shop->getName()?></option>>
             <?php endforeach;?>
               </select>
              </div>
             <?php endif;?>
          </td>
        </tr>
      </table>
    </li>
    <?php include_partial("default/comments_upload_photos");?>
    <li class="pl20">
    <span style="padding-left:55px"><a class="btn_co_2 fy" id="btn_publish_comment" onclick='return checkForm(<?php echo $showPerCost?'1':'0'?>,"pub")' style="cursor: pointer">发表评论</a></span>
    </li>
    </form>
  </ul> 
</div><!-- #comments_pub_bg -->
</div><!-- #comments_pub -->
 <?php
  $url=sfConfig::get("site_url")."/newshop/ajax/uploadCommentPhotos";
  $flashvars="url={$url}&id={$id}&ext=jpg,png,gif&size=1000000&sid=".session_id()."&user_id=".$sf_user->getUserId()."&maxfile=30";
  ?>
<div class="upload_pic_photo wrap_out" style="position: absolute; top: -5000px; width: 442px; visibility:hidden;">
	<div class="wrap_in"><a href="javascript:;" target="_self" class="wrap_close" onclick="hide_upload_pic();">×</a></div>
	<div class="wrap_bar" onselectstart="return false;"><div class="wrap_title"><span>商品图片上传(最多可上传9张图片)</span></div>
	<div class="wrap_body" style="width: 440px;">
		<div>
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="442" height="330" id="update" align="middle">
    	<param name="allowFullScreen" value="false">
    	<param name="allowScriptAccess" value="always">
    	<param name="movie" value="<?php echo sfConfig::get("site_url")?>/upload_wj.swf?rand=<?php echo time()?>">
      <param name="quality" value="high">
      <param name="wmode" value="opaque">
      <param name="bgcolor" value="#ffffff">
      <param name="flashvars" value="<?php echo $flashvars?>"/>
      <embed wmode="opaque" flashvars="<?php echo $flashvars?>"  src="<?php echo sfConfig::get("site_url")?>/upload_wj.swf?rand=<?php echo time()?>" quality="high" bgcolor="#DBE5F1" width="442" height="320" name="update" align="middle" allowscriptaccess="always" allowfullscreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
    </object>
		<div class="div_hide_flash"></div>
	  <div style="position:absolute;left:2px;bottom:4px;width:99%;height:16px;background:#DBE5F1;"></div>
	  </div>
  </div>
  </div>
  <input type="hidden" name="img_nums" id="img_nums" value="0"/>
</div>
	
	
<style>
  .pic_tag { height: 123px; }
  .wrap_out{position:absolute;z-index:2000;padding:5px;background:#eee;box-shadow:0 0 6px rgba(0,0,0,.5);-moz-box-shadow:0 0 6px rgba(0,0,0,0.5);-webkit-box-shadow:0 0 6px rgba(0,0,0,.5);}
  wrap_in{background:#fafafa;border:1px solid #ccc;}
  .wrap_bar{background:#f4f4f4;border-bottom:1px solid #ddd;line-height:26px;}
  .wrap_title{display:inline-block;margin-left:10px;cursor:text;}
  .wrap_close{cursor:pointer;float:right;margin-right:10px;color:#999;font-weight:bold;text-decoration:none;cursor:pointer;font-size:1.5em;}
</style>
 <script type="text/javascript">
 var requestUploadUrl = "<?php echo sfConfig::get("site_url")?>/newshop/ajax/uploadCommentPhotos?sid=<?php echo session_id();?>&user_id=<?php echo $sf_user->getUserId();?>";
 </script>
 
 
 <script language="javascript"> 
 
 $(function(){
   if($.cookie("upload_photo")) {
     $.cookie("upload_photo", ''); 
     pop_upload_pic();
   }
 });

 function challs_flash_update(){ //Flash 初始化函数
 	var a={};
 	//定义变量为Object 类型

 	a.title = "上传文件"; //设置组件头部名称
 	
 	a.FormName = "Filedata";
 	//设置Form表单的文本域的Name属性
 	
 	a.url = requestUploadUrl; 
 	//设置服务器接收代码文件
 	
 	a.parameter = ""; 
 	//设置提交参数，以GET形式提交,例："key=value&key=value&..."
 	
 	a.typefile = ["Images (*.gif,*.png,*.jpg,*jpeg)","*.gif;*.png;*.jpg;*.jpeg;",
 				"GIF (*.gif)","*.gif;",
 				"PNG (*.png)","*.png;",
 				"JPEG (*.jpg,*.jpeg)","*.jpg;*.jpeg;"];
 	//设置可以上传文件 数组类型
 	//"Images (*.gif,*.png,*.jpg)"为用户选择要上载的文件时可以看到的描述字符串,
 	//"*.gif;*.png;*.jpg"为文件扩展名列表，其中列出用户选择要上载的文件时可以看到的 Windows 文件格式，以分号相隔
 	//2个为一组，可以设置多组文件类型
 	
 	a.newTypeFile = ["Images (*.gif,*.png,*.jpg,*jpeg)","*.gif;*.png;*.jpg;*.jpeg;","JPE;JPEG;JPG;GIF;PNG",
 				"GIF (*.gif)","*.gif;","GIF",
 				"PNG (*.png)","*.png;","PNG",
 				"JPEG (*.jpg,*.jpeg)","*.jpg;*.jpeg;","JPE;JPEG;JPG"];
 	//设置可以上传文件，多了一个苹果电脑文件类型过滤 数组类型, 设置了此项，typefile将无效
 	//"Images (*.gif,*.png,*.jpg)"为用户选择要上载的文件时可以看到的描述字符串,
 	//"*.gif;*.png;*.jpg"为文件扩展名列表，其中列出用户选择要上载的文件时可以看到的 Windows 文件格式，以分号相隔
 	//"JPE;JPEG;JPG;GIF;PNG" 分号分隔的 Macintosh 文件类型列表，如下面的字符串所示："JPEG;jp2_;GI
 	
 	a.UpSize = 0;
 	//可限制传输文件总容量，0或负数为不限制，单位MB
 	
 	a.fileNum = 0;
 	//可限制待传文件的数量，0或负数为不限制
 	
 	a.size = 5;
 	//上传单个文件限制大小，单位MB，可以填写小数类型
 	
 	a.FormID = ['select','select2'];
 	//设置每次上传时将注册了ID的表单数据以POST形式发送到服务器
 	//需要设置的FORM表单中checkbox,text,textarea,radio,select项目的ID值,radio组只需要一个设置ID即可
 	//参数为数组类型，注意使用此参数必须有 challs_flash_FormData() 函数支持
 	
 	a.autoClose = 1;
 	//上传完成条目，将自动删除已完成的条目，值为延迟时间，以秒为单位，当值为 -1 时不会自动关闭，注意：当参数CompleteClose为false时无效
 	
 	a.CompleteClose = true;
 	//设置为true时，上传完成的条目，将也可以取消删除条目，这样参数 UpSize 将失效, 默认为false
 	
 	a.repeatFile = true;
 	//设置为true时，可以过滤用户已经选择的重复文件，否则可以让用户多次选择上传同一个文件，默认为false
 	
 	a.returnServer = true;
 	//设置为true时，组件必须等到服务器有反馈值了才会进行下一个步骤，否则不会等待服务器返回值，直接进行下一步骤，默认为false
 	
 	a.MD5File = 1;
 	//设置MD5文件签名模式，参数如下 ,注意：FLASH无法计算超过100M的文件,在无特殊需要时，请设置为0
 	//0为关闭MD5计算签名
 	//1为直接计算MD5签名后上传
 	//2为计算签名，将签名提交服务器验证，在根据服务器反馈来执行上传或不上传
 	//3为先提交文件基本信息，根据服务器反馈，执行MD5签名计算或直接上传，如果是要进行MD5计算，计算后，提交计算结果，在根据服务器反馈，来执行是否上传或不上传
 	
 	a.loadFileOrder=true;
 	//选择的文件加载文件列表顺序，TRUE = 正序加载，FALSE = 倒序加载
 	
 	a.mixFileNum=0;
 	//至少选择的文件数量，设置这个将限制文件列表最少正常数量（包括等待上传和已经上传）为设置的数量，才能点击上传，0为不限制
 	
 	a.ListShowType = 2;
 	//文件列表显示类型：1 = 传统列表显示，2 = 缩略图列表显示（适用于图片专用上传）
 	
 	a.InfoDownRight = "";
 	//右下角统计信息的文本设置,文本中的 %1% = 等待上传数量的替换符号，%2% = 已经上传数量的替换符号，例子“等待上传：%1%个  已上传：%2%个”
 	
 	a.TitleSwitch = true;
 	//是否显示组件头部
 	
 	a.ForceFileNum = 0;
 	//强制条目数量，已上传和待上传条目相加等于为设置的值（不包括上传失败的条目），否则不让上传, 0为不限制，设置限制后mixFileNum,autoClose和fileNum属性将无效！
 	
 	a.autoUpload = false;
 	//设置为true时，用户选择文件后，直接开始上传，无需点击上传，默认为false;
 	
 	a.adjustOrder = true;
 	//设置为true时，用户可以拖动列表，重新排列位置
 	
 	a.deleteAllShow = true
 	//设置是否显示，全部清除按钮
 	 
 	a.language = 0; 
 	//语言包控制，0 自动检测 1 简体中文，2 繁体中文 3 英文
 	
 	a.countData = true;
 	//是否向服务器端提交组件文件列表统计信息，POST方式提交数据
 	//access2008_box_info_max 列表总数量
 	//access2008_box_info_upload 剩余数量 （包括当前上传条目）
 	//access2008_box_info_over 已经上传完成数量 （不包括当前上传条目)
 	
 	a.isShowUploadButton = true;
 	//是否显示上传按钮，默认为true
 	
 	return a ;
 	//返回Object
 }

 function challs_flash_onComplete(a){ //每次上传完成调用的函数，并传入一个Object类型变量，包括刚上传文件的大小，名称，上传所用时间,文件类型
 	var name=a.fileName; //获取上传文件名
 	var size=a.fileSize; //获取上传文件大小，单位字节
 	var time=a.updateTime; //获取上传所用时间 单位毫秒
 	var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
 	var creationDate = a.fileCreationDate //获取文件创建时间
 	var modificationDate = a.fileModificationDate //获取文件最后修改时间 
 	//document.getElementById('show').innerHTML+=name+' --- '+size+'字节 ----文件类型：'+type+'--- 用时 '+(time/1000)+'秒<br><br>'
 }

 function challs_flash_onCompleteData(a){ //获取服务器反馈信息事件
   $(".btn_add_img").before(a);
   $("#li_upload_pic").hide();
   if($(".comment_img_item").length >= 9) {
     $(".btn_add_img").hide();
   }
  //document.getElementById('show').innerHTML+=a;
 	//document.getElementById('show').innerHTML+='<font color="#ff0000">服务器端反馈信息：</font><br />'+a+'<br />';	
 }
 function challs_flash_onStart(a){ //开始一个新的文件上传时事件,并传入一个Object类型变量，包括刚上传文件的大小，名称，类型
  if((parseInt($("#img_nums").val())+$(".comment_img_item").length)>9) {
    alert("最多只可以上传9张图片，请先删除部分图片再进行上传操作");
    return false;
  }
 	var name=a.fileName; //获取上传文件名
 	var size=a.fileSize; //获取上传文件大小，单位字节
 	var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
 	var creationDate = a.fileCreationDate //获取文件创建时间
 	var modificationDate = a.fileModificationDate //获取文件最后修改时间
 	//document.getElementById('show').innerHTML+=name+'开始上传！<br />';
 	
 	return true; //返回 false 时，组件将会停止上传
 }

 function challs_flash_onStatistics(a){ //当组件文件数量或状态改变时得到数量统计，参数 a 对象类型
 	var uploadFile = a.uploadFile; //等待上传数量
 	var overFile = a.overFile; //已经上传数量
 	var errFile = a.errFile; //上传错误数量
 	$("#img_nums").val(uploadFile);
 	return false;
 	
 }

 function challs_flash_alert(a){ //当提示时，会将提示信息传入函数，参数 a 字符串类型
   //alert(a);
 	//document.getElementById('show').innerHTML+='<font color="#ff0000">组件提示：</font>'+a+'<br />';
 }

 function challs_flash_onCompleteAll(a){ //上传文件列表全部上传完毕事件,参数 a 数值类型，返回上传失败的数量
   $("#upload_photo_list").show();
   hide_upload_pic();
   //$("#no_photo_tip").hide();
 	//document.getElementById('show').innerHTML+='<font color="#ff0000">所有文件上传完毕，</font>上传失败'+a+'个！<br />';
 	//window.location.href='http://www.access2008.cn/update'; //传输完成后，跳转页面
 }

 function challs_flash_onSelectFile(a){ //用户选择文件完毕触发事件，参数 a 数值类型，返回等待上传文件数量
 	//document.getElementById('show').innerHTML+='<font color="#ff0000">文件选择完成：</font>等待上传文件'+a+'个！<br />';
 }

 function challs_flash_deleteAllFiles(){ //清空按钮点击时，出发事件
 	return confirm("你确定要清空列表吗?");
 }

 function challs_flash_onError(a){ //上传文件发生错误事件，并传入一个Object类型变量，包括错误文件的大小，名称，类型
 	var err=a.textErr; //错误信息
 	var name=a.fileName; //获取上传文件名
 	var size=a.fileSize; //获取上传文件大小，单位字节
 	var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
 	var creationDate = a.fileCreationDate //获取文件创建时间
 	var modificationDate = a.fileModificationDate //获取文件最后修改时间
 	//document.getElementById('show').innerHTML+='<font color="#ff0000">'+name+' - '+err+'</font><br />';
 }

 function challs_flash_FormData(a){ // 使用FormID参数时必要函数
 	try{
 		var value = '';
 		var id=document.getElementById(a);
 		if(id.type == 'radio'){
 			var name = document.getElementsByName(id.name);
 			for(var i = 0;i<name.length;i++){
 				if(name[i].checked){
 					value = name[i].value;
 				}
 			}
 		}else if(id.type == 'checkbox'){
 			var name = document.getElementsByName(id.name);
 			for(var i = 0;i<name.length;i++){
 				if(name[i].checked){
 					if(i>0) value+=",";
 					value += name[i].value;
 				}
 			}
 		}else if(id.type == 'select-multiple'){
 		    for(var i=0;i<id.length;i++){
 		        if(id.options[i].selected){
 					if(i>0) value+=",";
 			         values += id.options[i].value; 
 			    }
 		    }
 		}else{
 			value = id.value;
 		}
 		return value;
 	 }catch(e){
 		return '';
 	 }
 }

 function challs_flash_style(){ //组件颜色样式设置函数
 	var a = {};
 	
 	/*  整体背景颜色样式 */
 	a.backgroundColor=['#f6f6f6','#f3f8fd','#dbe5f1'];	//颜色设置，3个颜色之间过度
 	a.backgroundLineColor='#5576b8';					//组件外边框线颜色
 	a.backgroundFontColor='#066AD1';					//组件最下面的文字颜色
 	a.backgroundInsideColor='#FFFFFF';					//组件内框背景颜色
 	a.backgroundInsideLineColor=['#e5edf5','#34629e'];	//组件内框线颜色，2个颜色之间过度
 	a.upBackgroundColor='#ffffff';						//上翻按钮背景颜色设置
 	a.upOutColor='#000000';								//上翻按钮箭头鼠标离开时颜色设置
 	a.upOverColor='#FF0000';							//上翻按钮箭头鼠标移动上去颜色设置
 	a.downBackgroundColor='#ffffff';					//下翻按钮背景颜色设置
 	a.downOutColor='#000000';							//下翻按钮箭头鼠标离开时颜色设置
 	a.downOverColor='#FF0000';							//下翻按钮箭头鼠标移动上去时颜色设置
 	
 	/*  头部颜色样式 */
 	a.Top_backgroundColor=['#e0eaf4','#bcd1ea']; 		//颜色设置，数组类型，2个颜色之间过度
 	a.Top_fontColor='#245891';							//头部文字颜色
 	
 	
 	/*  按钮颜色样式 */
 	a.button_overColor=['#FBDAB5','#f3840d'];			//鼠标移上去时的背景颜色，2个颜色之间过度
 	a.button_overLineColor='#e77702';					//鼠标移上去时的边框颜色
 	a.button_overFontColor='#ffffff';					//鼠标移上去时的文字颜色
 	a.button_outColor=['#ffffff','#dde8fe']; 			//鼠标离开时的背景颜色，2个颜色之间过度
 	a.button_outLineColor='#91bdef';					//鼠标离开时的边框颜色
 	a.button_outFontColor='#245891';					//鼠标离开时的文字颜色
 	
 	/* 文件列表样式 */
 	a.List_scrollBarColor="#000000"						//列表滚动条颜色
 	a.List_backgroundColor='#EAF0F8';					//列表背景色
 	a.List_fontColor='#333333';							//列表文字颜色
 	a.List_LineColor='#B3CDF1';							//列表分割线颜色
 	a.List_cancelOverFontColor='#ff0000';				//列表取消文字移上去时颜色
 	a.List_cancelOutFontColor='#D76500';				//列表取消文字离开时颜色
 	a.List_progressBarLineColor='#B3CDF1';				//进度条边框线颜色
 	a.List_progressBarBackgroundColor='#D8E6F7';		//进度条背景颜色	
 	a.List_progressBarColor=['#FFCC00','#FFFF00'];		//进度条进度颜色，2个颜色之间过度
 	
 	/* 错误提示框样式 */
 	a.Err_backgroundColor='#C0D3EB';					//提示框背景色
 	a.Err_fontColor='#245891';							//提示框文字颜色
 	a.Err_shadowColor='#000000';						//提示框阴影颜色
 	
 	
 	return a;
 }


 var isMSIE = (navigator.appName == "Microsoft Internet Explorer");   
 function thisMovie(movieName){   
   if(isMSIE){   
   	return window[movieName];   
   }else{
   	return document[movieName];   
   }   
 }
 </script>
 
<script>
$(document).ready(function(){
  $("#content").focus(function(){
      if($("#content").val()=='<?=$input_tips?>')
      {
          this.style.color='#666';
          $("#content").val("");
      }
   });
    $("#content").blur(function(){
       if($("#content").val()=='')
      {
          $("#content").val("<?=$input_tips?>");
      }
   });
})

</script>
<script type="text/javascript" src="/js/newshop/comments.js?v=1"></script>
