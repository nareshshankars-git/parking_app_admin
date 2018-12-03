function call_ajax(obj,page_url,div_id,arg) {
	arg=typeof arg === 'undefined' ? "" : arg
    var somedata = arg+"&"+obj.name+"="+obj.value;
	  $( "#"+div_id ).html('<img src="css/AjaxLoader.gif" style="width:100px;height:100px;" />');
	//console.log(somedata);
    $.ajax({
        type: "POST",
        url: "ajax/"+page_url+".php",
        cache: false,
        data: somedata,
        success: function(data) {
		   $( "#"+div_id ).removeClass('has-error');
           //console.log(data);
		   $( "#"+div_id ).html(data);
        }
    })
}
function call_ajax_url(page_url,div_id,arg,functionName) {
	arg=typeof arg === 'undefined' ? "" : arg;
	functionName=typeof functionName === 'undefined' ? "" : functionName;
    var somedata = arg;
	 $( "#"+div_id ).html('<div class="loader"></div>');
	//console.log(somedata);
    $.ajax({
        type: "GET",
        url: "ajax/"+page_url+".php",
        cache: false,
        data: somedata,
        success: function(data) {
           //console.log(data);
		   $( "#"+div_id ).html(data);
		   if(functionName!=""){
				window[functionName]();
			}
		}
    });
}
