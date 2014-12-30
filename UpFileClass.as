package 
{
	import flash.display.MovieClip;
	import flash.net.FileReference;
	import flash.net.URLRequest;
	import flash.events.Event;
	import flash.text.TextField;
	import flash.events.ProgressEvent;
	import flash.events.MouseEvent;
	import flash.display.Loader;
	import flash.display.BitmapData;
	import flash.display.Bitmap;
	import flash.filters.GlowFilter;
	import flash.text.TextFormat;
	import flash.events.ErrorEvent;
	import flash.events.IOErrorEvent;
	import com.greensock.TweenLite;
	import flash.external.ExternalInterface;
	import flash.events.DataEvent;

	public class UpFileClass extends MovieClip
	{

		private var file:FileReference=new FileReference();
		private var urlRequest:URLRequest;
		private var tf:TextField=new TextField();
		private var proMc:SimpleProgressMc;
		private var blackLayer:MovieClip=new MovieClip();
		private var statue:String = " 准备中";
		private var tf_statue:TextField=new TextField();
		public var shutMc:ShutBtnMc=new ShutBtnMc();
		public static var UPLOAD_COMPLETE:String="upComplete"; 
		public var loader:Loader=new Loader();
		public var isUpload:Boolean=true;
		public var start_time:Date;
		public var end_time:Date;

		public function UpFileClass(file:FileReference)
		{
			//背景绘制
			this.graphics.beginFill(0xA9B8D7,1);
			this.graphics.drawRect(-1,-1,122,152);
			this.graphics.beginFill(0xE9F0F8,1);
			this.graphics.drawRect(0,0,120,150);
			this.graphics.endFill();
			
			blackLayer.graphics.beginFill(0x000000,0.3);
			blackLayer.graphics.drawRect(-1,-1,122,152);
			blackLayer.graphics.endFill();
			blackLayer.visible=false;
			
			this.buttonMode=true;
			this.file = file;
			file.load();
			tf.appendText(file.name);
			this.addChild(tf);
			tf.x=15;
			tf.y=128;
			tf.width=100;
			tf.height=20;
			tf.setTextFormat(new TextFormat("微软雅黑",null,0x00000));
			tf.visible=false;
			
			tf_statue.x =15;
			tf_statue.multiline=false;
			tf_statue.height=15;
			tf_statue.autoSize="left";
			tf_statue.y=128;
			tf_statue.mouseEnabled=false;
			this.addChild(tf_statue);
			tf_statue.text = statue;
			
			proMc=new SimpleProgressMc();
			this.addChild(proMc);
			proMc.x=15;
			proMc.y=120;
			
			shutMc.gotoAndStop(1);
			shutMc.visible=false;
			shutMc.x=110;
			shutMc.y=10;
			shutMc.buttonMode=true;
			shutMc.addEventListener(MouseEvent.CLICK,deleteEvt);
			
			var pos:int=file.name.lastIndexOf(".");
			var ext:String=file.name.substr(pos+1);

			if (file.size > UpFileFlash.size||file.size==0)
			{
				isUpload=false;
				setStaue("[文件偏大]");
				tf_statue.textColor=0xFF0000;
			}
			else if(UpFileFlash.exts.indexOf(ext.toLocaleLowerCase())<0){
				isUpload=false;
				setStaue("["+ext+"格式不能上传]");
				tf_statue.textColor=0xFF0000;
			}
			else
			{
				isUpload=true;
				ext=ext.toLocaleLowerCase();
				if(ext=="png"||ext=="jpg"||ext=="gif"||ext=="jpeg"){
					file.addEventListener(Event.COMPLETE, readComplete);
				}else{
					//非图片预加载在此处理
					addLoadingPic(file.name);
				}
				
			}
			this.addEventListener(MouseEvent.ROLL_OVER,rollEvt);
			this.addEventListener(MouseEvent.ROLL_OUT,rollEvt);
			this.addChild(blackLayer);
		  	this.addChild(shutMc);
		}
		private function addLoadingPic(filename:String)
		{
		  var pos:int=filename.lastIndexOf(".");
		  var ext:String=filename.substr(pos+1);
		  var fname:String=filename.substr(0,pos);
		  var tf:TextField=new TextField();
		  tf.appendText(filename);
		  this.addChild(tf);
		  tf.x=15;
		  tf.y=60;
		  tf.width=100;
		  tf.height=20;
		  tf.setTextFormat(new TextFormat("微软雅黑",null,0x00000));
		
		  this.addChild(blackLayer);
		  this.addChild(shutMc);
		}
		private function rollEvt(e:MouseEvent)
		{
			if(e.type=="rollOver"){
				shutMc.visible=true;
				blackLayer.visible=true;
				tf.visible=true;
				tf_statue.visible=false;
			}else{
				blackLayer.visible=false;
				shutMc.visible=false;
				tf.visible=false;
				tf_statue.visible=true;
			}
		}
    	private function readComplete(e:Event):void
		{
			file.removeEventListener(Event.COMPLETE, readComplete);
			setStaue("[等待上传]");
			loader.loadBytes(file.data);
            loader.contentLoaderInfo.addEventListener(Event.COMPLETE,onLoadComplete);
		}
		private function onLoadComplete(e:Event):void
      	{
          var tempData:BitmapData=new BitmapData(loader.width,loader.height,false);
          tempData.draw(loader);
          var bitmap:Bitmap=new Bitmap(tempData);
		  bitmap.width=90;
		  bitmap.height=90;
		  bitmap.x=bitmap.y=15;
          addChild(bitmap);
		  bitmap.filters=[new GlowFilter(0x333333,1,3,3,5)];
		  bitmap.alpha=0;
		  TweenLite.to(bitmap,0.8,{alpha:1});
		  
		  loader.contentLoaderInfo.removeEventListener(Event.COMPLETE,onLoadComplete);
		  this.addChild(blackLayer);
		  this.addChild(shutMc);
     	 } 
		private function deleteEvt(e:MouseEvent):void
		{
			var val:int=UpFileFlash.totalFileArr.indexOf(this);
			if(val>=0){
				UpFileFlash.totalFileArr.splice(val,1);
			}
			UpFileFlash.resetPose();
			this.visible=false;
			setStaue("[取消上传...]");
			tf_statue.textColor=0xFF0000;
		}
		public function upFile(urlRequest:URLRequest):void
		{
			if (isUpload)
			{
				start_time=new Date();
				//shutMc.removeEventListener(MouseEvent.CLICK,deleteEvt);
				uploadFlie();
				file.upload(urlRequest);
			}

		}
		private function uploadFlie():void
		{
			file.addEventListener(Event.OPEN,open);
			file.addEventListener(ErrorEvent.ERROR,openError);
			file.addEventListener(IOErrorEvent.IO_ERROR,openError);
			file.addEventListener(ProgressEvent.PROGRESS,onProgress);
			file.addEventListener(Event.COMPLETE, complete);
			file.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA,uploadComplete);
		}
		private function uploadComplete(e:DataEvent):void
		{
			var obj=e.data.toString();
			ExternalInterface.call("challs_flash_onCompleteData",obj);
		}
		private function openError(e:Event):void
		{
			file.removeEventListener(ErrorEvent.ERROR,openError);
			setStaue("[上传失败...]");
			var obj:Object={"fileName":file.name,"fileSize":file.size,"fileCreationDate":file.creationDate,"fileType":file.type,"modificationDate":file.modificationDate};
			ExternalInterface.call("challs_flash_onError",obj);
			dispatchEvent(new Event(UpFileClass.UPLOAD_COMPLETE));
			
		}
		private function open(e:Event):void
		{
			file.removeEventListener(Event.OPEN,open);
			setStaue("[数据读取中...]");
			
			var obj:Object={"fileName":file.name,"fileSize":file.size,"fileCreationDate":file.creationDate,"fileType":file.type,"modificationDate":file.modificationDate};
			ExternalInterface.call("challs_flash_onStart",obj);
		}
		private function onProgress(e:ProgressEvent)
		{
			var loaded:int = Math.floor(e.bytesLoaded / e.bytesTotal * 100);
			proMc.maskMc.x = loaded*0.9;
			setStaue("[数据读取中..."+loaded+"%]");
		}
		private function complete(e:Event):void
		{
			isUpload=false;
			var val:int=UpFileFlash.totalFileArr.indexOf(this);
			if(val>=0){
				UpFileFlash.totalFileArr.splice(val,1);
			}
			
			end_time=new Date();
			
			file.removeEventListener(ProgressEvent.PROGRESS,onProgress);
			file.removeEventListener(Event.COMPLETE, complete);
			setStaue("[上传成功!]");
			UpFileFlash.sucess.push(this);
			
			var ms:Number=end_time.milliseconds-start_time.milliseconds
			var obj:Object={"fileName":file.name,"fileSize":file.size,"fileCreationDate":file.creationDate,"fileType":file.type,"modificationDate":file.modificationDate,"updateTime":ms};
			ExternalInterface.call("challs_flash_onComplete",obj);
			dispatchEvent(new Event(UpFileClass.UPLOAD_COMPLETE));
		}
		 
		public function setStaue(str:String):void
		{
			statue=str;
			tf_statue.text = statue;
		}
		

	}

}