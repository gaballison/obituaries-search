$(document).foundation();

function stringCleaning() {
    var myForm = document.getElementById('advancedSearch');
    var allInputs = myForm.getElementsByTagName('input');
    var input, i;
    for(i = 0; input = allInputs[i]; i++) {
        if(input.getAttribute('name') && !input.value) {
            input.setAttribute('name', '');
        }
    }
}
function goBack() {
    window.history.back()
}
$(".results:not(:last)").css("border-bottom", "dashed 1px #ccc");

$("p#error-firstname").hide();
$("p#error-lastname").hide();
$("p#error-church").hide();
$("p#error-burial").hide();
$("#between").hide();
if(!$("#formatPrint").prop("checked")) {
	$("div#mailing").hide();
} else if($("#formatPrint").prop("checked")) {
	$("div#mailing").show();
}
$("#yt").mouseout(function(){
	if($("#yt option:selected").val() === "between") {
		$("#between").show();
	} 
});
$("#yt").mouseout(function(){
	if($("#yt option:selected").val() !== "between") {
		$("#between").hide();
	} 
});
$("#pubLabel").hide();
$("#firstYear").hide();
$("#hideYears").hide();
$("#showYears").click(function(){
	$("#pubLabel").show();
	$("#firstYear").show();
	$("#showYears").hide();
	$("#hideYears").show();
	$("#pubselected").val("shown");
});
$("#hideYears").click(function(){
	$("#pubLabel").hide();
	$("#firstYear").hide();
	$("#showYears").show();
	$("#hideYears").hide();
	$("#between").hide();
	$("#pubselected").val("hidden");
});

