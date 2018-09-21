var url_string = window.location.href;
var url = new URL(url_string);
var urlCode = url.searchParams.get('e');
var urlName = url.searchParams.get('name');
var urlType = url.searchParams.get('type');
var urlRegion = url.searchParams.get('region');
window.history.replaceState(null, null, window.location.pathname);

$(document).ready( function () {
	if (urlCode != null || urlCode != undefined || urlCode != '') {
		if (urlType == 'DISTRICT') {
    		ajaxGet(urlCode, urlName, 'MUNICIPALITY');
    		$('#locationModal').html(name);
    		$('.screenLocation').html(name);
    		$('.list-candidates').show();
    		$('.gov-mayor').show(500);
    		$('.gov-governor').hide(500);
    	}
    	else if (urlType == 'PROVINCE') {
    		ajaxGet(urlCode, urlName, urlType, urlRegion);
    		ajaxGet(urlCode, urlName, 'HUC', urlRegion);
    		ajaxGet(urlCode, urlName, 'CITY', urlRegion);
    		$('#locationModal').html(name);
    		$('.screenLocation').html(name);
    		$('.list-candidates').show();
    		$('.gov-mayor').hide(500);
    		$('.gov-governor').show(500);
    	}
    	else {
    		ajaxGet(urlCode, urlName, urlType, urlRegion);
    		$('#locationModal').html(name);
    		$('.screenLocation').html(name);
    		$('.list-candidates').show();
    		$('.gov-mayor').show(500);
    		$('.gov-governor').hide(500);
    	}
	}
	$('#tableGeo').delegate('tbody > tr', 'click', function () {
    	var e = $(this).find(".code").html();
    	var name = $(this).find(".description").html();
    	var type = $(this).find(".type").html();
    	var region = $(this).find(".region").html();
    	switch (type) {
    		case 'DISTRICT':
    			$('tbody').html('');
    			ajaxGet(e, name, 'MUNICIPALITY');
    			$('#locationModal').html(name);
	    		$('.screenLocation').html(name);
	    		$('.list-candidates').show();
	    		$('.gov-mayor').show(500);
	    		$('.gov-governor').hide(500);
    		break;
    		case 'PROVINCE':
    			$('tbody').html('');
	    		ajaxGet(e, name, type, region);
	    		ajaxGet(e, name, 'HUC', region);
	    		ajaxGet(e, name, 'CITY', region);
	    		$('#locationModal').html(name);
	    		$('.screenLocation').html(name);
	    		$('.list-candidates').show();
	    		$('.gov-mayor').hide(500);
	    		$('.gov-governor').show(500);
    		break;
    		case 'MUNICIPAL':
    			alert('Last heirarchy. [municipality function goes here]');
    		break;
    		case 'HUC DISTRICT':
    			alert('Last heirarchy. [district function goes here]');
    		break;
    		case 'CC':
    			alert('Last heirarchy. [cc function goes here]');
    		break;
    		default:
    			$('tbody').html('');
    			ajaxGet(e, name, type, region);
    			$('#locationModal').html(name);
	    		$('.screenLocation').html(name);
	    		$('.list-candidates').show();
	    		$('.gov-mayor').show(500);
	    		$('.gov-governor').hide(500);
    	}
	});

	$('.bcrumbs').on('click', 'a', function(e) {
		e.preventDefault();
		var code = $(this).attr('id');
		var type = $(this).attr('class');
		console.log('Type: ' + type);
		$(this).nextAll().remove();
		ajaxGet(code, '', type);
		$('tbody').html('');
		if (type == 'PROVINCE') {
			ajaxGet(code, '', 'CITY');
			ajaxGet(code, '', 'HUC');
		}
		var name = $(this).html();
		if(type == 'DISTRICT' || type == 'MUNICIPALITY') {
    		$('#locationModal').html(name);
    		$('.screenLocation').html(name);
    		$('.list-candidates').show();
    		$('.gov-mayor').show(500);
    		$('.gov-governor').hide(500);
		} else if(type == 'PROVINCE' || type == 'CITY') {
    		$('#locationModal').html(name);
    		$('.screenLocation').html(name);
    		$('.list-candidates').show();
    		$('.gov-mayor').hide(500);
    		$('.gov-governor').show(500);
		} else {
    		$('#locationModal').html(name);
    		$('.screenLocation').html(name);
    		$('.list-candidates').show();
    		$('.gov-mayor').show(500);
    		$('.gov-governor').hide(500);
		}
	});

	$('#tableGeo').delegate('tbody > tr', 'mouseenter', function () {
		$(this).addClass('table-hover');
	});

	$('#tableGeo').delegate('tbody > tr', 'mouseleave', function () {
		$(this).removeClass('table-hover');
	});

	$('th').click(function() {
		if ($(this).children('i').hasClass('x')) {
			if ($(this).children('i').hasClass('fa-sort-amount-asc')) {
				$(this).children('i').removeClass('fa-sort-amount-asc');
				$(this).children('i').addClass('fa-sort-amount-desc');
			} else {
				$(this).children('i').removeClass('fa-sort-amount-desc');
				$(this).children('i').addClass('fa-sort-amount-asc');
			}
		}
		$(this).children('i').addClass('x');
		$(this).siblings().children('i').removeClass('x');
		$(this).siblings().children('i').removeClass('fa-sort-amount-desc');
		$(this).siblings().children('i').addClass('fa-sort-amount-asc');
	});

	loadPagination();

	function ajaxGet(e, name, type, region) {
		if (type == null || type == undefined || type == '') {
			$.ajax({
				method: 'GET',
				url: 'screening/' + e,
				success:function(data)  
		    	{
		    		if (data == '') {
		    		}
		    		else  {
		    			if (name != undefined && name != '') {
		    				$('.bcrumbs').append('<p>/</p> <a href="" id="' + e + '" class="">' + name + '</a>');
		    			}
		    			loadTable(e, data);
		    		}
		    	} 
			});
		}
		else {
			$.ajax({
				method: 'GET',
				url: 'screening/' + type + '/' + e,
				success:function(data)  
		    	{
		    		if (data == '') {
		    		}
		    		else  {
		    			if (name != undefined && name != '') {
		    				if (type != 'CITY') {
		    					if (type == 'PROVINCE' || (type == 'HUC' && region == 'NCR')) {
		    						$('.bcrumbs').html('<a href="" id="' + region + '" class="REGION">REGION ' + region + '</a> <p>/</p> <a href="" id="' + e + '" class="' + type + '">' + name + '</a>');
		    					}
		    					else {
		    						if ($('#' + e).length == 0)
		    							$('.bcrumbs').append('<p>/</p> <a href="" id="' + e + '" class="' + type + '">' + name + '</a>');
		    					}
		    				}
		    			}
		    			switch (type) {
		    				case 'HUC':
		    					hucTable(e, data, region);
		    				break;
		    				case 'PROVINCE':
		    					districtTable(e, data);
		    				break;
		    				case 'MUNICIPALITY':
		    					municipalityTable(e, data, name);
		    				break;
		    				case 'CITY':
		    					cityTable(e, data, name);
		    				break;
		    				default:
		    					loadTable(e, data);
		    			}
		    			//loadTable(e, data);
		    		}
		    		$('#locationModal').html(name);
		    		$('.screenLocation').html(name);
		    	} 
			});
		}
		getCandidate(e, type);
	}
});

