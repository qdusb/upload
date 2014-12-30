package 
{

	import flash.display.MovieClip;
	import flash.events.*;
	import flash.net.URLRequest;
	import flash.net.FileFilter;
	import flash.net.FileReferenceList;
	import flash.net.FileReference;
	import ibw.com.systm.URLLocation;
	import flash.net.URLVariables;
	import flash.net.URLRequestMethod;
	import flash.utils.setTimeout;
	import com.greensock.TweenLite;
	import flash.system.Security;
	import flash.external.ExternalInterface;

	public class UpFileFlash extends MovieClip
	{

		private var file_list:FileReferenceList;
		private var imgfilter:FileFilter;
		private var isSelect:Boolean;
		private var urlRequest:URLRequest=new URLRequest();
		private static var fileMc:MovieClip=new MovieClip();
		private var maskMc:MovieClip=new MovieClip();
		public static var totalFileArr:Array=new Array();
		private var url:URLLocation;
		private var rooturl:String = String(stage.loaderInfo.parameters["url"]);
		private var id:String = String(stage.loaderInfo.parameters["id"]);
		private var sid:String = String(stage.loaderInfo.parameters["sid"]);
		private var user_id:String = String(stage.loaderInfo.parameters["user_id"]);
		
		private var ext:String= String(stage.loaderInfo.parameters["ex_args"]);
		private var upload_dir:String= String(stage.loaderInfo.parameters["upload_dir"]);
		private var to_tmp_flag:String= String(stage.loaderInfo.parameters["to_tmp_flag"]);
	
		private  var _size:int= int(stage.loaderInfo.parameters["size"]);
		private var maxFileNum:int= int(stage.loaderInfo.parameters["maxfile"]);
		
		public static var exts:Array=new Array();
		public static var imageSeri:int;
		public static var sBar;
		public static var size:int;
		public static var sucess:Array=new Array();
		
		public function UpFileFlash()
		{
			stage.align="TL";
			Security.allowDomain("*");
			Security.allowInsecureDomain("*");
			//var style=ExternalInterface.call("challs_flash_style");
			init();
		}
		public function uploadExt(ext:String){
			
		}
		private function init():void
		{
			
			var urlStr:String=this.loaderInfo.url.toString();
			var a_url:String=urlStr.slice(0,urlStr.lastIndexOf("/")+1);
			//rooturl="http://localhost/myapp/include/upload.php";

			if(maxFileNum==0){
				maxFileNum=20;
			}
			
			if(ext=="undefined"){
				ext="jpg,png,gif";
			}
			if(_size==0){
				size=1024*1024*2;
			}else{
				size=_size;
			}
			exts=ext.split(",");
			var temp:Array=new Array();
			for(var i:int=0;i<exts.length;i++){
				temp[i]="*."+exts[i];
			}
			var extsStr:String=temp.join(";");

			url=new URLLocation();
			urlRequest.url=rooturl;
			var variables:URLVariables = new URLVariables();
			
      		variables.id=id;
			variables.sid=sid;
			variables.user_id=user_id;
			variables.upload_dir=upload_dir;
			variables.to_tmp_flag=to_tmp_flag;
			variables.ex_args=ext;
			
        	urlRequest.method=URLRequestMethod.POST;
        	urlRequest.data=variables;
			
			
			file_list = new FileReferenceList();
			imgfilter = new FileFilter("图像文件("+extsStr+")",extsStr);

			file_list.addEventListener(Event.SELECT,select);
			browBtn.addEventListener(MouseEvent.CLICK,selectFile);			
			uploadBtn.addEventListener(MouseEvent.CLICK,uploadFile);
			cleanBtn.addEventListener(MouseEvent.CLICK,cleanEvt);
			
			this.addChild(fileMc);
			fileMc.y=76;
			fileMc.x=22;
			
			maskMc=new MovieClip();
			maskMc.y=76;
			maskMc.x=25;
			maskMc.graphics.beginFill(0xFFFF00);
			maskMc.graphics.drawRect(0,0,398,220);
			maskMc.graphics.endFill();
			this.addChild(maskMc);
			fileMc.mask=maskMc;
			slideBar.visible=false;
			sBar=slideBar;
			
		}
		private function closeSwfEvt(e:MouseEvent){
			ExternalInterface.call("closeSwf");
		}
		function cleanEvt(e:MouseEvent):void
		{
			var sf=ExternalInterface.call("challs_flash_deleteAllFiles");
			if(sf==true)
			{
				totalFileArr=[];
				while(fileMc.numChildren>0)
				{
					fileMc.removeChildAt(0);
				}
				slideBar.visible=false;
			}
			
		}
		function selectFile(e:MouseEvent):void
		{
			file_list.browse([imgfilter]);
		}
		function select(e:Event):void
		{
			isSelect = true;
			for each (var file:FileReference in file_list.fileList)
			{
				var upfile:UpFileClass = new UpFileClass(file);
				fileMc.addChild(upfile);
				upfile.x = 130*(totalFileArr.length%3)+12;
				upfile.y = 164*Math.floor(totalFileArr.length/3)+6;
				totalFileArr.push(upfile);
			}
			resetPose();
		}
		public static function resetPose()
		{
			while(fileMc.numChildren>0)
			{
				fileMc.removeChildAt(0);
			}
			var length:int=totalFileArr.length;
			for(var i:int=0;i<totalFileArr.length;i++)
			{
				var file:UpFileClass=totalFileArr[i];
				fileMc.addChild(file);
				var posX= 130*(i%3)+12;
				var posY= 164*Math.floor(i/3)+6;
				TweenLite.to(file,0.3,{x:posX,y:posY});
			}
			sBar.visible=false;
			setTimeout(resetBar,500);
		}
		public static function resetBar()
		{
			sBar.setObjectBar(fileMc,200);
		}
		function uploadFile(e:MouseEvent)
		{
			trace(totalFileArr.length);
			if(maxFileNum<totalFileArr.length){
				ExternalInterface.call("challs_flash_maxFile",totalFileArr.length);
				return;
			}
			if (isSelect&&totalFileArr.length>0)
			{
				for each (var file:UpFileClass in totalFileArr)
				{
					file.addEventListener(UpFileClass.UPLOAD_COMPLETE,upCompleteEvt);
				}
				for each (file in totalFileArr)
				{
					if(file.isUpload){
						file.upFile(urlRequest);
						break;
					}
				}
			}
		}
		public function upCompleteEvt(e:Event):void
		{
			var file:UpFileClass=e.currentTarget as UpFileClass;
			var seri:int=totalFileArr.indexOf(file);
			if(seri==totalFileArr.length-1)
			{
				ExternalInterface.call("challs_flash_onCompleteAll",file_list.fileList.length-sucess.length);
				sucess=[];
				totalFileArr=[];
				while(fileMc.numChildren>0)
				{
					fileMc.removeChildAt(0);
				}
				slideBar.visible=false;
				return;
			}
			else
			{
				for(var i:int=seri+1;i<=totalFileArr.length-1;i++)
				{
					if(totalFileArr[i].isUpload){
						totalFileArr[i].upFile(urlRequest);
						break;
					}
				}
				
			}
		}
	}
}