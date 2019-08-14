doSort = function(){
	$jq("ul" ).sortable();
/// Sortable list START
	$jq( "ul.oeplsortable1" ).sortable({
		connectWith: "ul",
	});
	$jq( "ul.oeplsortable2, ul.oeplsortable3" ).sortable({
		connectWith: "ul",
		dropOnEmpty: true,
		update: function(event,ui)	{
			var col1 = [];
			var col2 = [];
			var module = $jq(this).attr('module');
			var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
			var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
			$jq("#" + ULID1 ).find('li').each(function() {
				var pid = $jq(this).attr('data-pid');
				col1.push (pid);
			});
			$jq("#" + ULID2 + " li").each(function() {
					var pid = $jq(this).attr('data-pid');
					col2.push (pid);
			});
			updateOEPLSorting(module, col1, col2);
		}
	});
	$jq( ".oeplsortable1, .oeplsortable2, .oeplsortable3" ).disableSelection();
}
$jq( ".oeplsortable1, .oeplsortable2, .oeplsortable3" ).disableSelection();
///	Reset button Function START
$jq(document).on( "click", ".Reset", function() {
	var module = $jq(this).attr('module');
	var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
	var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
	var ULID0  = module + 'Col0UL' + $jq(this).attr('refID');
	var data1 = $jq("#" + ULID1 ).html();
	var data2 = $jq("#" + ULID2 ).html();
	if(data1 || data2)
	{
		var agree=confirm("Are you sure ?");
		if (agree)
		{
			var col1 = [];
			var col2 = [];
			$jq("#loading-mask").show();
			$jq("#" + ULID1 ).find('li').each(function() {
				$jq(this).hide();
			});
			$jq("#" + ULID2 ).find('li').each(function() {
				$jq(this).hide();
			});
			$jq("#" + ULID0 ).prepend(data1,data2);
			updateOEPLSorting(module, col1, col2);
			setTimeout(function (){
				$jq("#import").trigger("click");
			}, 500);
		}
		else
		{
			return false; 	
		}
	}  
	else
	{
		alert ("No fields to reset");
	}
});
///	Reset button Function END

///	Transfer All button Function START
$jq(document).on( "click", ".Transfer", function() {
	var module = $jq(this).attr('module');
	var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
	var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
	var ULID0  = module + 'Col0UL' + $jq(this).attr('refID');
	var data = $jq("#" + ULID0 ).html();
	var col1 = [];
	var col2 = [];
	if(data)
	{
		$jq("#loading-mask").show();
		$jq("#" + ULID1 ).find('li').each(function() {
			var pid = $jq(this).attr('data-pid');
			col1.push (pid);
		});
		$jq("#" + ULID0 ).find('li').each(function() {
			var pid = $jq(this).attr('data-pid');
			col1.push (pid);
			$jq(this).hide();
		});
		$jq("#" + ULID2 ).find('li').each(function() {
			var pid = $jq(this).attr('data-pid');
			col2.push (pid);
		});
		$jq("#" + ULID1 ).append(data);
		updateOEPLSorting(module, col1, col2);
		/*setTimeout(function (){
			
			$jq("ul#" + ULID1).load(" #" + ULID1 + " li");
			$jq("ul#" + ULID2).load(" #" + ULID2 + " li");
			$jq("ul#" + ULID0).load(" #" + ULID0 + " li" ,function(){
				$jq("#loading-mask").hide();	
			});
		}, 2000);*/
		setTimeout(function (){
			$jq("#import").trigger("click");
		}, 500);
	}
	else
	{
		alert ("No Fields To Transfer");
	}
});
///	Transfer All button Function END

///Add Filler To column1 START
$jq(document).on( "click", ".Col1Filler", function() {
	var module = $jq(this).attr('module');
	var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
	var col1 = -1;
	var col2 = -1;
	updateOEPLSorting(module, col1, col2);
	setTimeout(function (){
		$jq("#import").trigger("click");
	}, 500);
});
///Add Filler To column1 END