function loadTable(e, data) {
	var keys = Object.keys(data);
	var y = keys.length - 1;
	var s = parseInt(keys[0]);
	var d = parseInt(keys[y]);
	for (var x = s; x <= d; x++) {
		if (data[x] != undefined) {
			var type = '';
			if (data[x].type != undefined) {
				type = data[x].type;
			}
			$('tbody').append(`
					<tr class='item'>
						<td class="code">` + data[x].province_code + `</td>
						<td class="description">` + data[x].lgu + `</td>
						<td>0</td>
						<td>0</td>
						<td>0</td>
						<td>Lorem Ipsum</td>
						<td class="type">` + type + `</td>
						<td class="region" style="display:none;">` + data[x].region + `</td>
					</tr>
				`);
			loadPagination();
		}
	}
}

function municipalityTable(e, data, name) {
	var keys = Object.keys(data);
	var y = keys.length - 1;
	var s = parseInt(keys[0]);
	var d = parseInt(keys[y]);
	for (var x=s; x <= d; x++) {
		if (data[x].district == name) {
			var type = 'MUNICIPAL';
			if (data[x].type != undefined) {
				type = data[x].type;
			}
			printRow(data, x, 'municipality', type);
		}
	}
}

function hucTable(e, data, region) {
	var keys = Object.keys(data);
	var y = keys.length - 1;
	var s = parseInt(keys[0]);
	var d = parseInt(keys[y]);
	for (var x=s; x <= d; x++) {
		if (data[x] != undefined) {
			var type = 'HUC';
			if (data[x].type != undefined) {
				type = data[x].type;
			}
			if (data[x].district == '') {
				printRow(data, x, 'city', type);
			}
			else if (region != 'NCR') {
				console.log($('.bcrumbs a').length);
				if ($('.bcrumbs a').length <= 2) {
					if (x == s) {
						printRow(data, x, 'city', type);
					}
				}
				else {
					type = 'HUC DISTRICT'
					printRow(data, x, 'district', type);
				}
			}
			else {
				type = 'HUC DISTRICT'
				printRow(data, x, 'district', type);
			}
		}
	}
}

