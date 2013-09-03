(function($) {
	
	$.fn.mulitselector = function(options) { 
		
		if ($("#mulitSelector").length != 0) return;

		var $input = $(this);

		var ms_html;

		var settings = 
		{
			title: "choose tables",
			data: null,
			perPage: 10,
			curPage: 0
		};

		if (options){
			jQuery.extend(settings, options);
		}

		function initialise(){
			initContent();
			initEvent();
			initPageBtn();
		}

		function initEvent() {

			$("#ms_bt_ok").click(function() {
				var result = "";
				markData();
				$.each(settings.data, function(i, v){				   
					if(v.checked){					    
						result = v.name + "," + result;                       
					}
				}); 
                result=result.substr(0,(result.length)-1);               
				$input.val(result);
				ms_html.remove();
			});
			
			$("#ms_bt_all").click(function() {
				var obj = $("#allItems1 input");
				obj.each(function() { 
					$(this).prop("checked", true); 
				});
			});

			$("#ms_bt_clear").click(function() {
				var obj = $("#allItems1 input");
				obj.each(function() { 
					$(this).prop("checked",false); 
				});
			});

			$("#ms_img_close").click(function() {
				ms_html.remove();
			});
			
			$("#ms_bt_pre").click(function() {
				markData();
				$("#allItems1").html(getDataHtml(settings.curPage-1));
				initPageBtn();
			});
			
			$("#ms_bt_next").click(function() {
				markData();
				$("#allItems1").html(getDataHtml(settings.curPage+1));
				initPageBtn();
			});	
		}
		
		function markData(){
			$("#allItems1 input").each(function(){
				var id = $(this).val().split("@")[0];
				if($(this).prop("checked") == true) {
					$.each(settings.data, function(i, v){
						if(v.id == id){
							v.checked=true;
						}
					});
				} else {
					$.each(settings.data, function(i, v){
						if(v.id == id){
							v.checked=false;
						}
					});
				}
			});	
		}

		function initContent() {

			var offset = $input.offset();
			var divtop = 1 + offset.top + document.getElementById($input.attr("id")).offsetHeight + 'px';
			var divleft = offset.left + 'px';
			var popmask = document.createElement('div');

			var html = [];	

			html.push('<div id="mulitSelector" style="display:block; top:'+divtop+';left:'+divleft+'; position: absolute; z-index: 1999;">');
			html.push('    <div id="pslayer"  class="alert_div sech_div ms_width">');
			html.push('        <div class="box">');
			html.push('            <h1><span id="psHeader">'+settings.title+'</span><A href="javascript:void(0);" class="butn3" id="ms_img_close"></A></h1>');
			html.push('			<div class="blk">');
			html.push('				<div id="divSelecting" class="sech_layt">');
			html.push('					<h3>');
			html.push('						<span id="selectingHeader"></span><b class="btn_fst">');
			html.push('						<input id="ms_bt_pre" name="" type="button" value="prev" />');
			html.push('						<input id="ms_bt_next" name="" type="button" value="next" />');
			html.push('						<input id="ms_bt_all" name="" type="button" value="All" />');
			html.push('						<input id="ms_bt_clear" name="" type="button" value="Unall" />');
			html.push('						<input id="ms_bt_ok" name="" type="button" value="ok" /></b>');
			html.push('					</h3>');
			html.push('				</div>');
			html.push('				<div class="sech_layb"> ');
			html.push('					<h2 id="subHeader1"></h2>');
			html.push('					<ol id="allItems1">');
			
			html.push(getDataHtml(settings.curPage));

			html.push('					</ol>');
			html.push('				</div>');
			html.push('			</div>');
			html.push('		</div>');
			html.push('   </div>');
			html.push('</div>');

			ms_html = $(html.join("")).appendTo('body');
			
		}
		
		function initPageBtn(){
			var dataArray = settings.data;
			if (dataArray != null){
				var len = dataArray.length;
				var starti = settings.curPage * settings.perPage > len ? 0 : settings.curPage * settings.perPage;
				var endi = starti + settings.perPage > len? len : starti + settings.perPage;
				if(starti==0) {
					$("#ms_bt_pre").hide();
				} else {
					$("#ms_bt_pre").show();
				}
				if(endi==len){
					$("#ms_bt_next").hide();
				} else {
					$("#ms_bt_next").show();
				}
			}
		}
		
		function getDataHtml(pageNo){
			if(pageNo < 0) pageNo = 0;
			var html = [];
			var dataArray = settings.data;
			if (dataArray != null){
				var len = dataArray.length;
				var starti = pageNo * settings.perPage > len ? 0 : pageNo * settings.perPage;
				var endi = starti + settings.perPage > len? len : starti + settings.perPage;
				settings.curPage = pageNo;
				for(var i=starti; i<endi; i++){
					var d = dataArray[i];
                    var result="";
                    if(d.name.length>28)
                     {
                        result=d.name.substr(0,28)+"...";
                     }
                     else
                     {
                        result=d.name;
                     }                                       
					var status = findStatus(d);                   
					html.push('						<li id=$'+d.id+' name=100 class="nonelay">');
					html.push('							<a href="###" title="'+d.name+'">');
					html.push('							<label for="'+d.id+'">');
					html.push('							<input id="'+d.id+'" type="checkbox" '+status+' value="'+(d.id+ '@'+ d.name)+'"/>'+result+'</label>');
					html.push('							</a>');
					html.push('						</li>');
				}
			}
			return html.join("");
		}

		function findStatus(d){
			if(d.checked) return "checked";
			var content = $input.val();
			if (jQuery.trim(content) == ""){
				return "";
			}

			var obj = content.split(",");
			for(var i=0; i<obj.length; i++){
				if(obj[i] == d.name){
					return "checked"
				}
			}

		}

		initialise();

	}



})(jQuery);