///Add Filler To column2 START
$jq(document).on( "click", ".Col2Filler", function() {
	var module = $jq(this).attr('module');
	var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
	var col1 = -2;
	var col2 = -2;
	updateOEPLSorting(module, col1, col2);
	setTimeout(function (){
		$jq("#import").trigger("click");
	}, 500);
});
///Add Filler To column2 END

///Swap columns START
$jq(document).on( "click", ".Swap", function() {
	var module = $jq(this).attr('module');
	var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
	var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
	var ULID0  = module + 'Col0UL' + $jq(this).attr('refID');
	var data1 = $jq("#" + ULID1 ).html();
	var data2 = $jq("#" + ULID2 ).html();
	var col1 = [];
	var col2 = [];
	if (data1 || data2)
	{
		$jq("#" + ULID1 ).find('li').each(function() {
			var pid = $jq(this).attr('data-pid');
			col2.push (pid);
		});
		$jq("#" + ULID2 ).find('li').each(function() {
			var pid = $jq(this).attr('data-pid');
			col1.push (pid);
		});
		updateOEPLSorting(module, col1, col2);
		setTimeout(function (){
			$jq("#import").trigger("click");
		}, 500);
	}
	else
	{
		alert ("No Columns to Swap");	
	}
});
///Swap columns END

///Delete Filter START
$jq(document).on( "click", ".delFiller", function() {
	var module = $jq(this).attr('module');
	var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
	var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
	var ULID0  = module + 'Col0UL' + $jq(this).attr('refID');
	var data1 = $jq("#" + ULID1 +" li:contains('Filler')").html();
	var data2 = $jq("#" + ULID2 +" li:contains('Filler')").html();
	var col1 = [];
	var col2 = [];
	if (data1 || data2)
	{
		var agree = confirm("Are you sure?");
		if(agree)
		{
			$jq("#loading-mask").show();
			$jq("#" + ULID1 +" li:contains('Filler')").remove();
			$jq("#" + ULID2 +" li:contains('Filler')").remove();
			$jq("#" + ULID1 ).find('li').each(function() {
				var pid = $jq(this).attr('data-pid');
				col1.push (pid);
			});
			$jq("#" + ULID2 ).find('li').each(function() {
				var pid = $jq(this).attr('data-pid');
				col2.push (pid);
			});
			updateOEPLSorting(module, col1, col2);
			setTimeout(function (){
				$jq("#import").trigger("click");
			}, 500);
		}
	}
	else
	{
		alert("No fillers to Delete");
	}
});
///Delete Filter END

$jq(document).ready(function() 
{
	$jq("ul" ).sortable();
/// Sortable list START
	$jq( "ul.oeplsortable1" ).sortable({
		connectWith: "ul",
	});
	$jq( "ul.oeplsortable2, ul.oeplsortable3" ).sortable({
		connectWith: "ul",
		dropOnEmpty: true,
		update: function(event,ui)	{
			var col1 = [];
			var col2 = [];
			var module = $jq(this).attr('module');
			var ULID1  = module + 'Col1UL' + $jq(this).attr('refID');
			var ULID2  = module + 'Col2UL' + $jq(this).attr('refID');
			$jq("#" + ULID1 ).find('li').each(function() {
				var pid = $jq(this).attr('data-pid');
				col1.push (pid);
			});
			$jq("#" + ULID2 + " li").each(function() {
					var pid = $jq(this).attr('data-pid');
					col2.push (pid);
			});
			updateOEPLSorting(module, col1, col2);
		}
	});
	$jq( ".oeplsortable1, .oeplsortable2, .oeplsortable3" ).disableSelection();
/// Sortable list END
});
/// Ajax Function START
function updateOEPLSorting(module, col1, col2)
{
	var data = {};	
	data = {module:module,pid1:col1,pid2:col2};
	string = JSON.stringify(data);

	new Ajax.Request(moduleShortUrl, {
	method: 'post',
	parameters: {data:string},
	});
}
/// Ajax Function END