function districtTable(e, data) {
	var keys = Object.keys(data);
	var y = keys.length - 1;
	var s = parseInt(keys[0]);
	var d = parseInt(keys[y]);
	for (var x=s; x <= d; x++) {
		if (x != keys[0]) {
			if (data[x].district != data[x-1].district) {
				printRow(data, x, 'district', 'DISTRICT');
			}
		}
		else {
			printRow(data, x, 'district', 'DISTRICT');
		}
	}
}

function cityTable(e, data, name) {
	var keys = Object.keys(data);
	var y = keys.length - 1;
	var s = parseInt(keys[0]);
	var d = parseInt(keys[y]);
	for (var x=s; x <= d; x++) {
		var type = 'CC';
		if (data[x].type != undefined) {
			type = data[x].type;
		}
		printRow(data, x, 'city', type);
	}
}

function printRow(data, x, name, type) {
	$('tbody').append(`
			<tr class='item'>
				<td class="code">` + data[x].province_code + `</td>
				<td class="description">` + data[x][name] + `</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>Lorem Ipsum</td>
				<td class="type">` + type + `</td>
			</tr>
	`);
	loadPagination();
}

function searchData() {
	// Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("searchBar");
  	filter = input.value.toUpperCase();
  	table = document.getElementById("tableGeo");
  	tr = table.getElementsByTagName("tr");

  	// Loop through all table rows, and hide those who don't match the search query
  	for (i = 0; i < tr.length; i++) {
    	td1 = tr[i].getElementsByTagName("td")[0];
    	td2 = tr[i].getElementsByTagName("td")[1];
    	td5 = tr[i].getElementsByTagName("td")[5];
    	if (td1 || td2 || td5) {
      		if (td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1 || td5.innerHTML.toUpperCase().indexOf(filter) > -1) {
        		tr[i].style.display = "";
      		} else {
        		tr[i].style.display = "none";
      		}
    	}
  	}
}

function loadPagination() {
    var rowsShown = 10;
    var rowsTotal = $('#tableGeo tbody tr').length;
    var numPages = rowsTotal/rowsShown;
    $('#nav').html('');
    for(i = 0;i < numPages;i++) {
        var pageNum = i + 1;
        $('#nav').append('<a href="#" rel="'+i+'">'+pageNum+'</a> ');
    }
    $('#tableGeo tbody tr').hide();
    $('#tableGeo tbody tr').slice(0, rowsShown).show();
    $('#nav a:first').addClass('active');
    $('#nav a').bind('click', function(){
        $('#nav a').removeClass('active');
        $(this).addClass('active');
        var currPage = $(this).attr('rel');
        var startItem = currPage * rowsShown;
        var endItem = startItem + rowsShown;
        $('#tableGeo tbody tr').css('opacity','0.0').hide().slice(startItem, endItem).
        css('display','table-row').animate({opacity:1}, 300);
        return false;
    });
}

function getCandidate(provinceCode, type) {
	
}