// Regular Expression Checking
$("p#error-firstname").hide();
$("input#fn").keyup(function() {
	var $fnInput = $("input#fn").val();
	//var $fnTrimmed = $.trim($("input#fn").val());
    var $fnRegex = /^\w+[\w\s%]*$/ig;
    if($fnRegex.test($fnInput)) {
		$("#fn").addClass("success");
		$("#fnVar").html("<p id=\"success-firstname\" class=\"successful-input\"><i class=\"fa fa-check fa-lg\"></i></p>");
		$("p#error-firstname").hide();
		$("#fn").removeClass("warning");
		$("#advsubmit").prop("disabled",false);
    } else if ($fnInput.length === 0){
		$("#fn").removeClass("warning");
		$("#fn").removeClass("success");
		$("p#error-firstname").remove();
		$("p#success-firstname").remove();
	} else {
		$("p#error-firstname").show();
		$("#fn").addClass("warning");
		$("#fn").removeClass("success");
		$("p#success-firstname").remove();
		$("#advsubmit").prop("disabled",true);
    }
});
$("input#ln").keyup(function() {
	var $lnInput = $("input#ln").val();
	//var $lnTrimmed = $.trim($("input#ln").val());
    //var $lnRegex = /^\w+[\w\s%]*$/ig;
	var $lnRegex = /^\w+[\w\s\.\'%]*$/ig;
    if($lnRegex.test($lnInput)) {
		$("#ln").addClass("success");
		$("p#error-lastname").hide();
		$("#ln").removeClass("warning");
		$("#advsubmit").prop("disabled",false);
    } else if ($lnInput.length === 0){
		$("#ln").removeClass("warning");
		$("#ln").removeClass("success");
		$("p#error-lastname").hide();
	} else {
    	$("p#error-lastname").show();
		$("#ln").addClass("warning");
		$("#ln").removeClass("success");
		$("#advsubmit").prop("disabled",true);
    }
});

$("input#ch").keyup(function() {
    var $chInput = $("input#ch").val();
    var $chRegex = /^\w+[\w\s\.\'%]*$/ig;
    if($chRegex.test($chInput)) {
		$("#ch").addClass("success");
		$("p#error-church").hide();
		$("#ch").removeClass("warning");
		$("#advsubmit").prop("disabled",false);
    } else if ($chInput.length === 0) { 
		$("#ch").removeClass("warning");
		$("#ch").removeClass("success");
		$("p#error-church").hide();
	} else {
    	$("p#error-church").show();
		$("#ch").addClass("warning");
		$("#ch").removeClass("success");
		$("#advsubmit").prop("disabled",true);
    }
});
$("input#bur").keyup(function() {
    var $burInput = $("input#bur").val();
    var $burRegex = /^\w+[\w\s\.\'%]*$/ig;
    if($burRegex.test($burInput)) {
		$("#bur").addClass("success");
		$("p#error-burial").hide();
		$("#bur").removeClass("warning");
		$("#advsubmit").prop("disabled",false);
    } else if ($burInput.length === 0) {
		$("#bur").removeClass("warning");
		$("#bur").removeClass("success");
		$("p#error-burial").hide();
	} else {
    	$("p#error-burial").show();
		$("#bur").addClass("warning");
		$("#bur").removeClass("success");
		$("#advsubmit").prop("disabled",true);
    }
});
$("input#syr").keyup(function() {
    var $syrInput = $("input#syr").val();
	var pubs = $("input#pubselected").val();
    var $syrRegex = /^\d{4}$/ig;
    if($syrRegex.test($syrInput)) {
		$("#syr").addClass("success");
		$("p#error-firstyear").hide();
		$("#syr").removeClass("warning");
		$("#pubyearErr").hide();
		$("#advsubmit").prop("disabled",false);
    } else if (pubs === "shown" && $syrInput.length < 4) {
		$("#syr").addClass("warning");
		$("#syr").removeClass("success");
		$("#pubyearErr").html("<p id=\"error-pubyear1\" class=\"callout warning\">You must include a year if you want to search by publication date. If you don't want to search by date, click the \"Hide Publication Year\" button</p>");
	} else {
    	$("p#error-firstyear").show();
		$("#syr").addClass("warning");
		$("#syr").removeClass("success");
		$("#pubyearErr").html("<p id=\"error-pubyear1\" class=\"callout warning\">You must include a year if you want to search by publication date. If you don't want to search by date, click the \"Hide Publication Year\" button</p>");
		$("#advsubmit").prop("disabled",true);
    }
});

$("input#eyr").keyup(function() {
    var $eyrInput = $("input#eyr").val();
	var $syrInput2 = $("input#syr").val();
	var $ytInput = $("#yt option:selected").val();
	var $eyrGood;
	var $syrGood;
    var $eyrRegex = /^\d{4}$/ig;
    if($eyrRegex.test($eyrInput)) {
		$eyrGood = $eyrInput;
		$("#eyr").addClass("success");
		$("p#error-endyear").hide();
		$("#pubyearErr").hide();
		if($eyrRegex.test($syrInput2)) {
			$syrGood = $syrInput2;
			if($eyrGood > $syrGood) {
				$("#eyr").addClass("success");
				$("p#error-endyear").hide();
				$("#eyr").removeClass("warning");
				$("#pubyearErr").hide();
				$("#advsubmit").prop("disabled",false);
			} else if ($eyrGood < $syrGood) {
				$("p#error-endyear").show();
				$("#eyr").addClass("warning");
				$("#eyr").removeClass("success");
				$("#pubyearErr").html("<p id=\"error-pubyear2\" class=\"callout warning\">2nd year must be after 1st year</p>");
				$("#advsubmit").prop("disabled",true);
			}
		}
    } 
});
$("input#advsubmit").click(function() {
	// if all empty, don't submit
	var fn = $("input#fn").val();
	var ln = $("input#ln").val();
	var ch = $("input#ch").val();
	var bur = $("input#bur").val();
	var pubselect = $("input#pubselected").val();
	var yt = $("input#yt").val();
	var smn = $("input#smn").val();
	var sy = $("input#syr").val();
	var emn = $("input#emn").val();
	var ey = $("input#eyr").val();
	if(pubselect === "shown") {
		// year cannot be blank
		if(sy === null) {
			$("#pubyearErr").html("<p id=\"error-pubyear1\" class=\"callout warning\">You must include a year if you want to search by publication date. If you don't want to search by date, click the \"Hide Publication Year\" button</p>");
			$("#syr").addClass("callout warning");
			$("#syrVar").html(" ");
			$("#syr").removeClass("callout success");
		}
	}
});



$("#reqName").keyup(function(){
	var namevar = $("#reqName").val();
	var nlen = namevar.length;
	var patt = /^[A-z ]*\s[A-z ]*$/i;
	var patt2 = /^[A-z ]*$/i;
	if(nlen === 0) {
		$("#reqName").addClass("alert");
		$("#reqName").removeClass("success");
		$("#reqName").removeClass("warning");
		$("#nameErr").html("<p class=\"alert callout\"><b>Name is required</b></p>");
		$("#nameVal").html("<i class=\"fa fa-exclamation-circle fa-2x\" style=\"color: red;\"></i>");
	} else if (patt.test(namevar)) {
		$("#reqName").removeClass("alert");
		$("#reqName").removeClass("warning");
		$("#reqName").addClass("success");
		$("#nameVal").html("<i class=\"fa fa-check fa-2x\" style=\"color: green;\"></i>");
		$("#nameErr").html(" ");
	} else if (patt2.test(namevar)) {
		$("#reqName").removeClass("alert");
		$("#reqName").removeClass("success");
		$("#reqName").addClass("warning");
		$("#nameErr").html("<p class=\"warning callout\"><b>Please include a first and last name</b></p>");
		$("#nameVal").html("<i class=\"fa fa-exclamation-triangle fa-2x\" style=\"color: orange\"></i>");
	} else {
		$("#reqName").removeClass("alert");
		$("#reqName").removeClass("success");
		$("#reqName").removeClass("warning");
		$("#nameErr").html(" ");
		$("#nameVal").html(" ");
	}
});
$("#reqEmail").keyup(function(){
	var emailvar = $("#reqEmail").val();
	var elen = emailvar.length;
	var epatt = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
	if(elen === 0) {
		$("#reqEmail").addClass("alert");
		$("#emailErr").html("<p class=\"alert callout\"><b>Email address is required</b></p>");
		$("#emailVal").html("<i class=\"fa fa-exclamation-circle fa-2x\" style=\"color: red;\"></i>");
	} else if (epatt.test(emailvar)) {
		$("#reqEmail").addClass("success");
		$("#emailErr").html(" ");
		$("#emailVal").html("<i class=\"fa fa-check fa-2x\" style=\"color: green;\"></i>");
	} else {
		$("#reqEmail").removeClass("alert");
		$("#reqEmail").removeClass("success");
		$("#emailErr").html(" ");
		$("#emailVal").html(" ");
	}
});
$("#formatDigital").mouseout(function(){
	if(!$("#formatDigital").prop("checked")) {
		if(!$("#formatPrint").prop("checked")) {
			$("#formatErr").html("<p class=\"alert callout\"><b>You must select at least one format</b></p>");
			$("#formatVal").html("<i class=\"fa fa-exclamation-circle fa-2x\" style=\"color: red;\"></i>");
		} 
	} else if($("#formatDigital").prop("checked")) {
			$("#formatErr").html(" ");
			$("#formatVal").html(" ");
	}
});
$("#formatPrint").mouseout(function(){
	if(!$("#formatPrint").prop("checked")) {
		if(!$("#formatDigital").prop("checked")) {
			$("#formatErr").html("<p class=\"alert callout\"><b>You must select at least one format</b></p>");
			$("#formatVal").html("<i class=\"fa fa-exclamation-circle fa-2x\" style=\"color: red;\"></i>");
		}
		$("div#mailing").hide();
	} else if($("#formatPrint").prop("checked")) {
		$("#formatErr").html(" ");
		$("#formatVal").html(" ");
		$("div#mailing").show();
	}
});

$("input#advsubmit").click(function() {
	// if all empty, don't submit
	var name = $("input#reqName").val();
	var email = $("input#reqEmail").val();
	var addr = $("input#reqAddress").val();
	var obit = $("input#reqObit").val();
	var recPrint = $("input#formatPrint").val();
	var recEmail = $("input#formatEmail").val();
	if(pubselect === "shown") {
		// year cannot be blank
		if(sy === null) {
			$("#pubyearErr").html("<p id=\"error-pubyear1\" class=\"callout warning\">You must include a year if you want to search by publication date. If you don't want to search by date, click the \"Hide Publication Year\" button</p>");
			$("#syr").addClass("callout warning");
			$("#syrVar").html(" ");
			$("#syr").removeClass("callout success");
		}
	}
	
});
