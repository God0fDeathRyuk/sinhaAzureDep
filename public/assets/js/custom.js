$(document).ready(function () {

	$('.mnuMn').click(function () {
		$(this).children('.arw').toggleClass('bi bi-chevron-up');
		$(this).parent().children().nextAll().slideToggle('slow');
		$(this).parent().prevAll().children('ul').slideUp('slow');

		$(this).parent().prevAll('li').find('.arw').removeClass('bi bi-chevron-up');
		$(this).parent().prevAll('li').find('.arw').addClass('bi bi-chevron-down');

		$(this).parent().nextAll('li').find('.arw').removeClass('bi bi-chevron-up');
		$(this).parent().nextAll('li').find('.arw').addClass('bi bi-chevron-down');

		$(this).parent().nextAll('li').children('ul').slideUp('slow');

		$(this).parent().toggleClass('active');
		$(this).parent().nextAll().removeClass('active');
		$(this).parent().prevAll().removeClass('active');
	});

	// datepicker from to validation
	//$( ".txtFrom" ).datepicker( "option", "dateFormat", "mm-dd-yy" );

		$(".txtFrom").datepicker({
			// dateFormat: 'dd-mm-yy',
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() + 1);
				$(".txtTo").datepicker("option", "minDate", dt);
			}
			
		});
		$(".txtTo").datepicker({
			// dateFormat: 'dd-mm-yy',
			onSelect: function (selected) {
				var dt = new Date(selected);
				dt.setDate(dt.getDate() - 1);
				$(".txtFrom").datepicker("option", "maxDate", dt);
			}
		});
});

// Date and Time start

function startTime() {
	var today = new Date();
	var hr = today.getHours();
	var min = today.getMinutes();
	var sec = today.getSeconds();
	ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
	hr = (hr == 0) ? 12 : hr;
	hr = (hr > 12) ? hr - 12 : hr;
	//Add a zero in front of numbers<10
	hr = checkTime(hr);
	min = checkTime(min);
	sec = checkTime(sec);
	document.getElementById("clock").innerHTML = hr + ":" + min + ":" + sec + " " + ap;

	var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	var curWeekDay = days[today.getDay()];
	var curDay = today.getDate();
	var curMonth = months[today.getMonth()];
	var curYear = today.getFullYear();
	var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear;
	document.getElementById("date").innerHTML = date;

	var time = setTimeout(function () { startTime() }, 500);
}
function checkTime(i) {
	if (i < 10) {
		i = "0" + i;
	}
	return i;
}
startTime();

//Date and time end

var xValues = ["Realized Amount", "Unrealized Amount"];
var yValues = [75, 25];
var barColors = [
	"#055570",
	"#00aba9"
];

new Chart("myChart", {
	type: "doughnut",
	data: {
		labels: xValues,
		datasets: [{
			backgroundColor: barColors,
			data: yValues
		}]
	},
	options: {
		title: { display: false },
		legend: { display: false }
	}
});

//a simple date formatting function
function dateFormat(inputDate, format) {
	//parse the input date
	const date = new Date(inputDate);

	//extract the parts of the date
	const day = date.getDate();
	const month = date.getMonth() + 1;
	const year = date.getFullYear();

	//replace the month
	format = format.replace("MM", month.toString().padStart(2, "0"));

	//replace the year
	if (format.indexOf("yyyy") > -1) {
		format = format.replace("yyyy", year.toString());
	} else if (format.indexOf("yy") > -1) {
		format = format.replace("yy", year.toString().substr(2, 2));
	}

	//replace the day
	format = format.replace("dd", day.toString().padStart(2, "0"));

	return format;
}

function formOption(url = '', option = '', formId) {
	url = '/sinhaco' + url;
	var form = document.getElementById(formId);
	var inputs = document.getElementsByTagName('input');

	form.action = url + option;

	if (option == 'add') {
		for (let input of inputs)
			input.required = false;
	}
}

function resetFields() {
	textarea = document.getElementsByTagName('textarea');
	for (let input of textarea) input.innerHTML = '';
}

function submitForm(form = '', option = '') {
	if (option.toLowerCase() == 'delete') {
		Swal.fire({
			title: 'Are you sure?',
			text: "Do you want to Delete ??",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Delete'
		}).then((result) => {
			if (result.isConfirmed) {
				document.getElementById(form).submit();
			}
		})
	} else document.getElementById(form).submit();
}
//---
$(document).ready(function () {
	$('.prcdClk').click(function () {
		$('.tbl-shw-hde').addClass("d-blck");
		$(this).prop("disabled", true);
		$('.btnSve').prop("disabled", false);
	});
});

function grant_perm(e) {
	var checked_all = '';
	var total_row = document.getElementById("total_row").value;
	if (e == 'C') {

		for (i = 1; i <= total_row; i++) {
			document.getElementById("chk" + i).checked = true;
		}
	}
}
function grant_perm2(e) {
	var checked_all = '';

	var total_row = document.getElementById("total_row").value
	if (e == 'N') {
		for (i = 1; i <= total_row; i++) {
			document.getElementById("chk" + i).checked = false;
		}
	}
}
function grant_all() {
	var total_nos = document.getElementById("total_row").value;
	var checked_all = '';
	for (i = 1; i <= total_nos; i++) {
		document.getElementById("initial_perm" + i).checked = true;
	}
}

function revoke_all() {
	var total_nos = document.getElementById("total_row").value;
	var checked_all = '';
	for (i = 1; i <= total_nos; i++) {
		document.getElementById("initial_perm" + i).checked = false;
	}
}
function check_quick_link(i) {
	if (document.getElementById("quick_link" + i).checked && document.getElementById("chk" + i).checked) {
		alert("Please give permission first ...");
		document.getElementById("quick_link" + i).checked = false
	}
}
function cchange(count) {
	var cTrue = document.getElementById("initial_perm" + count).value
	if (cTrue == 'Y') {
		$("#initial_perm" + count).val('N');
	}
	if (cTrue == '') {
		$("#initial_perm" + count).val('Y');
	}
	if (cTrue == 'N') {
		$("#initial_perm" + count).val('Y');
	}

}

function proceedOn() {
	var matterCode = document.getElementById("matterCode").value;
	var action = document.getElementById("pageMode").value;
	document.getElementById("proceed").href = "/master/matter-masteraddedit/" + action + "?code_code=" + matterCode;

}

function changeTab() {
	var initial_name = document.getElementById("initialName").value;
	var file_locn_code = document.getElementById("fileLocnCode").value;
	var court_code = document.getElementById("courtCode").value;
	var matter_desc2 = document.getElementById("matterDesc2").value;
	var client_name = document.getElementById("clientName").value;
	var client_group_name = document.getElementById("clientGroupName").value;
	var appearing_for_code = document.getElementById("appearingForCode").value;
	var reference_type_code = document.getElementById("referenceTypeCode").value;
	var address_line_1 = document.getElementById("addressLine_1").value;
	var attention_name = document.getElementById("attentionName").value;



	if (initial_name != '' && file_locn_code != '' && court_code != '' && matter_desc2 != '' && client_name != '' && client_group_name != '' && appearing_for_code != '' && reference_type_code != '' && address_line_1 != '' && attention_name != '') {
		document.getElementById("tabChange").options[1].disabled = false;
		document.getElementById("tabChange").options[2].disabled = false;
		document.getElementById("tabChange").options[3].disabled = false;
		document.getElementById("tabChange").options[4].disabled = false;
		document.getElementById("tabChange").options[5].disabled = false;
		document.getElementById("tabChange").options[6].disabled = false;
		document.getElementById("tabChange").options[7].disabled = false;
		document.getElementById("tabChange").options[8].disabled = false;
		document.getElementById("tabChange").options[9].disabled = false;
		document.getElementById("tabChange").options[10].disabled = false;
		document.getElementById("tabChange").options[11].disabled = false;
		document.getElementById("tabChange").options[12].disabled = false;
		document.getElementById("tabChange").options[13].disabled = false;
		document.getElementById("tabChange").options[14].disabled = false;
		document.getElementById("tabChange").options[15].disabled = false;
		document.getElementById("tabChange").options[16].disabled = false;
		document.getElementById("tabChange").options[17].disabled = false;
		document.getElementById("tabChange").options[18].disabled = false;



	}
}
function changeSlide() {

	var id = document.getElementById("tabChange").value
	if (id == 'basic') {
		document.getElementById("basic").classList.remove('d-none');
		document.getElementById("basic").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'oSide') {
		document.getElementById("oSide").classList.remove('d-none');
		document.getElementById("oSide").classList.add('d-block');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}

	if (id == 'cncl(0)') {
		document.getElementById("cncl(0)").classList.remove('d-none');
		document.getElementById("cncl(0)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
		document.getElementById("referedBy").classList.add('d-none');
		document.getElementById("referedBy").classList.remove('d-block');
	}
	if (id == 'Advisor(O)') {
		document.getElementById("Advisor(O)").classList.remove('d-none');
		document.getElementById("Advisor(O)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Advisoronrec(O)') {
		document.getElementById("Advisoronrec(O)").classList.remove('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Intprty(O)') {
		document.getElementById("Intprty(O)").classList.remove('d-none');
		document.getElementById("Intprty(O)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Advisor(C)') {
		document.getElementById("Advisor(C)").classList.remove('d-none');
		document.getElementById("Advisor(C)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Councel(C)') {
		document.getElementById("Councel(C)").classList.remove('d-none');
		document.getElementById("Councel(C)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');

	}
	if (id == 'Advonrec(C)') {
		document.getElementById("Advonrec(C)").classList.remove('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');

	}
	if (id == 'Int.Party(C)') {
		document.getElementById("Int.Party(C)").classList.remove('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Represent(C)') {
		document.getElementById("Represent(C)").classList.remove('d-none');
		document.getElementById("Represent(C)").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("referedBy").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'referedBy') {
		document.getElementById("referedBy").classList.remove('d-none');
		document.getElementById("referedBy").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Initials') {
		document.getElementById("Initials").classList.remove('d-none');
		document.getElementById("Initials").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("referedBy").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'RelatedMatters') {
		document.getElementById("RelatedMatters").classList.remove('d-none');
		document.getElementById("RelatedMatters").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
		document.getElementById("referedBy").classList.add('d-none');
	}
	if (id == 'Org.Rec') {
		document.getElementById("Org.Rec").classList.remove('d-none');
		document.getElementById("Org.Rec").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Case_Nos') {
		document.getElementById("Case_Nos").classList.remove('d-none');
		document.getElementById("Case_Nos").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Details') {
		document.getElementById("Details").classList.remove('d-none');
		document.getElementById("Details").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'Cheques') {
		document.getElementById("Cheques").classList.remove('d-none');
		document.getElementById("Cheques").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("bill_addr").classList.add('d-none');
	}
	if (id == 'bill_addr') {
		document.getElementById("bill_addr").classList.remove('d-none');
		document.getElementById("bill_addr").classList.add('d-block');
		document.getElementById("oSide").classList.add('d-none');
		document.getElementById("basic").classList.add('d-none');
		document.getElementById("cncl(0)").classList.add('d-none');
		document.getElementById("Advisor(O)").classList.add('d-none');
		document.getElementById("Advisoronrec(O)").classList.add('d-none');
		document.getElementById("Intprty(O)").classList.add('d-none');
		document.getElementById("Advisor(C)").classList.add('d-none');
		document.getElementById("Councel(C)").classList.add('d-none');
		document.getElementById("Advonrec(C)").classList.add('d-none');
		document.getElementById("Int.Party(C)").classList.add('d-none');
		document.getElementById("Represent(C)").classList.add('d-none');
		document.getElementById("Initials").classList.add('d-none');
		document.getElementById("RelatedMatters").classList.add('d-none');
		document.getElementById("Org.Rec").classList.add('d-none');
		document.getElementById("Case_Nos").classList.add('d-none');
		document.getElementById("Details").classList.add('d-none');
		document.getElementById("Cheques").classList.add('d-none');
	}
}
function advisor_otherside_delRow(n) {
	alert();
	var advisor_other_side_ok_ind = document.getElementById("advisor_other_side_ok_ind" + n).value;
	if (advisor_other_side_ok_ind == 'Y') {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "N";
	}
	else {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "Y";
	}
}
function otherside_delRow(n) {
	var other_side_ok_ind = document.getElementById("other_side_ok_ind" + n).value;
	if (other_side_ok_ind == 'Y') {
		document.getElementById("other_side_ok_ind" + n).value = "N";
		document.getElementById("other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("other_side_ok_ind" + n).value = "Y";
		document.getElementById("other_side_ok_ind" + n).checked = true;
	}
}
function counsel_otherside_delRow(n) {
	var counsel_other_side_ok_ind = document.getElementById("counsel_other_side_ok_ind" + n).value;
	if (counsel_other_side_ok_ind == 'Y') {
		document.getElementById("counsel_other_side_ok_ind" + n).value = "N";
		document.getElementById("counsel_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("counsel_other_side_ok_ind" + n).value = "Y";
		document.getElementById("counsel_other_side_ok_ind" + n).checked = true;
	}
}
function advisor_otherside_delRow(n) {
	var advisor_other_side_ok_ind = document.getElementById("advisor_other_side_ok_ind" + n).value;
	if (advisor_other_side_ok_ind == 'Y') {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "N";
		document.getElementById("advisor_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "Y";
		document.getElementById("advisor_other_side_ok_ind" + n).checked = true;
	}
}
function advisor_client_delRow(n) {
	var advisor_client_ok_ind = document.getElementById("advisor_client_ok_ind" + n).value;
	if (advisor_client_ok_ind == 'Y') {
		document.getElementById("advisor_client_ok_ind" + n).value = "N";
		document.getElementById("advisor_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("advisor_client_ok_ind" + n).value = "Y";
		document.getElementById("advisor_client_ok_ind" + n).checked = true;
	}
}
function int_party_otherside_delRow(n) {
	var int_party_other_side_ok_ind = document.getElementById("int_party_other_side_ok_ind" + n).value;
	if (int_party_other_side_ok_ind == 'Y') {
		document.getElementById("int_party_other_side_ok_ind" + n).value = "N";
		document.getElementById("int_party_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("int_party_other_side_ok_ind" + n).value = "Y";
		document.getElementById("int_party_other_side_ok_ind" + n).checked = true;
	}
}
function adv_on_rec_otherside_delRow(n) {
	var adv_on_rec_other_side_ok_ind = document.getElementById("adv_on_rec_other_side_ok_ind" + n).value;
	if (adv_on_rec_other_side_ok_ind == 'Y') {
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).value = "N";
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).value = "Y";
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).checked = true;
	}
}
function adv_on_rec_client_delRow(n) {
	var adv_on_rec_client_ok_ind = document.getElementById("adv_on_rec_client_ok_ind" + n).value;
	if (adv_on_rec_client_ok_ind == 'Y') {
		document.getElementById("adv_on_rec_client_ok_ind" + n).value = "N";
		document.getElementById("adv_on_rec_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("adv_on_rec_client_ok_ind" + n).value = "Y";
		document.getElementById("adv_on_rec_client_ok_ind" + n).checked = true;
	}
}
function int_party_client_delRow(n) {
	var int_party_client_ok_ind = document.getElementById("int_party_client_ok_ind" + n).value;
	if (int_party_client_ok_ind == 'Y') {
		document.getElementById("int_party_client_ok_ind" + n).value = "N";
		document.getElementById("int_party_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("int_party_client_ok_ind" + n).value = "Y";
		document.getElementById("int_party_client_ok_ind" + n).checked = true;
	}
}
function repr_by_client_delRow(n) {
	var repr_by_client_ok_ind = document.getElementById("repr_by_client_ok_ind" + n).value;
	if (repr_by_client_ok_ind == 'Y') {
		document.getElementById("repr_by_client_ok_ind" + n).value = "N";
		document.getElementById("repr_by_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("repr_by_client_ok_ind" + n).value = "Y";
		document.getElementById("repr_by_client_ok_ind" + n).checked = true;
	}
}
function related_matter_delRow(n) {
	var related_matter_ok_ind = document.getElementById("related_matter_ok_ind" + n).value;
	if (eval(related_matter_ok_ind == 'Y')) {
		document.getElementById("related_matter_ok_ind" + n).value = "N";
		document.getElementById("related_matter_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("related_matter_ok_ind" + n).value = "Y";
		document.getElementById("related_matter_ok_ind" + n).checked = true;
	}
}
function matter_org_delRow(n) {
	var matter_org_ok_ind = document.getElementById("matter_org_ok_ind" + n).value;
	if (eval(matter_org_ok_ind == 'Y')) {
		document.getElementById("matter_org_ok_ind" + n).value = "N";
		document.getElementById("matter_org_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("matter_org_ok_ind" + n).value = "Y";
		document.getElementById("matter_org_ok_ind" + n).checked = true;
	}
}
function related_matters(page) {
	if (page == "matterTypeAdd") {
		var row_no = parseInt(document.getElementById('matter_type_count').value) + 1;
		var count = row_no;
		document.getElementById('matter_type_count').value = count;
		$("#matterTypeDes").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border"><span><input type="text" class="form-control" name="matter_type_desc' + row_no + '" value=""  /></span><input type="hidden" class="form-control" name="matter_type_code' + row_no + '" value="" readonly /></td></tr>');
	}
	if (page == "matterSubTypeAdd") {
		var row_no = parseInt(document.getElementById('matter_sub_type_count').value) + 1;
		var count = row_no;
		document.getElementById('matter_sub_type_count').value = count;
		$("#matterSubTypeDes").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border"><span><input type="text" class="form-control" name="matter_sub_type_desc' + row_no + '" value="" /></span><input type="hidden" class="form-control" name="matter_sub_type_code' + row_no + '" value="" readonly /></td></tr>');
	}
	if (page == "relatedMatter") {
		var row_no = parseInt(document.getElementById('related_matter_count').value) + 1;
		var count = row_no;
		document.getElementById('related_matter_count').value = count;
		$("#relatedMatterTable").append('<tr id="rowID' + row_no + '" class="fs-14 border-0"><td class="border position-relative"><span><input type="checkbox" class="NwCbx_tbl" name="related_matter_ok_ind' + row_no + '" id="related_matter_ok_ind' + row_no + '" size="1" onclick="related_matter_delRow(' + row_no + ')"  value="Y" checked/></span><input type="hidden" name="related_matter_record_code' + row_no + '" value="" /> </td><td class="border position-relative"><div class="position-relative"><span><input type="text" class="form-control"	name="related_matter_code' + row_no + '"  id="relatedMatterCode' + row_no + '" onclick="related_matter_delRow(' + row_no + ')" onchange="fetchData(this, \'matter_code\'\, [\'relatedMatterCode' + row_no + '\'\, \'relatedMatterDescription' + row_no + '\'\], [\'matter_code\'\, \'mat_des\'\],\'relatedmatter_code\'\)" /><i class="fa fa-binoculars icn-vw icn-vw2" id="matterBinocular" onclick="showData(\'matter_code\'\, \'4204\'\, \'relatedMatterCode' + row_no + '\'\, [\'relatedMatterCode' + row_no + '\'\,\'relatedMatterDescription' + row_no + '\'\], [\'matter_code\'\,\'mat_des\'\],\'relatedmatter_code\'\);"  data-toggle="modal" data-target="#lookup" ></i></span></div></td><td class="border"><span><input type="text" class="form-control" name="related_matter_description' + row_no + '" id="relatedMatterDescription' + row_no + '"/></span></td></tr>');
	}
	if (page == "orgMatter") {
		var row_no = parseInt(document.getElementById('matter_org_count').value) + 1;
		var count = row_no;
		document.getElementById('matter_org_count').value = count;
		$("#orgMatter").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="matter_org_ok_ind' + row_no + '"  id="matter_org_ok_ind' + row_no + '" size="1" value="Y" onclick="matter_org_delRow(' + row_no + ')" checked/></span><input type="hidden" name="matter_org_record_code' + row_no + '" value="" /></td><td class="border"><span><input type="text" class="form-control" name="matter_org_record_desc' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="matter_org_remarks' + row_no + '" value=""/></span></td><td class="border"><span><input type="text" class="form-control" name="matter_org_filelocation' + row_no + '" value=""/></span></td><td class="border"><span><input type="text" class="form-control datepicker2"	name="matter_org_receivedon' + row_no + '" value="" onblur="make_date(this)"/></span></td><td class="border"><span><input type="text" class="form-control datepicker2" id="matter_org_returnon" name="matter_org_returnon' + row_no + '" value="" onblur="make_date(this)"/></span></td></tr>');
	}
	if (page == "caseNo") {
		var row_no = parseInt(document.getElementById('case_no_count').value) + 1;
		var count = row_no;
		document.getElementById('case_no_count').value = count;
		$("#caseNoTable").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border"><span><input type="text" class="form-control" name="other_case_no' + row_no + '" value=""/></span></td><td class="border"><span><input type="text" class="form-control" name="other_subject' + row_no + '" value=""/></span></td></tr>');
	}
	if (page == "cheques") {
		var row_no = parseInt(document.getElementById('cheques_count').value) + 1;
		var count = row_no;
		document.getElementById('cheques_count').value = count;
		$("#chequesTable").append('<tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border"><span><input type="text" class="form-control" name="matter_instrument_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control datepicker2"	name="matter_instrument_dt' + row_no + '" onblur="make_date(this)"/></span></td><td class="border"><span><input type="text" class="form-control" name="matter_bank_name' + row_no + '"/></span></td><td class="border"><span><input type="text" class="form-control" name="matter_instr_amt' + row_no + '"/></span></td></tr>');
	}
	if (page == "otherSide") {
		var row_no = parseInt(document.getElementById('other_side_count').value) + 1;
		var count = row_no;
		document.getElementById('other_side_count').value = count;
		$("#otherSideTable").append('<tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" class="" name="other_side_ok_ind' + row_no + '" id="other_side_ok_ind' + row_no + '" size="1"	value="Y" onclick="otherside_delRow(' + row_no + ')" checked/></span><input type="hidden" name="other_side_record_code<?php echo $key ?>" value="1" /></td><td class="border"><span><input type="text" class="form-control" name="other_side_name' + row_no + '" id="otherSideName"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_address_line_1' + row_no + '"	id="other_sideAddressLine1"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_address_line_2' + row_no + '" id="other_sideAddressLine2"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_address_line_3' + row_no + '"	id="other_sideAddressLine3"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_address_line_4' + row_no + '"	id="other_sideAddressLine4"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_city' + row_no + '" id="otherSideCity" value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_pin_code' + row_no + '" id="otherSidePinCode"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_state_name' + row_no + '"	id="otherSideStateName"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_country' + row_no + '"	id="otherSideCountry" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_isd_code' + row_no + '"	id="otherSideIsdCode" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_std_code' + row_no + '"	id="otherSideStdCode" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_phone_no' + row_no + '" id="otherSidePhoneNo" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_mobile_no' + row_no + '" id="otherSideMobileNo" value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="other_side_email_id' + row_no + '" id="otherSideEmailId" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_fax_no' + row_no + '" id="otherSideFaxNo" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_company_name' + row_no + '" id="otherSideCompanyName" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_designation' + row_no + '" id="otherSideDesignation" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="other_side_office_tel' + row_no + '" value="" /></span></td></tr>');
	}
	if (page == "counsel") {
		var row_no = parseInt(document.getElementById('counsel_other_side_count').value) + 1;
		var count = row_no;
		document.getElementById('counsel_other_side_count').value = count;
		$("#counselTable").append('<tr class="fs-14 border-0" id="rodId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="counsel_other_side_ok_ind' + row_no + '"  id="counsel_other_side_ok_ind' + row_no + '" size="1" value="Y" onclick="counsel_otherside_delRow(' + row_no + ')" checked /></span><input type="hidden" name="counsel_other_side_record_code' + row_no + '" value="2" /></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_name' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_address_line_1" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_address_line_2' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_address_line_3' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="counsel_other_side_address_line_4' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_city' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_pin_code' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_state_name' + row_no + '"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="counsel_other_side_country' + row_no + '"	value="" /></span></td><td class="border"><span><input type="text" class="form-control"	name="counsel_other_side_isd_code' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_std_code' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_phone_no' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_mobile_no' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_email_id' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_fax_no' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_company_name' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_designation' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="counsel_other_side_office_tel' + row_no + '" value="" /></span></td></tr>');
	}
	if (page == "advisor") {
		var row_no = parseInt(document.getElementById('advidor_other_side_count').value) + 1;
		var count = row_no;
		document.getElementById('advidor_other_side_count').value = count;
		$("#advisorTable").append('<tr class="fs-14 border-0" id="rowID' + row_no + '">	<td class="border cbxtd text-center"><span><input type="checkbox" name="advisor_other_side_ok_ind' + row_no + '" id="advisor_other_side_ok_ind' + row_no + '" size="1" value="Y" onclick="advisor_otherside_delRow(' + row_no + ')" checked/></span><input type="hidden" name="advisor_other_side_record_code' + row_no + '" value="3" /></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_other_side_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_other_side_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_other_side_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_other_side_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_other_side_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_other_side_office_tel' + row_no + '" /></span></td></tr>');
	}
	if (page == "advOnRecTable") {
		var row_no = parseInt(document.getElementById('advidor_other_side_count').value) + 1;
		var count = row_no;
		document.getElementById('advidor_other_side_count').value = count;
		$("#advOnRecTable").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="adv_on_rec_other_side_ok_ind' + row_no + '" id="adv_on_rec_other_side_ok_ind' + row_no + '" size="1" value="Y" onclick="adv_on_rec_otherside_delRow(' + row_no + ')" checked/></span><input type="hidden"	name="adv_on_rec_other_side_record_code' + row_no + '"	value="4" /></td><td class="border"><span><span><input type="text" class="form-control"	name="adv_on_rec_other_side_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_other_side_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_other_side_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_office_tel' + row_no + '" /></span></td></tr>');
	}
	// if(page=="advOnRecTable")
	// {
	// 	var row_no =parseInt(document.getElementById('advidor_other_side_count').value)+1;
	// 	var count =row_no;
	// 	document.getElementById('advidor_other_side_count').value=count;
	// 	$("#advOnRecTable").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="adv_on_rec_other_side_ok_ind' + row_no + '" id="adv_on_rec_other_side_ok_ind' + row_no + '" size="1" value="Y" onclick="adv_on_rec_otherside_delRow(' + row_no + ')" checked/></span><input type="hidden"	name="adv_on_rec_other_side_record_code' + row_no + '"	value="4" /></td><td class="border"><span><span><input type="text" class="form-control"	name="adv_on_rec_other_side_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_other_side_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_other_side_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_other_side_office_tel' + row_no + '" /></span></td></tr>');
	// }
	if (page == "intprty") {
		var row_no = parseInt(document.getElementById('int_party_othher_side_count').value) + 1;
		var count = row_no;
		document.getElementById('int_party_othher_side_count').value = count;
		$("#intprtyTable").append('<tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="int_party_other_side_ok_ind' + row_no + '" id="int_party_other_side_ok_ind' + row_no + '" size="1" value="Y" onclick="int_party_otherside_delRow(' + row_no + ')" checked/></span><input type="hidden" name="int_party_other_side_record_code' + row_no + '" value="5" /></td><td class="border"><span><span><input type="text"	class="form-control" name="int_party_other_side_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_other_side_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_other_side_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_other_side_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_other_side_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_other_side_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_other_side_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_other_side_office_tel' + row_no + '" /></span></td></tr>');
	}
	if (page == "advisorC") {
		var row_no = parseInt(document.getElementById('advisor_client_count').value) + 1;
		var count = row_no;
		document.getElementById('advisor_client_count').value = count;
		$("#advisorCTable").append('<tr class="fs-14 border-0" id="rowID' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="advisor_client_ok_ind' + row_no + '" id="advisor_client_ok_ind' + row_no + '"	size="1" value="Y" onclick="advisor_client_delRow(' + row_no + ')" checked/></span><input type="hidden"	name="advisor_client_record_code' + row_no + '"	value="6" /></td><td class="border"><span><span><input type="text" class="form-control"	name="advisor_client_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="advisor_client_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="advisor_client_office_tel' + row_no + '" /></span></td></tr>');
	}
	if (page == "advOnRec") {
		var row_no = parseInt(document.getElementById('adv_rec_client_count').value) + 1;
		var count = row_no;
		document.getElementById('adv_rec_client_count').value = count;
		$("#advOnReCTable").append('<tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="adv_on_rec_client_ok_ind' + row_no + '" id="adv_on_rec_client_ok_ind' + row_no + '" size="1" value="Y" onclick="adv_on_rec_client_delRow(' + row_no + ')" checked/></span><input type="hidden" name="adv_on_rec_client_record_code' + row_no + '"value="8" /></td><td class="border"><span><span><input type="text" class="form-control" name="adv_on_rec_client_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_client_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_client_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_client_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_client_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_client_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="adv_on_rec_client_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="adv_on_rec_client_office_tel' + row_no + '" /></span></td></tr>');
	}
	if (page == "inPartyC") {
		var row_no = parseInt(document.getElementById('int_party_client_count').value) + 1;
		var count = row_no;
		document.getElementById('int_party_client_count').value = count;
		$("#intpartCTable").append(' <tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="int_party_client_ok_ind' + row_no + '"  id="int_party_client_ok_ind' + row_no + '" size="1" value="Y" onclick="int_party_client_delRow(' + row_no + ')" checked/></span><input type="hidden" name="int_party_client_record_code' + row_no + '"	value="9" /></td><td class="border"><span><span><input type="text"	class="form-control" name="int_party_client_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_phone_no' + row_no + '" /></span> </td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="int_party_client_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="int_party_client_office_tel' + row_no + '" /></span></td></tr>');
	}
	if (page == "reprByClient") {
		var row_no = parseInt(document.getElementById('repr_by_client_count').value) + 1;
		var count = row_no;
		document.getElementById('repr_by_client_count').value = count;
		$("#representCTable").append('<tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="repr_by_client_ok_ind' + row_no + '" id="repr_by_client_ok_ind' + row_no + '" size="1" value="Y" onclick="repr_by_client_delRow(' + row_no + ')" checked/></span><input type="hidden" name="repr_by_client_record_code<?php echo $key2 ?>" value="10" /></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_address_line_1' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_address_line_2' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_address_line_3' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_address_line_4' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_city' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control"  name="repr_by_client_pin_code' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_state_name' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_country' + row_no + '"  value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_isd_code' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_std_code' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_phone_no' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_mobile_no' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_email_id' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_fax_no' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control" name="repr_by_client_company_name' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control"  name="repr_by_client_designation' + row_no + '" value="" /></span></td><td class="border"><span><input type="text" class="form-control"  name="repr_by_client_office_tel' + row_no + '"  value="" /></span></td></tr>');
	}
	if (page == "refByClient") {
		var row_no = parseInt(document.getElementById('ref_by_client_count').value) + 1;
		var count = row_no;
		document.getElementById('ref_by_client_count').value = count;
		$("#referedByTable").append('<tr class="fs-14 border-0" id="rowId' + row_no + '"><td class="border cbxtd text-center"><span><input type="checkbox" name="ref_by_client_ok_ind' + row_no + '" id="ref_by_client_ok_ind' + row_no + '" size="1" value="Y" onclick="ref_by_delRow(' + row_no + ')" checked/></span><input type="hidden" name="ref_by_client_record_code' + row_no + '"	value="6" /></td><td class="border"><span><span><input type="text" class="form-control"	name="ref_by_client_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_address_line_1' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="ref_by_client_address_line_2' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_address_line_3' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_address_line_4' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_city' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_pin_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_state_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_country' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="ref_by_client_isd_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_std_code' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_phone_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_mobile_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_email_id' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="ref_by_client_fax_no' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_company_name' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control"	name="ref_by_client_designation' + row_no + '" /></span></td><td class="border"><span><input type="text" class="form-control" name="ref_by_client_office_tel' + row_no + '" /></span></td></tr>');
	}

}
function addSigRow() {
	var row_no = parseInt(document.getElementById('sigRowCount').value) + 1;
	var count = row_no;
	document.getElementById('sigRowCount').value = count;
	$("#sigTab").append('<tr id="rowId' + row_no + '"><td>' + row_no + '</td><td><input type="text" class="form-control float-start w-90" placeholder="Name" name="signatory_name' + row_no + '" id="signatory_name' + row_no + '" value=""/></td><td><input type="text" class="form-control float-start w-100" placeholder="Designation" name="signatory_desg' + row_no + '" id="signatory_desg' + row_no + '" value=""/></td></tr>');
}
function displayPage() {

	$('#page_menu').addClass('d-block');
	$('#page_menu').removeClass('d-none');

	$('#permission_on_menu').addClass('d-none');

}

function account_master_check() {
	var main_ac_code = document.getElementById("main_ac_code").value;
	var main_ac_desc = document.getElementById("main_ac_desc").value;
	var act_group_code = document.getElementById("act_group_code").value;
	var act_group_desc = document.getElementById("act_group_desc").value;
	if (main_ac_code == '') {
		alertMSG('info', '<strong> A/C Code Cannot Be Empty</strong>');
		document.getElementById("main_ac_code").focus();
		return false;
	}
	else if (main_ac_desc == '') {
		alertMSG('info', '<strong> A/C Name Cannot Be Empty</strong>');
		document.getElementById("main_ac_desc").focus();
		return false;
	}
	else if (act_group_code == "" || act_group_desc == "") {
		alertMSG('info', '<strong> A/C Group Cannot Be Empty</strong>');
		document.getElementById("act_group_desc").focus();
		return false;
	}
	document.getElementById("submit").disabled = true;

}
function client_code_check() {
	var client_code = document.getElementById("clientCode").value;
	if (client_code != '') {
		alert("Your Client Code Is : " + client_code);
	}

}
function validation() {
	var ind = 1;
	if (document.getElementById('clientName').value == '') {
		alert('Please Enter Client Name : ', 1);
		document.getElementById('clientName').focus();
		ind = 0;
	}
	if (document.getElementById('clientGroupName').value == '') {
		alert('Please Enter Client Group');
		document.getElementById('clientGroupName').focus();
		ind = 0;
	}
}

/*========================== anindita 01-08-2023======================*/
function validnumbercheck(e) {

	var key = window.event ? e.keyCode : e.which;

	//alert(key);
	//if (key=="48" || key=="49" || key=="50" || key=="51" || key=="52" || key=="53" || key=="54" || key=="55" || key=="56" || key=="57")
	var keychar = String.fromCharCode(key);
	reg = /\d/;
	if (reg.test(keychar) || key == "46" || key == "8" || key == "0") {
		return true
	}
	else {
		return false
	}
}

function changeCase(fld, n) {
	// var test = fld.value;
	// alert(test);
	var ind = 1;
	if (fld.value != '') {
		var val = fld.value;
		val = val.toUpperCase();

		if (val == 'I' || val == 'C') {
			fld.value = val;
		}
		else {
			Swal.fire({ text: 'Enter Proper value ... Either "I" for Inpocket Or "C" for Counsel ...' }).then((result) => { setTimeout(() => { fld.focus() }, 500) });
			//alert("Enter Proper value .......        \n\nEither \" I \" for Inpocket... \n\nOr \n\n\" C \" for Counsel ...");
			//fld.focus();
			fld.select();
			ind = 0;
			return false;
		}
	}
}

function amtValidate(fld, r_no, grid_no) {
	if (fld.value < 0) {
		Swal.fire({ text: 'Amount must be > 0' }).then((result) => { setTimeout(() => { fld.focus() }, 500) });
		//alert('Amount must be > 0')
		//fld.focus() ;
		return false;
	}
	else {
		// validateNumber(fld, "Amount : ",2);
		clacTotalAmt();
		if (fld.value > 0) {
			if (grid_no == 'Grid1') {
				document.getElementById("printer_ind" + r_no).checked = true;
				document.getElementById("prn_seq_no" + r_no).focus();
			}
			if (grid_no == 'Grid2') {
				document.getElementById("printer" + r_no).checked = true;
				document.getElementById("prn_seq" + r_no).focus();
			}
		}
	}
}

function clacTotalAmt() {
	calcCounselTot();
	calcInpocketTot();
	calcOutpocketTot();

	var total = 0;
	var inpocket = document.getElementById("bill_amount_inpocket").value;
	var outpocket = document.getElementById("bill_amount_outpocket").value;
	var counsel = document.getElementById("bill_amount_counsel").value;

	total = inpocket + outpocket + counsel;
	total = parseFloat(total).toFixed(2);
	document.getElementById("total_amount").value = total;
}

function calcCounselTot() {
	var tot_row = document.getElementById("row_no1").value;
	tot_row = tot_row;
	var cousel_amt = 0;
	var tot_counsel = 0;
	for (var i = 1; i <= tot_row; i++) {
		if (document.getElementById("io_ind" + i).value == 'C') {
			counsel_amt = document.getElementById("billed_amount" + i).value;
			tot_counsel = tot_counsel + counsel_amt;
		}
	}
	tot_counsel = parseFloat(tot_counsel).toFixed(2);
	document.getElementById("bill_amount_counsel").value = tot_counsel;
}

function calcInpocketTot() {
	var tot_row = document.getElementById("row_no1").value;
	//alert(tot_row);
	//tot_row = "";
	tot_row = tot_row;

	var inpocket_amt = 0;
	var tot_inpocket = 0;
	for (var i = 1; i <= tot_row; i++) {
		//alert(eval("document.f1.io_ind"+i+".value"));
		if (document.getElementById("io_ind" + i).value == 'I') {
			inpocket_amt = document.getElementById("billed_amount" + i).value;
			tot_inpocket = tot_inpocket + inpocket_amt;
		}
	}
	tot_inpocket = parseFloat(tot_inpocket).toFixed(2);
	document.getElementById("bill_amount_inpocket").value = tot_inpocket;
}

function calcOutpocketTot() {
	var tot_row = document.getElementById("row_no2").value;
	tot_row = tot_row;
	var outocket_amt = 0;
	var tot_outpocket = 0;
	for (var i = 1; i <= tot_row; i++) {
		outocket_amt = document.getElementById("amount" + i).value;
		tot_outpocket = tot_outpocket + outocket_amt;
	}
	tot_outpocket = parseFloat(tot_outpocket).toFixed(2);
	document.getElementById("bill_amount_outpocket").value = tot_outpocket;
}

function inpocket_delRow(e, n) {
	var row = document.getElementById("Ctd" + n);
	var tax_per = document.getElementById("tax_per").value;
	document.getElementById("service_tax_ind" + n).checked = false;
	if (document.getElementById("inp_ok_ind" + n).value == 'Y') {
		$(e).parent('tr').addClass('rowSlcted');
		document.getElementById("inp_ok_ind" + n).value = 'N';
		document.getElementById("inp_ok_ind" + n).style.background = 'rgb(163 200 213)';
		document.getElementById("inp_ok_ind" + n).style.color = 'rgb(163 200 213)';
		row.style.background = 'rgb(163 200 213)';
		calc_service_tax(n, tax_per, 'Grid1');
	} else {
		$(e).parent('tr').removeClass('rowSlcted');
		document.getElementById("inp_ok_ind" + n).value = 'Y';
		document.getElementById("inp_ok_ind" + n).style.background = '#fff';
		document.getElementById("inp_ok_ind" + n).style.color = '#fff';
		row.style.background = '#fff';
		calc_service_tax(n, tax_per, 'Grid1');
	}
}

function outpocket_delRow(e, n) {
	var row = document.getElementById("Ctd2" + n);
	var tax_per = document.getElementById("tax_per").value;
	document.getElementById("tax_ind" + n).checked = false;
	if (document.getElementById("out_ok_ind" + n).value == 'Y') {
		$(e).parent('tr').addClass('rowSlcted');
		document.getElementById("out_ok_ind" + n).value = 'N';
		document.getElementById("out_ok_ind" + n).style.background = 'rgb(163 200 213)';
		document.getElementById("out_ok_ind" + n).style.color = 'rgb(163 200 213)';
		row.style.background = 'rgb(163 200 213)';
		calc_service_tax(n, tax_per, 'Grid2');
		//adjust_value(n);
	} else {
		$(e).parent('tr').removeClass('rowSlcted');
		document.getElementById("out_ok_ind" + n).value = 'Y';
		document.getElementById("out_ok_ind" + n).style.background = '#fff';
		document.getElementById("out_ok_ind" + n).style.color = '#fff';
		row.style.background = '#fff';
		calc_service_tax(n, tax_per, 'Grid2');
		//row.style.background='#ECE8D7';
		//re_adjust_value(n);
	}
	//amount_calc();
}

// function calc_service_tax(rno, taxper, gridno) {
// 	if (gridno == "Grid1") {
// 		var inp_ok_ind = document.getElementById("inp_ok_ind" + rno).value;
// 		if (document.getElementById("service_tax_ind" + rno).checked == true) {
// 			var ioind = document.getElementById("io_ind" + rno).value;
// 			var bilamt = document.getElementById("billed_amount" + rno).value;
// 			var taxamt = (bilamt * taxper) / 100;

// 			document.getElementById("service_tax_percent" + rno).value = taxper;
// 			document.getElementById("service_tax_amount" + rno).value = taxamt;
// 			format_number(document.getElementById("service_tax_amount" + rno), 2);

// 			calc_total_amount();
// 		}
// 		else {
// 			var ioind = document.getElementById("io_ind" + rno).value;
// 			var bilamt = document.getElementById("billed_amount" + rno).value;
// 			// var taxamt = Math.round(bilamt*taxper/100,0);
// 			var taxamt = (bilamt * taxper) / 100;

// 			document.getElementById("service_tax_percent" + rno).value = '';
// 			document.getElementById("service_tax_amount" + rno).value = '';

// 			calc_total_amount();
// 		}
// 	}
// 	else if (gridno == "Grid2") {
// 		if (document.getElementById("tax_ind" + rno).checked == true) {
// 			var ioind = 'O';
// 			var bilamt = document.getElementById("amount" + rno).value;
// 			var taxamt = (bilamt * taxper) / 100;


// 			document.getElementById("tax_percent" + rno).value = taxper;
// 			document.getElementById("tax_amount" + rno).value = taxamt;
// 			format_number(document.getElementById("tax_amount" + rno), 2);

// 			calc_total_amount();


// 		}
// 		else {
// 			var ioind = 'O';
// 			var bilamt = document.getElementById("amount" + rno).value;

// 			var taxamt = (bilamt * taxper) / 100;


// 			document.getElementById("tax_percent" + rno).value = '';
// 			document.getElementById("tax_amount" + rno).value = '';

// 			calc_total_amount();
// 		}
// 	}
// }

function calc_total_amount() {
	var ip_stax = 0; var ip_ntax = 0; var ip_amt = 0; var svtax_ip = 0;
	var op_stax = 0; var op_ntax = 0; var op_amt = 0; var svtax_op = 0;
	var cn_stax = 0; var cn_ntax = 0; var cn_amt = 0; var svtax_cn = 0;

	for (i = 1; i <= document.getElementById('row_no1').value; i++) {
		var ioind = document.getElementById("io_ind" + i).value;
		var bilamt = document.getElementById("billed_amount" + i).value;
		var taxamt = document.getElementById("service_tax_amount" + i).value;
		var inp_ok_ind = document.getElementById("inp_ok_ind" + i).value;
		if (inp_ok_ind == 'Y') {
			if (document.getElementById("service_tax_ind" + i).checked == true) {
				if (ioind == 'I') {
					ip_stax += parseFloat(bilamt); ip_ntax += 0; ip_amt += parseFloat(bilamt); svtax_ip += parseFloat(taxamt);
				}
				else if (ioind == 'C') {
					cn_stax += parseFloat(bilamt); cn_ntax += 0; cn_amt += parseFloat(bilamt); svtax_cn += parseFloat(taxamt);
				}
			}
			else if (document.getElementById("service_tax_ind" + i).checked == false) {
				if (ioind == 'I') {
					ip_stax += 0; ip_ntax += parseFloat(bilamt); ip_amt += parseFloat(bilamt); svtax_ip += 0;
				}
				else if (ioind == 'C') {
					cn_stax += 0; cn_ntax += parseFloat(bilamt); cn_amt += parseFloat(bilamt); svtax_cn += 0;
				}
			}
		}
	}

	//---
	for (i = 1; i <= document.getElementById("row_no2").value; i++) {
		var ioind = 'O';
		var bilamt = document.getElementById("amount" + i).value;
		var taxamt = document.getElementById("tax_amount" + i).value;
		var out_ok_ind = document.getElementById("out_ok_ind" + i).value;
		if (out_ok_ind == 'Y') {
			if (document.getElementById("tax_ind" + i).checked == true) {
				op_stax += parseFloat(bilamt); op_ntax += 0; op_amt += parseFloat(bilamt); svtax_op += parseFloat(taxamt);
			}
			else if (document.getElementById("tax_ind" + i).checked == false) {
				op_stax += 0; op_ntax += parseFloat(bilamt); op_amt += parseFloat(bilamt); svtax_op += 0;
			}
		}
	}
	//---
	var tbamt_stax = parseFloat(ip_stax) + parseFloat(op_stax) + parseFloat(cn_stax);
	var tbamt_ntax = parseFloat(ip_ntax) + parseFloat(op_ntax) + parseFloat(cn_ntax);
	var tbill_amt = parseFloat(ip_amt) + parseFloat(op_amt) + parseFloat(cn_amt);
	var tstax_amt = parseFloat(svtax_ip) + parseFloat(svtax_op) + parseFloat(svtax_cn);

	var nbill_amt = tbill_amt + tstax_amt;

	//---
	if (ip_stax > 0) { document.getElementById("bill_amount_inpocket_stax").value = ip_stax; format_number(document.getElementById("bill_amount_inpocket_stax"), 2); } else { document.getElementById("bill_amount_inpocket_stax").value = ''; }
	if (ip_ntax > 0) { document.getElementById("bill_amount_inpocket_ntax").value = ip_ntax; format_number(document.getElementById("bill_amount_inpocket_ntax"), 2); } else { document.getElementById("bill_amount_inpocket_ntax").value = ''; }
	if (ip_amt > 0) { document.getElementById("bill_amount_inpocket").value = ip_amt; format_number(document.getElementById("bill_amount_inpocket"), 2); } else { document.getElementById("bill_amount_inpocket").value = ''; }
	if (svtax_ip > 0) { document.getElementById("service_tax_inpocket").value = svtax_ip; format_number(document.getElementById("service_tax_inpocket"), 2); } else { document.getElementById("service_tax_inpocket").value = ''; }

	if (op_stax > 0) { document.getElementById("bill_amount_outpocket_stax").value = op_stax; format_number(document.getElementById("bill_amount_outpocket_stax"), 2); } else { document.getElementById("bill_amount_outpocket_stax").value = ''; }
	if (op_ntax > 0) { document.getElementById("bill_amount_outpocket_ntax").value = op_ntax; format_number(document.getElementById("bill_amount_outpocket_ntax"), 2); } else { document.getElementById("bill_amount_outpocket_ntax").value = ''; }
	if (op_amt > 0) { document.getElementById("bill_amount_outpocket").value = op_amt; format_number(document.getElementById("bill_amount_outpocket"), 2); } else { document.getElementById("bill_amount_outpocket").value = ''; }
	if (svtax_op > 0) { document.getElementById("service_tax_outpocket").value = svtax_op; format_number(document.getElementById("service_tax_outpocket"), 2); } else { document.getElementById("service_tax_outpocket").value = ''; }

	if (cn_stax > 0) { document.getElementById("bill_amount_counsel_stax").value = cn_stax; format_number(document.getElementById("bill_amount_counsel_stax"), 2); } else { document.getElementById("bill_amount_counsel_stax").value = ''; }
	if (cn_ntax > 0) { document.getElementById("bill_amount_counsel_ntax").value = cn_ntax; format_number(document.getElementById("bill_amount_counsel_ntax"), 2); } else { document.getElementById("bill_amount_counsel_ntax").value = ''; }
	if (cn_amt > 0) { document.getElementById("bill_amount_counsel").value = cn_amt; format_number(document.getElementById("bill_amount_counsel"), 2); } else { document.getElementById("bill_amount_counsel").value = ''; }
	if (svtax_cn > 0) { document.getElementById("service_tax_counsel").value = svtax_cn; format_number(document.getElementById("service_tax_counsel"), 2); } else { document.getElementById("service_tax_counsel").value = ''; }

	if (tbamt_stax > 0) { document.getElementById("total_bill_amount_stax").value = tbamt_stax; format_number(document.getElementById("total_bill_amount_stax"), 2); } else { document.getElementById("total_bill_amount_stax").value = ''; }
	if (tbamt_ntax > 0) { document.getElementById("total_bill_amount_ntax").value = tbamt_ntax; format_number(document.getElementById("total_bill_amount_ntax"), 2); } else { document.getElementById("total_bill_amount_ntax").value = ''; }
	if (tbill_amt > 0) { document.getElementById("total_amount").value = tbill_amt; format_number(document.getElementById("total_amount"), 2); } else { document.getElementById("total_amount").value = ''; }
	if (tstax_amt > 0) { document.getElementById("total_service_tax").value = tstax_amt; format_number(document.getElementById("total_service_tax"), 0); } else { document.getElementById("total_service_tax").value = ''; }
	//alert(nbill_amt);
	if (nbill_amt > 0) { document.getElementById("net_bill_amount").value = nbill_amt; format_number(document.getElementById("net_bill_amount"), 0); } else { document.getElementById("net_bill_amount").value = ''; }
}

function indexOf(item, i) {
	i || (i = 0);
	var length = this.length;
	if (i < 0) i = length + i;
	for (; i < length; i++)
		if (this[i] === item) return i;
	return -1;
}

//------ Formatting Numbers
function format_number(object1, object2) {
	var amt1 = object1.value;
	var decp = object2;
	var mamt1 = amt1;
	var mdecp = decp;
	var strlen = mamt1.length;
	var decpos = mamt1.indexOf(".");
	var strint = "";
	var strdec = "";
	var intlen = 0;
	var declen = 0;
	var aint = "";
	var adec = "";
	var aintlen = 0;
	var adeclen = 0;
	var aintlen = 0;
	var adeclen = 0;
	var aintno = 0;
	var adecno = 0;
	var ndec = "";
	var ndecno = 0;
	var decfac = "10";
	var intadd = 0;
	var decadd = 0;
	var digcnt = 0;
	var retint = "";
	var retdec = "";
	//------ Find Ount Sign
	if (object1.value < 0) {
		var sign = '-';
	}
	else {
		var sign = '';
	}
	//------ Find Out Integer and Decimals  
	if (decpos == -1) {
		strint = mamt1.substr(0, strlen);
		strdec = "";
	}
	else {
		strint = mamt1.substr(0, decpos);
		strdec = mamt1.substr(decpos + 1);
	}
	if (strint == "") { strint = "0"; }
	//------ Re-generate Integer and Decimals without comma
	for (i = 0; i < strint.length; i++) {
		if (strint.substr(i, 1) >= 0 || strint.substr(i, 1) <= 9) {
			aint = aint + strint.substr(i, 1);
		}
	}

	for (i = 0; i < strdec.length; i++) {
		if (strdec.substr(i, 1) >= 0 || strdec.substr(i, 1) <= 9) {
			adec = adec + strdec.substr(i, 1);
		}
	}
	aintlen = aint.length;
	adeclen = adec.length;
	aintno = parseInt(aint);
	adecno = parseInt(adec);
	ndec = decfac + adec;
	//------- Formatting Decimal Places 
	if (adeclen > parseInt(mdecp)) {
		decadd = 0;
		if (parseInt(ndec.substr(2 + parseInt(mdecp), 1)) >= 5) { decadd = 1; }
		ndecno = parseInt(ndec.substr(0, 2 + parseInt(mdecp))) + decadd;
		adec = ("" + ndecno).substr(2, parseInt(mdecp));
		intadd = ("" + ndecno).substr(1, 1);
		aintno = aintno + parseInt(intadd);
		aint = "" + aintno;
	}
	adeclen = adec.length;
	retdec = adec;
	for (i = (adeclen + 1); i <= parseInt(mdecp); i++) {
		retdec = retdec + '0';
	}
	//------- Formatting Integer Places 
	aintlen = aint.length;
	retint = "";
	for (i = (aintlen - 1); i >= 0; i--) {
		digcnt = digcnt + 1;
		if (digcnt == 4) {
			retint = retint;
			digcnt = 1;
		}
		retint = aint.substr(i, 1) + retint;
	}
	//------ Return Value  
	rvalue = retint;
	if (retdec != "") { rvalue = rvalue + "." + retdec; }
	//----------------------------------------------
	object1.value = sign + rvalue;
}

function myCheckPrintInd(rowno, gridno) {
	if (gridno == 'Grid1') {
		if (document.getElementById("printer_ind" + rowno).checked == false) {
			document.getElementById("billed_amount" + rowno).value = '';
			clacTotalAmt();
		}
	}
	else if (gridno == 'Grid2') {
		if (document.getElementById("printer" + rowno).checked == false) {
			document.getElementById("amount" + rowno).value = '';
			clacTotalAmt();
		}
	}

}

function make_date(obj) {
	var objdate = obj.value;
	var datelen = objdate.length;
	if (objdate != '' && objdate != '00-00-0000') {
		var dtCh = "-";
		var dtCh1 = "/";
		var dtCh2 = ".";
		index = objdate.indexOf(dtCh);
		index1 = objdate.indexOf(dtCh1);
		index2 = objdate.indexOf(dtCh2);
		if (objdate.indexOf('--', 0) != -1) {
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
			//alert("Invalid Date Format");	
			obj.value = '';
			//obj.focus();
		}
		else {
			if ((index > 0 && index1 < 0) || (index1 > 0 && index < 0)) {
				var pos11 = objdate.indexOf(dtCh);
				var pos12 = objdate.indexOf(dtCh, pos11 + 1);
			}
			else if ((index < 0 && index1 < 0)) {
				var pos11 = 1;
				var pos12 = 1;
			}
			if (((pos11 > 0 && pos12 > 0) || (pos11 < 0 && pos12 < 0))) {
				if (datelen == 4)		//dmyy
				{
					first = objdate.substr(0, 1);
					dd = 0 + first;
					second = objdate.substr(1, 1);
					mm = 0 + second;
					last = objdate.substr(2, 3);
					if (last <= 80) {
						yyyy = 20 + last;
					}
					if (last > 80) {
						yyyy = 19 + last;
					}
					obj.value = dd + "-" + mm + "-" + yyyy;
				}
				else if (datelen == 5)		//d-m-y
				{
					if (index >= 1 || index1 >= 1 || index2 >= 2) {
						first = objdate.substr(0, 1);
						dd = 0 + first;
						second = objdate.substr(2, 1);
						mm = 0 + second;
						last = objdate.substr(4, 3);
						yyyy = 200 + last;
						obj.value = dd + "-" + mm + "-" + yyyy;
					}
					else {
						Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date Format");	
						obj.value = '';
						//obj.focus();
					}
				}
				else if (datelen == 6)		//d-m-yy,ddmmyy,dd-m-y,d-mm-y
				{
					if (index == 1 || index1 == 1 || index2 == 1)		//d-m-yy
					{
						first = objdate.substr(0, 1);
						dd = 0 + first;
						if (pos12 == 3) {
							second = objdate.substr(2, 1);
							mm = 0 + second;
							last = objdate.substr(4, 4);
							if (last <= 80) {
								yyyy = 20 + last;
							}
							if (last > 80) {
								yyyy = 19 + last;
							}
							obj.value = dd + "-" + mm + "-" + yyyy;
						}
						else if (pos12 == 4) {
							second = objdate.substr(2, 2);
							mm = second;
							last = objdate.substr(5, 6);
							yyyy = 200 + last;
							if (mm > 12 || mm <= '00') {
								Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
								//alert("Invalid Date");	
								obj.value = '';
								//obj.focus();
							}
							obj.value = dd + "-" + mm + "-" + yyyy;
						}
						else {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");	
							obj.value = '';
							//obj.focus();
						}
					}
					else if (index == 2 || index1 == 2 || index2 == 2) {
						first = objdate.substr(0, 2);
						second = objdate.substr(3, 1);
						mm = 0 + second;
						last = objdate.substr(5, 6);
						yyyy = 200 + last;
						if (first > 31 || first <= '00') {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");
							obj.value = '';
							//obj.focus();
						}
						else {
							obj.value = first + "-" + mm + "-" + yyyy;
						}
					}
					else if (index == -1 && index == -1 && index2 == -1)		//ddmmyy
					{
						first = objdate.substr(0, 2);
						second = objdate.substr(2, 2);
						last = objdate.substr(4, 4);
						if (last <= 80) {
							yyyy = 20 + last;
						}
						if (last > 80) {
							yyyy = 19 + last;
						}
						if (first > 31 || first <= '00') {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");	
							obj.value = '';
							//obj.focus();
						}
						else if (second > 12 || second <= '00') {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");
							obj.value = '';
							//obj.focus();
						}
						else if (first <= 31 && second <= 12) {
							obj.value = first + "-" + second + "-" + yyyy;
						}
					}
					else {
						Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date Format");	
						obj.value = '';
						//obj.focus();
					}
				}
				else if (datelen == 7)		//dd-m-yy,d-mm-yy,dd-mm-y
				{
					if (index == 2 || index1 == 2 || index2 == 2)	//dd-m-yy
					{
						first = objdate.substr(0, 2);
						if (pos12 == 4) {
							second = objdate.substr(3, 1);
							mm = 0 + second;
							last = objdate.substr(5, 8);
							if (last <= 80) {
								yyyy = 20 + last;
							}
							if (last > 80) {
								yyyy = 19 + last;
							}
							if (first > 31 || first <= '00') {
								Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
								//alert("Invalid Date");	
								obj.value = '';
								//obj.focus();
							}
							else {
								obj.value = first + "-" + mm + "-" + yyyy;
							}
						}
						else if (pos12 == 5) {
							second = objdate.substr(3, 2);
							mm = second;
							last = objdate.substr(6, 6);
							yyyy = 200 + last;
							if (mm > 12 || mm <= '00') {
								Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
								//alert("Invalid Date");	
								obj.value = '';
								//obj.focus();
							}
							else {
								obj.value = first + "-" + mm + "-" + yyyy;
							}
						}
						else {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");
							obj.value = '';
							//obj.focus();
						}
					}
					else if (index == 1 || index1 == 1 || index2 == 1) {
						first = objdate.substr(0, 1);
						dd = 0 + first;
						second = objdate.substr(2, 2);
						last = objdate.substr(5, 8);
						if (last <= 80) {
							yyyy = 20 + last;
						}
						if (last > 80) {
							yyyy = 19 + last;
						}
						if (second > 12 || second <= '00') {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");
							obj.value = '';
							//obj.focus();
						}
						else {
							obj.value = dd + "-" + second + "-" + yyyy;
						}
					}
					else {
						Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date Format");	
						obj.value = '';
						//obj.focus();
					}
				}
				else if (datelen == 8)		//d-m-yyyy,ddmmyyyy,dd-mm-yy
				{
					if (index >= 1 || index1 >= 1 || index2 >= 1) {
						if (index == 1 || index1 == 1 || index2 == 1)		//d-m-yyyy
						{
							first = objdate.substr(0, 1);
							dd = 0 + first;
							second = objdate.substr(2, 1);
							mm = 0 + second;
							last = objdate.substr(4, 8);
							yyyy = last;
							obj.value = dd + "-" + mm + "-" + yyyy;
						}
						if (index == 2 || index1 == 2 || index2 == 2)		//dd-mm-yy
						{
							first = objdate.substr(0, 2);
							dd = first;
							second = objdate.substr(3, 2);
							mm = second;
							last = objdate.substr(6, 8);
							if (last <= 80) {
								yyyy = 20 + last;
							}
							if (last > 80) {
								yyyy = 19 + last;
							}
							if (dd > 31 || dd <= '00') {
								Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
								//alert("Invalid Date");
								obj.value = '';
								//obj.focus();
							}
							else if (mm > 12 || mm <= '00') {
								Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
								//alert("Invalid Date");
								obj.value = '';
								//obj.focus();
							}
							else if (dd <= 31 && mm <= 12) {
								obj.value = dd + "-" + mm + "-" + yyyy;
							}
						}
						else {
							Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date Format");	
							obj.value = '';
							//obj.focus();
						}
					}
					else if (index == -1 && index == -1 && index2 == -1)	//ddmmyyyy
					{
						first = objdate.substr(0, 2);
						second = objdate.substr(2, 2);
						last = objdate.substr(4, 8);
						yyyy = last;
						if (first > 31 || first <= '00') {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");
							obj.value = '';
							//obj.focus();
						}
						else if (second > 12 || second <= '00') {
							Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
							//alert("Invalid Date");
							obj.value = '';
							//obj.focus();
						}
						else if (first <= 31 && second <= 12) {
							obj.value = first + "-" + second + "-" + yyyy;
						}
					}
					else {
						Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date Format");
						obj.value = '';
						//obj.focus();
					}
				}
				else if (datelen == 9)			//dd-m-yyyy
				{
					first = objdate.substr(0, 2);
					second = objdate.substr(3, 1);
					mm = 0 + second;
					last = objdate.substr(5, 9);
					yyyy = last;
					if (first > 31 || first <= '00') {
						Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date");
						obj.value = '';
						//obj.focus();
					}
					else {
						obj.value = first + "-" + mm + "-" + yyyy;
					}
				}
				else if (datelen == 10)		//dd-mm-yyyy
				{
					first = objdate.substr(0, 2);
					dd = first;
					second = objdate.substr(3, 2);
					mm = second;
					last = objdate.substr(6, 10);
					yyyy = last;
					if (dd > 31 || dd <= '00') {
						Swal.fire({ text: 'Invalid Date !!! Please Enter a valid Day ... ' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date !!! Please Enter a valid Day ...");	
						obj.value = '';
						//obj.focus();
					}
					else if (mm > 12 || mm <= '00') {
						Swal.fire({ text: 'Invalid Date !!! Please Enter a valid Month ... ' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date !!! Please enter a valid Month...");
						obj.value = '';
						//obj.focus();
					}
					else if (dd <= 31 && mm <= 12) {
						obj.value = dd + "-" + mm + "-" + yyyy;
					}
					else {
						Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
						//alert("Invalid Date Format");
						obj.value = '';
						//obj.focus();
					}
				}
				else {
					Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
					//alert("Invalid Date Format");	
					obj.value = '';
					//obj.focus();
				}
			}
			else {
				Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => { obj.focus() }, 500) });
				//alert("Invalid Date Format");
				obj.value = '';
				//obj.focus();
			}
		}
	}
}

function isDate(dtStr, obj1) {
	if (dtStr != '' && dtStr != '00-00-0000') {
		var minYear = 1900;
		var maxYear = 2100;
		var dtCh = "-";
		var pos1 = dtStr.indexOf(dtCh);
		var pos2 = dtStr.indexOf(dtCh, pos1 + 1);
		var strDay = dtStr.substring(0, pos1);
		var strMonth = dtStr.substring(pos1 + 1, pos2);
		var strYear = dtStr.substring(pos2 + 1);
		var intday = (strDay * 1);
		var intmonth = (strMonth * 1);
		var intyear = (strYear * 1)

		if (strYear.length != 4 || intyear == 0 || intyear < minYear || intyear > maxYear) {
			Swal.fire({ text: 'Please Enter a valid 4 digit Year ...' }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert("Please Enter a valid 4 digit Year ........ " ) ;
			obj1.value = "";
			//obj1.focus();

			return false;
		}

		if (strMonth.length != 2 || intmonth < 1 || intmonth > 12) {
			Swal.fire({ text: 'Please Enter a valid Month ...' }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert("Please Enter a valid Month ........ " ) ;
			obj1.value = "";
			//obj1.focus();
			return false;
		}

		if (strDay.length != 2 || intday < 1 || intday > DaysInMonth(intyear, intmonth)) {
			Swal.fire({ text: 'Please Enter a valid Day ...' }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert("Please Enter a valid Day ........ " ) ;
			obj1.value = "";
			//obj1.focus();
			return false;
		}
	}
	return true;
}

function DaysInMonth(yyno, mthno) {
	if (mthno == 4 || mthno == 6 || mthno == 9 || mthno == 11) { return 30; }
	if (mthno == 1 || mthno == 3 || mthno == 5 || mthno == 7 || mthno == 8 || mthno == 10 || mthno == 12) { return 31; }
	if (mthno == 2) { return (((yyno % 4 == 0) && ((!(yyno % 100 == 0)) || (yyno % 400 == 0))) ? 29 : 28); }
}

function dateValid(obj1, obj2, param, lb1, lb2) {
	if (!isDate(obj1.value, obj1)) {
		return false;
	}
	var dt1 = parseFloat((obj1.value).substr(6, 4) + (obj1.value).substr(3, 2) + (obj1.value).substr(0, 2));
	var dt2 = parseFloat((obj2.value).substr(6, 4) + (obj2.value).substr(3, 2) + (obj2.value).substr(0, 2));
	if (param == 'L') {
		console.log(dt1, dt2);
		if (dt1 > dt2) {
			Swal.fire({ text: lb1 + " can not be greater than " + lb2 + " ..." }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert(lb1+" can not be greater than "+lb2+" ...");
			//document.getElementById("activity_date"+test).value="";
			obj1.value = "";
			//obj1.focus();
			chk_flag == 0;
			return false;
		} else { chk_flag = 1; }
	}
	else if (param == 'G') {
		if (dt1 < dt2) {
			Swal.fire({ text: lb1 + " can not be less than " + lb2 + " ..." }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert(lb1+" can not be less than "+lb2+" ...");
			obj1.value = "";
			//obj1.focus();
			return false;
		}
	}
	else if (param == 'E') {
		if (dt1 == dt2) {
			Swal.fire({ text: lb1 + " can not be equal to " + lb2 + " ..." }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert(lb1+" can not be equal to "+lb2+" ...");
			obj1.value = "";
			//obj1.focus();
			return false;
		}
	}
	else if (param == 'GE') {
		if (dt1 >= dt2) {
			Swal.fire({ text: lb1 + " can not be less than equal to " + lb2 + " ..." }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert(lb1+" can not be less than equal to "+lb2+" ...");
			obj1.value = "";
			//obj1.focus();
			return false;
		}
	}
	else if (param == 'LE') {
		if (dt1 <= dt2) {
			Swal.fire({ text: lb1 + " can not be greater than equal to " + lb2 + " ..." }).then((result) => { setTimeout(() => { obj1.focus() }, 500) });
			//alert(lb1+" can not be greater than equal to "+lb2+" ...");
			obj1.value = "";
			//obj1.focus();
			return false;
		}
	}
	return true;
}

function chkActivity(fld) {
	if (fld.value != "") {
		let obj = document.getElementById("end_date");
		make_date(fld);
		if (obj != null) {
			if (obj.value != '') {
				dateValid(fld, document.getElementById("end_date"), 'L', "Activity Date", " Bill period...");
				// dateValid(fld,document.f1.start_date,'G',"Activity Date"," Bill period...");
			}
		} else {
			dateValid(fld, document.getElementById("cur_date"), 'L', "Bill Date", "Current Date");
			//document.getElementById("billDateUpto").value = document.getElementById("cur_date").value;
		}
	}
}

function chkActivity2(fld, test) {
	if (fld.value != "") {
		make_date(fld);
		if (document.getElementById("end_date").value != '') {
			dateValid(fld, document.getElementById("end_date"), 'L', "Activity Date", " Bill period...");
			// dateValid(fld,document.f1.start_date,'G',"Activity Date"," Bill period...");
		}
	}
}

function setFormat(obj, dec) {
	if (obj.value != '' && obj.value != 0.00) { format_number(obj, dec); } else { obj.value = 0.00; }
}

function getBillInfo(e) {
	if (e.value != '') {
		fetch(`${baseURL}/api/billDetails/${e.value}/notFound`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				document.getElementById("billNo").value = data.bill_no;
				document.getElementById("billAmount").value = data.bill_amount;
				document.getElementById("matterCode1").value = data.matter_code;
				document.getElementById("matterDesc1").value = data.mat_desc;
				document.getElementById("clientCode1").value = data.client_code;
				document.getElementById("clientName1").value = data.client_name;
				document.getElementById("referenceDesc").value = data.reference_desc;
				document.getElementById("subjectDesc").value = data.subject_desc;
				document.getElementById("otherCaseDesc").value = data.other_case_desc;
				document.getElementById("courtFeeBillInd").value = data.court_fee_bill_ind;
			});
	} else {
		e.value = '';
		//document.getElementById(elements[index]).value = '';
		Swal.fire({
			icon: 'info',
			html: '<strong> No Record Found !! </strong>'
		})
	}
}

function getMatterInfo(e) {
	document.getElementById("billDateUpto").value = document.getElementById("cur_date").value;
	let matter_data = document.getElementById("div_other_cases");
	let num = 0;
	if (e.value != '') {
		fetch(`${baseURL}/api/matterInfo/${e.value}/notFound`)
			.then((response) => response.json())
			.then((data) => {
				if (data.hasOwnProperty('oth_qry')) {
					document.getElementById("otherCaseCount").value = data.oth_case_cnt;
					let html = '';
					for (let matter of data.oth_qry) {
						num++;
						console.log(matter);
						html += `
							<div id="div_other_cases" class="d-block float-start w-100">
								<input type="checkbox" class="d-block float-start me-2 cbxInpt" name="oth_case_row${num}"/>
								<input type="text" class="form-control d-block float-start w-94" name="oth_case_no_subj_desc${num}" value="${matter.case_no_subject_desc}" readonly/>
								<input type="hidden" name="oth_row_no${num}" value="${matter.row_no}" readonly/>
								<input type="hidden" name="oth_case_no${num}" value="${matter.case_no}" readonly/>
								<input type="hidden" name="oth_subj_desc${num}" value="${matter.subject_desc}" readonly/>
							</div>
						`;
					}
					matter_data.innerHTML = html;
				}
			});
	}
}

function selectAll_from() {
	var rowNo = document.billCopying2.row_counter1.value;
	for (var i = 1; i <= rowNo; i++) {
		document.getElementById("copy_ind" + i).checked = true;
	}
}

function deSelectAll_from() {
	var rowNo = document.billCopying2.row_counter1.value;
	for (var i = 1; i <= rowNo; i++) {
		//if(document.f1.grant_bill[0].checked == true;
		document.getElementById("copy_ind" + i).checked = false;
	}
}

function selectAll_to() {
	var rowNo = document.billCopying3.row_counter2.value;
	for (var i = 1; i <= rowNo; i++) {
		document.getElementById("new_copy_ind_i" + i).checked = true;
	}
}

function deSelectAll_to() {
	var rowNo = document.billCopying3.row_counter2.value;
	for (var i = 1; i <= rowNo; i++) {
		document.getElementById("new_copy_ind_i" + i).checked = false;
	}
}

function case_type_no_year(fld, user_option) {
	document.all.case_type_value.value = document.all.case_type_code[document.all.case_type_code.selectedIndex].text;
	var f1 = document.getElementById("caseTypeCode").value
	var f11 = document.getElementById("caseTypeValue").value
	var f2 = document.getElementById("caseNo").value
	var f3 = document.getElementById("caseYear").value
	var flag = 'Y';
	if (flag == "Y" && user_option != "View") {
		if (f3 != '') {
			document.getElementById("matterDesc1").value = f11 + " " + f2 + " OF " + f3;
			document.getElementById("caseYear").value = f3;
			document.getElementById("matterDesc1").focus();

		}
		else {

			document.getElementById("matterDesc1").value = f11 + " " + f2;
		}
	}
}
function proceedOn() {
	var matterCode = document.getElementById("matterCode").value;
	var action = document.getElementById("pageMode").value;
	document.getElementById("Proceed").href = "/sinhaco/master/matter-masteraddedit/" + action + "?code_code=" + matterCode;

}
function buttonMode(buttonValue) {
	if (buttonValue != 'Add' && buttonValue != 'Reset') {
		document.getElementById("matterBinocular").classList.remove('d-none');
		document.getElementById("matterBinocular").classList.add('d-block');
		document.getElementById("matterCode").removeAttribute('readonly');
		document.getElementById("Add").setAttribute('disabled', '');
		$("#pageMode").val(buttonValue);
		//document.getElementById("matterBinocular").setAttribute("href", "/master/client-master-list?pageMode="+ buttonValue+""+hyug.php");
	}
	if (buttonValue == 'Reset') {
		document.getElementById("matterBinocular").classList.add('d-none');
		document.getElementById("matterCode").setAttribute('readonly', 'true');
		//document.getElementById("Add").setAttribute('enabled', 'true');
		document.getElementById("Add").removeAttribute('disabled', '');
		document.getElementById("pageMode").value = '';
	}
}

function changeTab() {
	var initial_name = document.getElementById("initialName").value;
	var file_locn_code = document.getElementById("fileLocnCode").value;
	var court_code = document.getElementById("courtCode").value;
	var matter_desc2 = document.getElementById("matterDesc2").value;
	var client_name = document.getElementById("clientName").value;
	var client_group_name = document.getElementById("clientGroupName").value;
	var appearing_for_code = document.getElementById("appearingForCode").value;
	var reference_type_code = document.getElementById("referenceTypeCode").value;
	var address_line_1 = document.getElementById("addressLine_1").value;
	var attention_name = document.getElementById("attentionName").value;



	if (initial_name != '' && file_locn_code != '' && court_code != '' && matter_desc2 != '' && client_name != '' && client_group_name != '' && appearing_for_code != '' && reference_type_code != '' && address_line_1 != '' && attention_name != '') {
		document.getElementById("tabChange").options[1].disabled = false;
		document.getElementById("tabChange").options[2].disabled = false;
		document.getElementById("tabChange").options[3].disabled = false;
		document.getElementById("tabChange").options[4].disabled = false;
		document.getElementById("tabChange").options[5].disabled = false;
		document.getElementById("tabChange").options[6].disabled = false;
		document.getElementById("tabChange").options[7].disabled = false;
		document.getElementById("tabChange").options[8].disabled = false;
		document.getElementById("tabChange").options[9].disabled = false;
		document.getElementById("tabChange").options[10].disabled = false;
		document.getElementById("tabChange").options[11].disabled = false;
		document.getElementById("tabChange").options[12].disabled = false;
		document.getElementById("tabChange").options[13].disabled = false;
		document.getElementById("tabChange").options[14].disabled = false;
		document.getElementById("tabChange").options[15].disabled = false;
		document.getElementById("tabChange").options[16].disabled = false;
		document.getElementById("tabChange").options[17].disabled = false;
		document.getElementById("tabChange").options[18].disabled = false;



	}
}
function advisor_otherside_delRow(n) {
	var advisor_other_side_ok_ind = document.getElementById("advisor_other_side_ok_ind" + n).value;
	if (advisor_other_side_ok_ind == 'Y') {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "N";
	}
	else {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "Y";
	}
}
function otherside_delRow(n) {
	var other_side_ok_ind = document.getElementById("other_side_ok_ind" + n).value;
	if (other_side_ok_ind == 'Y') {
		document.getElementById("other_side_ok_ind" + n).value = "N";
		document.getElementById("other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("other_side_ok_ind" + n).value = "Y";
		document.getElementById("other_side_ok_ind" + n).checked = true;
	}
}
function counsel_otherside_delRow(n) {
	var counsel_other_side_ok_ind = document.getElementById("counsel_other_side_ok_ind" + n).value;
	if (counsel_other_side_ok_ind == 'Y') {
		document.getElementById("counsel_other_side_ok_ind" + n).value = "N";
		document.getElementById("counsel_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("counsel_other_side_ok_ind" + n).value = "Y";
		document.getElementById("counsel_other_side_ok_ind" + n).checked = true;
	}
}
function advisor_otherside_delRow(n) {
	var advisor_other_side_ok_ind = document.getElementById("advisor_other_side_ok_ind" + n).value;
	if (advisor_other_side_ok_ind == 'Y') {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "N";
		document.getElementById("advisor_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("advisor_other_side_ok_ind" + n).value = "Y";
		document.getElementById("advisor_other_side_ok_ind" + n).checked = true;
	}
}
function advisor_client_delRow(n) {
	var advisor_client_ok_ind = document.getElementById("advisor_client_ok_ind" + n).value;
	if (advisor_client_ok_ind == 'Y') {
		document.getElementById("advisor_client_ok_ind" + n).value = "N";
		document.getElementById("advisor_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("advisor_client_ok_ind" + n).value = "Y";
		document.getElementById("advisor_client_ok_ind" + n).checked = true;
	}
}
function int_party_otherside_delRow(n) {
	var int_party_other_side_ok_ind = document.getElementById("int_party_other_side_ok_ind" + n).value;
	if (int_party_other_side_ok_ind == 'Y') {
		document.getElementById("int_party_other_side_ok_ind" + n).value = "N";
		document.getElementById("int_party_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("int_party_other_side_ok_ind" + n).value = "Y";
		document.getElementById("int_party_other_side_ok_ind" + n).checked = true;
	}
}
function adv_on_rec_otherside_delRow(n) {
	var adv_on_rec_other_side_ok_ind = document.getElementById("adv_on_rec_other_side_ok_ind" + n).value;
	if (adv_on_rec_other_side_ok_ind == 'Y') {
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).value = "N";
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).value = "Y";
		document.getElementById("adv_on_rec_other_side_ok_ind" + n).checked = true;
	}
}
function adv_on_rec_client_delRow(n) {
	var adv_on_rec_client_ok_ind = document.getElementById("adv_on_rec_client_ok_ind" + n).value;
	if (adv_on_rec_client_ok_ind == 'Y') {
		document.getElementById("adv_on_rec_client_ok_ind" + n).value = "N";
		document.getElementById("adv_on_rec_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("adv_on_rec_client_ok_ind" + n).value = "Y";
		document.getElementById("adv_on_rec_client_ok_ind" + n).checked = true;
	}
}
function int_party_client_delRow(n) {
	var int_party_client_ok_ind = document.getElementById("int_party_client_ok_ind" + n).value;
	if (int_party_client_ok_ind == 'Y') {
		document.getElementById("int_party_client_ok_ind" + n).value = "N";
		document.getElementById("int_party_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("int_party_client_ok_ind" + n).value = "Y";
		document.getElementById("int_party_client_ok_ind" + n).checked = true;
	}
}
function repr_by_client_delRow(n) {
	var repr_by_client_ok_ind = document.getElementById("repr_by_client_ok_ind" + n).value;
	if (repr_by_client_ok_ind == 'Y') {
		document.getElementById("repr_by_client_ok_ind" + n).value = "N";
		document.getElementById("repr_by_client_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("repr_by_client_ok_ind" + n).value = "Y";
		document.getElementById("repr_by_client_ok_ind" + n).checked = true;
	}
}
function related_matter_delRow(n) {
	var related_matter_ok_ind = document.getElementById("related_matter_ok_ind" + n).value;
	if (eval(related_matter_ok_ind == 'Y')) {
		document.getElementById("related_matter_ok_ind" + n).value = "N";
		document.getElementById("related_matter_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("related_matter_ok_ind" + n).value = "Y";
		document.getElementById("related_matter_ok_ind" + n).checked = true;
	}
}
function matter_org_delRow(n) {
	var matter_org_ok_ind = document.getElementById("matter_org_ok_ind" + n).value;
	if (eval(matter_org_ok_ind == 'Y')) {
		document.getElementById("matter_org_ok_ind" + n).value = "N";
		document.getElementById("matter_org_ok_ind" + n).checked = false;
	}
	else {
		document.getElementById("matter_org_ok_ind" + n).value = "Y";
		document.getElementById("matter_org_ok_ind" + n).checked = true;
	}
}

function chkNumber(fld) {

	format_number(fld, 2);
}
function bank_master_check() {
	if (document.getElementById('bank_name').value == '') {
		alert('Please Give A Bank Name');
		document.getElementById('bank_name').focus();
		return false;
	}

	if (document.getElementById('bsr_code').value == '') {
		alert('Please Give A BSR Code');
		document.getElementById('bsr_code').focus();
		return false;
	}
}
function checkdata() {
	if (document.getElementById('branch_name').value == '') {
		alert('Please Enter Branch Name');
		document.getElementById('branch_name').focus();
		return false;
	}
	else if (document.getElementById('branch_abbr_name').value == '') {
		alert('Please Enter Branch Abbr Name ');
		document.getElementById('branch_abbr_name').focus();
		return false;
	}
	else if (document.getElementById('address_line_1').value == '') {
		alert('Please Branch Address');
		document.getElementById('address_line_1').focus();
		return false;
	}
	else if (document.getElementById('state_code').value == '') {
		alert('Please Enter State Code');
		document.getElementById('state_code').focus();
		return false;
	}
	else if (document.getElementById('contact_person').value == '') {
		alert('Please Enter Contact Person');
		document.getElementById('contact_person').focus();
		return false;
	}
	else if (document.getElementById('company_code').value == '') {
		alert('Please Enter Company Code');
		document.getElementById('company_code').focus();
		return false;
	}
	else if (document.getElementById('tds_circle_no').value == '') {
		alert('Please Enter TDS Circle');
		document.getElementById('tds_circle_no').focus();
		return false;
	}
	document.getElementById('submit_btn').submit();
}
function building_master_check() {
	if (document.getElementById('floor').value == '') {
		alert('Please Enter Floor');
		document.getElementById('floor').select();
		document.getElementById('floor').focus();
		return false;
	}
	else if (document.getElementById('address_line_1').value == '') {
		alert('Please Enter Address');
		document.getElementById('address_line_1').select();
		document.getElementById('address_line_1').focus();
		return false;
	}
	else if (document.getElementById('city').value == '') {
		alert('Please Enter City');
		document.getElementById('city').select();
		document.getElementById('city').focus();
		return false;
	}
	document.getElementById('submit_buildingbtn').submit();
}
function goSave() {
	var flag = 0;
	if (document.getElementById('clientName').value == '') {
		flag = 1;
		alert('Please Enter Client Name : ', 1);
		document.getElementById('clientName').focus();
		return false;
	}
	else if (document.getElementById('addressLine_1').value == '') {
		flag = 1;
		alert('Please Enter Address Line');
		document.getElementById('addressLine_1').focus();
		return false;
	}
	else if (document.getElementById('City').value == '') {
		flag = 1;
		alert('Please Enter City');
		document.getElementById('City').focus();
		return false;
	}
	else if (document.getElementById('stateCode').value == '') {
		flag = 1;
		alert('Please Select State');
		document.getElementById('stateCode').focus();
		return false;
	}
	else if (document.getElementById('Country').value == '') {
		flag = 1;
		alert('Please Enter Country');
		document.getElementById('Country').focus();
		return false;
	}
}
function attgoSave() {
	if (document.getElementById('clientCode').value == '') {
		alert('Please Enter Client Name');
		document.getElementById('clientName').focus();
		return false;
	}
	else if (document.getElementById('attentionName').value == '') {
		alert('Please Enter Attention Name');
		document.getElementById('attentionName').focus();
		return false;
	}
	else if (document.getElementById('title').value == '') {
		alert('Please Enter Title');
		document.getElementById('title').focus();
		return false;
	}
}
function check_account(param) {
	if (param.value == 'CB') {
		document.getElementById('bank_account_no').readOnly = true;
		document.getElementById('acDesc').value = '';
		document.getElementById('subAcDesc').value = '';
		document.getElementById('bank_account_no').value = '';
		document.getElementById('overdraft_amount').value = '';
		document.getElementById('bank_account_no').style.backgroundColor = '#EEEEEE';

	}
	else if (param.value == 'BB') {
		document.getElementById('bank_account_no').value = '';
		document.getElementById('acDesc').value = '';
		document.getElementById('subAcDesc').value = '';
		document.getElementById('overdraft_amount').value = '';
		document.getElementById('bank_account_no').style.backgroundColor = '#FFFFFF';
		document.getElementById('overdraft_amount').style.backgroundColor = '#FFFFFF';
		document.getElementById('bank_account_no').readOnly = false;
		document.getElementById('overdraft_amount').readOnly = false;
		document.getElementById('acDesc').setAttribute('required', '');

	}
	else {
		document.getElementById('bank_account_no').value = '';
		document.getElementById('acDesc').value = '';
		document.getElementById('subAcDesc').value = '';
		document.getElementById('overdraft_amount').value = '';
		document.getElementById('bank_account_no').style.backgroundColor = '#EEEEEE';
		document.getElementById('overdraft_amount').style.backgroundColor = '#EEEEEE';
		document.getElementById('bank_account_no').readOnly = true;
		document.getElementById('overdraft_amount').readOnly = true;
	}
}
function chk_and_submit() {

	if (document.getElementById('daybook_desc').value == '') {
		alert('Please Enter Daybook Name');
		document.getElementById('daybook_desc').focus();
		return false;
	}
	else if (document.getElementById('daybook_type').value == '') {
		alert('Please Enter Daybook Type');
		document.getElementById('daybook_type').focus();
		return false;
	}
	else if (document.getElementById('branch_code').value == '') {
		alert('Please Enter Branch');
		document.getElementById('branch_code').focus();
		return false;
	}
	else if (document.getElementById('mainAcCode').value == '') {
		alert('Please Enter Main A/c');
		document.getElementById('main_ac_hlp').focus();
		return false;
	}
	else if (document.getElementById('daybook_type').value == 'B' && document.getElementById('bank_account_no').value == '') {
		alert('Please EnterBank A/c No');
		document.getElementById('bank_account_no').focus();
		return false;
	}
}

function data_check() {
	if (document.getElementById('department_code').value == '') {
		alert('Please Enter Department Code');
		document.getElementById('department_code').focus();
		return false;
	}
	else if (document.getElementById('department_name').value == '') {
		alert('Please Enter Department Name');
		document.getElementById('department_name').focus();
		return false;
	}
}
function data_check_designatiokn() {
	if (document.getElementById('designation_code').value == '') {
		alert('Please Enter Designation Code');
		document.getElementById('designation_code').focus();
		return false;
	}
	else if (document.getElementById('designation_name').value == '') {
		alert('Please Enter Designation Name');
		document.getElementById('designation_name').focus();
		return false;
	}
}
function validation2() {
	if (document.getElementById('employee_name').value == '') {
		alert("Please Ensert Employee Name");
		document.getElementById('employee_name').focus();
		return false;
	}
	else if (document.getElementById('employee_initial').value == '') {
		alert("Please Enter Employee initial");
		document.f1.employee_initial.focus();
		return false;
	}
	else if (document.getElementById('branch_name').value == '') {
		alert("Please Enter Branch Name ", 1);
		document.getElementById('branch_name').focus();
		return false;
	}
}
function data_check_initial() {
	if (document.getElementById('initial_code').value == '') {
		alert('Please Enter Initial Code');
		document.getElementById('initial_code').focus();
		return false;
	}
	else if (document.getElementById('initial_name').value == '') {
		alert('Please Enter Initial Name');
		document.getElementById('initial_name').focus();
		return false;
	}
	else if (document.getElementById('address_line_1').value == '') {
		alert('Please Enter Address');
		document.getElementById('address_line_1').focus();
		return false;
	}
}
function checkdataCode() {
	if (document.getElementById('code_code').value == '') {
		alert("Please Enter Code  .....");
		document.getElementById('code_code').focus();
		return false;
	}
	else if (document.getElementById('code_desc').value == '') {
		alert("Please Enter Code Description .....");
		document.getElementById('code_desc').focus();
		return false;
	}
}
function courier_master_check() {

	if (document.getElementById('supplierCode').value == '') {
		alert('Please Select Agency Name');
		document.getElementById('supplierName').focus();
		return false;
	}


	if (document.getElementById('rate_desc').value == '') {
		alert('Please Enter Particulars');
		document.getElementById('rate_desc').focus();
		return false;
	}

	if (document.getElementById('rate').value == '') {
		alert('Please Enter Rate');
		document.getElementById('rate').focus();
		return false;
	}

	if (document.getElementById('rate').value <= 0) {
		alert('Invalid Rare Input');
		document.getElementById('rate').value = '';
		document.getElementById('rate').focus();
		return false;
	}
}
function photocopy_master_check() {
	var rt = document.getElementById('rate').value * 1;
	if (document.getElementById('supplierCode').value == '') {
		alert('Please Select Supplier Name');
		document.getElementById('supplierName').focus();
		return false;
	}

	if (document.getElementById('page_size').value == '') {
		alert('Please Enter Page Size');
		document.getElementById('page_size').focus();
		return false;
	}

	if (document.getElementById('rate').value == '') {
		alert('Please Enter Rate');
		document.getElementById('rate').focus();
		return false;
	}

	if (rt <= 0) {
		alert('Invalid Rate Input', 34);
		document.getElementById('rate').focus();
		return false;
	}
}
function billing_master_check() {
	var coun_code = document.getElementById('counselCode').value;
	if (coun_code == '') {
		alert('Please Select Counsel');
		document.getElementById('counselName').focus();
		return false;
	}

	var clnt_code = document.getElementById('clientCode').value;
	if (clnt_code == '') {
		alert('Please Select Client');
		document.getElementById('clientName').focus();
		return false;
	}

	var mat_code = document.getElementById('matterCode').value;
	if (mat_code == '') {
		alert('Please Select Matter');
		document.getElementById('matterName').focus();
		return false;
	}

	var act_code = document.getElementById('activityCode').value;
	if (act_code == '') {
		alert('Please Select Activity');
		document.getElementById('actName').focus();
		return false;
	}

	var rt = document.getElementById('rate').value;
	if (rt == '') {
		alert('Please Enter Rate');
		document.getElementById('rate').focus();
		return false;
	}

	if (rt <= 0) {
		alert('Invalid Rate Input');
		document.getElementById('rate').focus();
		return false;
	}
}
function supp_check() {

	if (document.getElementById('supplier_name').value == '') {
		alert('Please Enter Supplier Name');
		document.getElementById('supplier_name').focus();
		return false;
	}
	if (document.getElementById('address_line_1').value == '') {
		alert('Please Enter Address Line 1 : ', 1);
		document.getElementById('address_line_1').focus();
		return false;
	}
	if (document.getElementById('city').value == '') {
		alertessage('Please Enter City');
		document.getElementById('city').focus();
		return false;
	}
	if (document.getElementById('state_code').value == '') {
		alert('Please Select State');
		document.getElementById('state_code').focus();
		return false;
	}
	if (document.getElementById('state_code').value == '' && document.getElementById('country').value == '') {
		alert('Please Enter Country');
		document.getElementById('country').focus();
		return false;
	}
	else if (document.getElementById('default_comm_mode').value == 'E' && document.getElementById('email_id').value == '') {
		alert('Please Enter E-Mail ID');
		document.getElementById('email_id').focus();
		return false;
	}
	else if (document.getElementById('default_comm_mode').value == 'F' && document.getElementById('fax_no').value == '') {
		alert('Please Enter Fax No');
		document.getElementById('fax_no').focus();
		return false;
	}
	else if (document.getElementById('default_comm_mode').value == 'T' && (document.getElementById('mobile_no').value == '' && document.getElementById('phone_no').value == '')) {
		alert('Please Enter Phone No');
		document.getElementById('phone_no').focus();
		return false;

	}
	if (document.getElementById('status_code').value == '') {
		alert('Please Enter Status');
		document.getElementById('status_code').focus();
		return false;
	}
}
function tax_master_validataion() {
	if (document.getElementById('tax_name').value == '') {
		alert("Please Enter Tax name");
		document.getElementById('tax_name').focus();
		return false;
	}
	if (document.getElementById('taxAccountCode').value == '') {
		alert("Please Enter Tax Account Code");
		document.getElementById('taxAccountDesc').focus();
		return false;
	}
	if (document.getElementById('subAcCode').value == '') {
		alert("Please Enter Tax sub account code");
		document.getElementById('subAcDesc').focus();
		return false;
	}
}
function expense_master_check() {
	if (document.getElementById('expense_code').value == '') {
		alert('Please Enter Expense Code');
		document.getElementById('expense_code').focus();
		return false;
	}

	if (document.getElementById('expense_description').value == '') {
		alert('Please Enter Expense Description');
		document.getElementById('expense_description').focus();
		return false;
	}
}

function myFinalBillSerial(e) {

	if (e.value != '') {
		let bill_year = document.getElementById("billYear").value;
		fetch(`${baseURL}/api/FinalBillSerial/${e.value}/${bill_year}`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				let realised_amount = data.realised_amount;
				let num_row = data.num_row;
				if (realised_amount > 0) {
					Swal.fire({
						icon: 'info',
						html: '<strong> Bill has already been realised </strong>'
					})
				} else if (num_row == null) {
					e.value = '';
					document.getElementById('billYear').value = '';
					Swal.fire({
						icon: 'info',
						html: '<strong> Bill No not found ...... </strong>'
					})
				} else {
					document.getElementById("refBillSerialNo").value = data.ref_bill_serial_no;
					document.getElementById("serialNo").value = data.serial_no;
					document.getElementById("matterCode").value = data.matter_code;
					document.getElementById("matterDesc").value = data.matter_desc;
					document.getElementById("clientCode").value = data.client_code;
					document.getElementById("clientName").value = data.client_name;
					document.getElementById("statusCode").value = data.status_code;
				}
			});
	} else {
		e.value = '';
		document.getElementById('billYear').value = '';
		Swal.fire({
			icon: 'info',
			html: '<strong> No Record Found !! </strong>'
		})
	}
}

function billCopy(e) {
	e.preventDefault();
	if (e.srcElement.name == 'billCopying2') {
		let matterCode = document.billCopying2.matter_code.value;
		let billDateUpto = document.billCopying2.bill_date_upto.value;
		billCopyListing(`/nextproceed?matter_code=${matterCode}&bill_date_upto=${billDateUpto}`, 'billCopying2');
	} else {
		Swal.fire({
			title: 'Are you sure?',
			text: "Want to make a copy of this Bill !!",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#055570',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Sure'
		}).then((result) => {
			if (result.isConfirmed) {
				let serialNo = document.billCopying.serial_no.value;
				billCopyListing(`/proceed?serial_no=${serialNo}`, 'billCopying1');
			}
		})
	}
}

function billCopyListing(action = '', billNo = '') {
	let tr = '', key = 1;
	fetch(`${baseURL}/billing/copying${action}`)
		.then((response) => response.json())
		.then((response) => {

			if (billNo == 'billCopying2') {
				if (document.getElementById('matterCode').value != '') {
					document.getElementById('billCopying3').classList.remove("d-none");
					document.getElementById('BilCpyprcdBtn2').style.display = "none";
					document.getElementById('backbtn1').style.display = "none";
					document.billCopying3.row_counter2.value = response.count;
					document.getElementById("slctAll2").checked = (response.count > 0) ? true : false;

					for (let record of response.records) {
						tr += `
    						<tr>
    							<td id="Ctd${key}" class="d-none">
    								<input type="hidden" name="ok_ind${key}" value="" readonly />
    								<input type="hidden" name="srl_no${key}" value="${record.serial_no}" readonly />
    							</td>
    							<td> <input type="text" class="form-control" name="date${key}" value="${record.date}" readonly /> </td>
    							<td class="brkwrd"> <textarea name="details${key}" class="form-control"  readonly>${(record.details) ? htmlToText(record.details) : ''}</textarea> </td>
    							<td> <input type="text" class="form-control" name="amount${key}" value="${record.amount}" readonly /> </td>
    							<td class="w-2 text-center" style="width:2%;"> <input type="checkbox" name="new_copy_ind_i${key}" id="new_copy_ind_i${key}" checked /> </td>
    						</tr>
    					`; key++;
					} document.getElementById('2ndThead').innerHTML = tr;

					$('.myform').children().unwrap().wrapAll("<form class='frmmm' action='' method='post'></form>");
					$('.frmDv1').unwrap();
					document.getElementById('submitBtn').classList.remove("d-none");
				} else {
					Swal.fire({ text: 'Please Matter Code !!' }).then((result) => { setTimeout(() => { document.getElementById('matterCode').focus() }, 500) });
				}
			} else {
				document.getElementById('billCopying2').classList.remove("d-none");
				document.getElementById('BilCpyprcdBtn').style.display = "none";
				document.getElementById('resetBtn1').style.display = "none";
				document.getElementById("cur_date").value = response.cur_date;
				document.billCopying2.row_counter1.value = response.count;
				document.getElementById("slctAll").checked = (response.count > 0) ? true : false;

				for (let record of response.records) {
					tr += `
						<tr>
							<td id="Ctd${key}" class="d-none">
								<input type="hidden" name="inp_ok_ind${key}" value="" readonly />
								<input type="hidden" name="source_code${key}" value="${record.source_code}" />
								<input type="hidden" name="activity_type${key}" value="${record.activity_type}" />
								<input type="hidden" name="printer_ind${key}" value="${record.printer_ind}" />
								<input type="hidden" name="prn_seq_no${key}" value="${record.prn_seq_no}" />
							</td>
							<td><input type="text" class="form-control" name="activity_date${key}" value="${dateFormat(record.activity_date, 'dd-MM-yyyy')}" readonly /></td>
							<td><input type="text" class="form-control" name="counsel_code${key}" value="${record.counsel_code}" readonly /></td>
							<td><textarea class="form-control" name="activity_desc${key}" readonly> ${(record.activity_desc) ? htmlToText(record.activity_desc) : ''} </textarea></td>
							<td class="text-center w-7"><input type="text" class="form-control text-center" name="io_ind${key}" value="${record.io_ind}" readonly /></td>
							<td><input type="text" class="form-control" name="billed_amount${key}" value="${(record.billed_amount == null) ? 0.00 : record.billed_amount}" readonly /></td>
							<td class="text-center"><input type="checkbox" class="cbxInpt" name="copy_ind${key}" id="copy_ind${key}" value="Y" checked /></td>
						</tr>
					`; key++;
				} document.getElementById('1stThead').innerHTML = tr;
			}
		});
}

function htmlToText(html) {
	var div = document.createElement("div");
	div.innerHTML = html;
	return div.textContent;
}

function chkEntryDate(fld) {
	if (fld.value != "") {
		make_date(fld);
		dateValid(fld, document.getElementById("cur_date"), 'L', "Entry Date", "Current Date");
	}
}

function chkBriefDate(fld, n) {
	// if(document.f1.user_option.value == 'Select')
	// {
	// 	if(fld.value == "") { alert('Brief Date : ',1); fld.focus(); return false; }
	// 	//addRow(n);
	// }
	if (fld.value != "") {
		make_date(fld);
		dateValid(fld, document.getElementById("cur_date"), 'L', "Brief Date", "Current Date")
	}
}

function chkMemoDate(fld) {
	if (fld.value != "") {
		make_date(fld);
		dateValid(fld, document.getElementById("cur_date"), 'L', "Memo Date", "Current Date");
	}
}

function chkInstrumentDate(fld) {
	if (fld.value != "") {
		make_date(fld);
		dateValid(fld, document.getElementById("cur_date"), 'L', "Instrument Date", "Current Date");
	}
}

function validateNumber(item, str, decimal) {
	if (isNaN(item.value) == true) {
		Swal.fire({ text: str + ' should be a valid Number ...' }).then((result) => { setTimeout(() => { item.focus() }, 500) });
		//alert(str+' should be a valid Number ........');
		item.select();
		//item.focus();
		return false;
	} else {
		if (item.value <= 0) {
			Swal.fire({ text: str + ' should be a Positive ...' }).then((result) => { setTimeout(() => { item.focus() }, 500) });
			//alert(str+' should be a Positive .........');
			item.value = '';
			//item.focus();
			return false;
		}
	}
	format_number(item, decimal);
	return true;

}

function chkCounselFeeRecd(fld, n) {
	if (fld.value != "") { validateNumber(fld, "Counsel Fee Receive : ", 2); }
	calc_total_counsel_recd();
}

function chkClerkFeeRecd(fld, n) {
	if (fld.value != "") { validateNumber(fld, "Clerk Fee Receive : ", 2); }
	calc_total_clerk_recd();
}

function chkPeonFeeRecd(fld, n) {
	if (fld.value != "") { validateNumber(fld, "Peon Fee Receive : ", 2); }
	calc_total_peon_recd();
}

function calc_total_counsel() {
	var total_row = document.f1.row_counter.value;
	var counsel_total = 0;
	for (i = 1; i <= total_row; i++) {
		voucher_ok_ind = eval("document.f1.voucher_ok_ind" + i + ".value");
		//console.log(voucher_ok_ind);
		if (voucher_ok_ind == 'Y') {
			counsel_total = counsel_total + eval("document.f1.counsel_fee" + i + ".value") * 1;
		}
	}
	counsel_total = parseFloat(counsel_total).toFixed(2);
	document.f1.counsel_fee.value = counsel_total;
}

function calc_total_clerk() {
	var total_row = (document.f1.row_counter.value) * 1;
	var clerk_total = 0;
	for (i = 1; i <= total_row; i++) {
		voucher_ok_ind = eval("document.f1.voucher_ok_ind" + i + ".value");
		if (voucher_ok_ind == 'Y') {
			clerk_total = clerk_total + eval("document.f1.clerk_fee" + i + ".value") * 1;
		}
	}
	clerk_total = parseFloat(clerk_total).toFixed(2);
	document.f1.clerk_fee.value = clerk_total;
}

function calc_total_peon() {
	var total_row = (document.f1.row_counter.value) * 1;
	var peon_total = 0;
	for (i = 1; i <= total_row; i++) {
		voucher_ok_ind = eval("document.f1.voucher_ok_ind" + i + ".value");
		if (voucher_ok_ind == 'Y') {
			peon_total = peon_total + eval("document.f1.peon_fee" + i + ".value") * 1;
		}
	}
	peon_total = parseFloat(peon_total).toFixed(2);
	document.f1.peon_fee.value = peon_total;
}

function calc_total_counsel_recd() {
	var total_row = (document.f1.row_counter.value) * 1;
	var counsel_total_recd = 0;
	for (i = 1; i <= total_row; i++) {
		voucher_ok_ind = eval("document.f1.voucher_ok_ind" + i + ".value");
		if (voucher_ok_ind == 'Y') {
			counsel_total_recd = counsel_total_recd + eval("document.f1.counsel_fee_recd" + i + ".value") * 1;
		}
	}
	counsel_total_recd = parseFloat(counsel_total_recd).toFixed(2);
	document.f1.counsel_fee_recd.value = counsel_total_recd;
}

function calc_total_clerk_recd() {
	var total_row = (document.f1.row_counter.value) * 1;
	var clerk_total_recd = 0;
	for (i = 1; i <= total_row; i++) {
		voucher_ok_ind = eval("document.f1.voucher_ok_ind" + i + ".value");
		if (voucher_ok_ind == 'Y') {
			clerk_total_recd = clerk_total_recd + eval("document.f1.clerk_fee_recd" + i + ".value") * 1;
		}
	}
	clerk_total_recd = parseFloat(clerk_total_recd).toFixed(2);
	document.f1.clerk_fee_recd.value = clerk_total_recd;
}

function calc_total_peon_recd() {
	var total_row = (document.f1.row_counter.value) * 1;
	var peon_total_recd = 0;
	for (i = 1; i <= total_row; i++) {
		voucher_ok_ind = eval("document.f1.voucher_ok_ind" + i + ".value");
		if (voucher_ok_ind == 'Y') {
			peon_total_recd = peon_total_recd + eval("document.f1.peon_fee_recd" + i + ".value") * 1;
		}
	}
	peon_total_recd = parseFloat(peon_total_recd).toFixed(2);
	document.f1.peon_fee_recd.value = peon_total_recd;
}

function calc_newtax_amount(rno) {
	var ntaxamt = eval("document.f1.new_tax_amount" + rno + ".value") * 1;
	if (ntaxamt > 0) { format_number(eval("document.f1.new_tax_amount" + rno), 2); }

	calc_newcess_percent(rno);
}

function calc_newcess_percent(rno) {
	var ntaxamt = eval("document.f1.new_tax_amount" + rno + ".value") * 1;
	var ncessper = eval("document.f1.new_tax_cess_percent" + rno + ".value") * 1;

	if (ncessper > 0) {
		var ncessamt = (ntaxamt * ncessper / 100); eval("document.f1.new_tax_cess_amount" + rno + ".value = '" + ncessamt + "'"); format_number(eval("document.f1.new_tax_cess_amount" + rno), 2);
	}
	else {
		var ncessamt = ''; eval("document.f1.new_tax_cess_amount" + rno + ".value = '" + ncessamt + "'");
	}

	//---
	calc_newhecess_percent(rno);
}

function calc_newcess_amount(rno) {
	var ncessamt = eval("document.f1.new_tax_cess_amount" + rno + ".value") * 1;
	if (ncessamt > 0) { format_number(eval("document.f1.new_tax_cess_amount" + rno), 2); }

	calc_newhecess_percent(rno);
}

function calc_newhecess_percent(rno) {
	var ntaxamt = eval("document.f1.new_tax_amount" + rno + ".value") * 1;
	var nhcesper = eval("document.f1.new_tax_hecess_percent" + rno + ".value") * 1;

	if (nhcesper > 0) {
		var nhcesamt = (ntaxamt * nhcesper / 100); eval("document.f1.new_tax_hecess_amount" + rno + ".value = '" + nhcesamt + "'"); format_number(eval("document.f1.new_tax_hecess_amount" + rno), 2);
	}
	else {
		var nhcesamt = ''; eval("document.f1.new_tax_hecess_amount" + rno + ".value = '" + nhcesamt + "'");
	}

	//---
	calc_row_total_tax(rno);
}

//-------------------------------------------------
function calc_newhecess_amount(rno) {
	var nhcessamt = eval("document.f1.new_tax_hecess_amount" + rno + ".value") * 1;
	if (nhcessamt > 0) { format_number(eval("document.f1.new_tax_hecess_amount" + rno), 2); }
	//---
	calc_row_total_tax(rno);
}

function calc_row_total_tax(rno) {
	var ntaxper = eval("document.f1.new_tax_percent" + rno + ".value") * 1;
	var ncessper = eval("document.f1.new_tax_cess_percent" + rno + ".value") * 1;
	var nhcessper = eval("document.f1.new_tax_hecess_percent" + rno + ".value") * 1;
	var nttaxper = ntaxper + ((ntaxper * ncessper / 100).toFixed(3)) * 1 + ((ntaxper * nhcessper / 100).toFixed(3)) * 1;
	if (nttaxper > 0) { eval("document.f1.new_tax_total_percent" + rno + ".value = '" + nttaxper + "'"); format_number(eval("document.f1.new_tax_total_percent" + rno), 3); }

	var ntaxamt = eval("document.f1.new_tax_amount" + rno + ".value") * 1;
	var ncessamt = eval("document.f1.new_tax_cess_amount" + rno + ".value") * 1;
	var nhcessamt = eval("document.f1.new_tax_hecess_amount" + rno + ".value") * 1;
	var nttaxamt = Math.round(ntaxamt + ncessamt + nhcessamt);
	if (nttaxamt > 0) { eval("document.f1.new_tax_total_amount" + rno + ".value = '" + nttaxamt + "'"); format_number(eval("document.f1.new_tax_total_amount" + rno), 2); }

	calc_row_total(rno);
	calc_total_counsel();
	calc_total_clerk();
	calc_total_peon();
}

function calc_row_total(rno) {
	var cnslamt = eval("document.f1.counsel_fee" + rno + ".value") * 1;
	var clrkamt = eval("document.f1.clerk_fee" + rno + ".value") * 1;
	var peonamt = eval("document.f1.peon_fee" + rno + ".value") * 1;

	var nttaxamt = eval("document.f1.new_tax_total_amount" + rno + ".value") * 1;
	var rtotalamt = cnslamt + clrkamt + peonamt + nttaxamt;
	if (rtotalamt > 0) { eval("document.f1.gross_amount" + rno + ".value = '" + rtotalamt + "'"); format_number(eval("document.f1.gross_amount" + rno), 2); }
}

function myqtrno(e) {

	if (e.value != '') {
		let finc_year = document.getElementById("finYear").value;
		let brch_code = document.getElementById("branchCode").value;
		var qtrsdt = '';
		var qtredt = '';
		fetch(`${baseURL}/api/returnNo/${e.value}/${brch_code}/${finc_year}`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				let num_row = data.num_row;
				if (num_row == null) {
					e.value = '';
					Swal.fire({
						icon: 'info',
						html: '<strong> TDS Return No not yet Entered ... Please enter it FIRST ... </strong>'
					})
				} else {
					if (e.value == 1) { qtrsdt = '01-04-' + finc_year.substr(0, 4); qtredt = '30-06-' + finc_year.substr(0, 4); }
					else if (e.value == 2) { qtrsdt = '01-07-' + finc_year.substr(0, 4); qtredt = '30-09-' + finc_year.substr(0, 4); }
					else if (e.value == 3) { qtrsdt = '01-10-' + finc_year.substr(0, 4); qtredt = '31-12-' + finc_year.substr(0, 4); }
					else if (e.value == 4) { qtrsdt = '01-01-' + finc_year.substr(5, 4); qtredt = '31-03-' + finc_year.substr(5, 4); }

					document.getElementById("startDate").value = qtrsdt;
					document.getElementById("endDate").value = qtredt;
					document.getElementById("tdsReturnNo").value = data.tds_return_no;
				}
			});
	} else {
		e.value = '';
		document.getElementById('billYear').value = '';
		Swal.fire({
			icon: 'info',
			html: '<strong> No Record Found !! </strong>'
		})
	}
}

$(".readonly").on('keydown paste focus mousedown', function (e) {
	if (e.keyCode != 9) // ignore tab
		e.preventDefault();
});

function checkBoxValidation() {
	if ($('#listTable .child-checkbox:checked').length > 0) {
		$("#btnLaser").prop("disabled", false);
	} else {
		$("#btnLaser").prop("disabled", true);
	}
} checkBoxValidation();

$(document).ready(function () {
	$("#listTable .child-checkbox").click(function () {
		checkBoxValidation();
	});
});
//------------------
function getParameters() {
	let urlString = document.location.href, params = {};
	let paramString = urlString.split('?')[1];
	let params_arr = paramString.split('&');
	for (let i = 0; i < params_arr.length; i++) {
		let pair = params_arr[i].split('=');
		params[pair[0]] = pair[1];
	} return params;
}

function changeRowPerPage(e) { // added by Surajit Naskar on 26-10-2023
	let params = getParameters();
	let url = `${window.location.origin + window.location.pathname}?display_id=${params.display_id}&menu_id=${params.menu_id}&rows_per_page=${e.value}`;
	location.replace(url);
}
function validatePhoneNumber(phoneNumber) {
	// Define a regular expression pattern for a typical US phone number (10 digits)
	const phonePattern = /^\d{10}$/;
	if (!phonePattern.test(phoneNumber)) {
		Swal.fire({ text: 'Invalid phone number. Please enter a 10-digit number.' }).then((result) => { setTimeout(() => { phoneNumber.value = ''; }, 500) });

		return false;
	}
	return true;
}
//-----07-12-2023 Created By Sylvester---////
function passwordStrength(password) {
	var desc = new Array();
	desc[0] = "Very Weak";
	desc[1] = "Weak";
	desc[2] = "Better";
	desc[3] = "Medium";
	desc[4] = "Strong";
	desc[5] = "Very Strong";
	desc[6] = "Strongest";
	desc[7] = "Strongest";
	desc[8] = "Strongest";
	desc[9] = "Strongest";


	var score = 0;

	if (password.length > 3) score++;

	if (password.length > 5) score++;


	if ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) score++;

	if (password.match(/\d+/)) score++;

	if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) score++;

	if (password.length > 6) score++;

	if (password.length > 8) score++;

	if (password.length > 9) score++;


	document.getElementById("passwordDescription").innerHTML = desc[score];
	document.getElementById("passwordStrength").className = "mb-3 mt-1 rounded h-auto strength" + score;
}
function checkdata2() {
	if (document.getElementById("employee_password").value == '') {
		alertMSG('info', "Please Enter Password");
		document.getElementById("employee_password").focus();
		return false;
	} else if (document.getElementById("employee_new_password_1").value == '') {
		alertMSG('info', "Please Enter New Password");
		document.getElementById("employee_new_password_1").focus();
		return false;
	} else if (document.getElementById("employee_new_password_2").value == '') {
		alertMSG('info', "Please Confirm New Password ");
		document.getElementById("employee_new_password_2").focus();
		return false;
	} else if (document.getElementById("employee_new_password_1").value != document.getElementById("employee_new_password_2").value) {
		alertMSG('info', "New Password and Confirm Password must be the same");
		document.getElementById("employee_new_password_1").value = null;
		document.getElementById("employee_new_password_2").value = null;
		document.getElementById("employee_new_password_1").focus();
		return false;
	} else {
		var frm = document.getElementById("F1");
		frm.submit();
	}
}
function check_password(e) {
	let userId = e.value;
	let pass = document.getElementById("user_id").value;

	if (userId != '') {
		fetch(`/sinhaco/api/check_password/${userId}/${pass}`)
			.then((response) => response.json())
			.then((user) => {
				// alert(user.user_password);
				if (user.passwords_match == false) {
					alertMSG('info', '<strong>Wrong Password</strong>');
					document.getElementById("employee_password").value = '';
				}
				else {
					document.getElementById("employee_new_password_1").removeAttribute('readonly');
					document.getElementById("employee_new_password_2").removeAttribute('readonly');
				}
			});
	}
}
// Alert
function alertMSG(ICON = '', TITLE = '', TEXT = '', HTML = '') {
	Swal.fire({
		position: 'top-center',
		icon: ICON,
		html: HTML,
		title: TITLE,
		text: TEXT,
		showConfirmButton: true
	});
}



function checkMatterMergedata() {
	if (document.getElementById("fin_year").value == '') {
		alertMSG('info', '<strong>Please Enter Financial Year</strong>');
		document.getElementById("fin_year").focus();
		return false;
	}
	else if (document.getElementById("oldMatterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code (From)</strong>');
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else if (document.getElementById("newMatterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code (To)</strong>');
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else if (document.getElementById("oldMatterCode").value == document.getElementById("newMatterCode").value) {
		alertMSG('info', '<strong>Matter Code (From) and Matter Code (To) must be different</strong>');
		document.getElementById("oldMatterCode").value = '';
		document.getElementById("oldMatterDesc").value = '';
		document.getElementById("old_client_code").value = '';
		document.getElementById("oldClientName").value = '';
		document.getElementById("newMatterCode").value = '';
		document.getElementById("newMatterDesc").value = '';
		document.getElementById("newClientCode").value = '';
		document.getElementById("newClientName").value = '';
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else {
		var frm = document.getElementById("matterMerge");
		frm.submit();
	}
}

function checkClientMergedata() {
	if (document.getElementById("fin_year").value == '') {
		alertMSG('info', '<strong>Please Enter Financial Year</strong>');
		document.getElementById("fin_year").focus();
		return false;
	}
	else if (document.getElementById("oldClientCode").value == '') {
		alertMSG('info', '<strong>Please Enter Financial Year</strong>');
		document.getElementById("oldClientCode").focus();
		return false;
	}
	else if (document.getElementById("newClientCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code (To)</strong>');
		document.getElementById("newClientCode").focus();
		return false;
	}
	else if (document.getElementById("oldClientCode").value == document.getElementById("newClientCode").value) {
		alertMSG('info', '<strong>Client Code (From) and Client Code (To) must be different</strong>');
		document.getElementById("oldClientCode").value = '';
		document.getElementById("oldClientName").value = '';
		document.getElementById("newClientCode").value = '';
		document.getElementById("newClientName").value = '';
		document.getElementById("oldClientCode").focus();
		return false;
	}
	else {
		var frm = document.getElementById("clientMerge");
		frm.submit();
	}
}
function checkMatterClientUpdationdata() {
	if (document.getElementById("fin_year").value == '') {
		alertMSG('info', '<strong>Please Enter Financial Year</strong>');
		document.getElementById("fin_year").focus();
		return false;
	}
	else if (document.getElementById("oldMatterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code (From)</strong>');
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else if (document.getElementById("newClientCode").value == '') {
		alertMSG('info', '<strong>Please Enter Client Code (New)</strong>');
		document.getElementById("newClientCode").focus();
		return false;
	}
	else if (document.getElementById("oldClientCode").value == document.getElementById("newClientCode").value) {
		alertMSG('info', '<strong>Client (Old) and Client (New) must be different</strong>');
		document.getElementById("oldMatterCode").value = '';
		document.getElementById("oldMatterDesc").value = '';
		document.getElementById("oldClientCode").value = '';
		document.getElementById("oldClientName").value = '';
		document.getElementById("newClientCode").value = '';
		document.getElementById("newClientName").value = '';
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else {
		var frm = document.getElementById("matterClientUpdation");
		frm.submit();
	}
}
function checkMatterStatusChangedata() {
	if (document.getElementById("matterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code</strong>');
		document.getElementById("matterCode").focus();
		return false;
	}
	else if (document.getElementById("new_status_code").value == '') {
		alertMSG('info', '<strong>Please Enter Status (New)</strong>');
		document.getElementById("new_status_code").focus();
		return false;
	}
	else if (document.getElementById("old_status_code").value == document.getElementById("new_status_code").value) {
		alertMSG('info', '<strong>Status (Old) and Status (New) must be different</strong>');
		document.getElementById("new_status_code").value = '';
		document.getElementById("new_status_code").focus();
		return false;
	}
	else {
		var frm = document.getElementById("matterStatusChange");
		frm.submit();
	}
}
function checkMatterCopydata() {
	if (document.getElementById("matterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code</strong>');
		document.getElementById("matterCode").focus();
		return false;
	}
	else {
		var frm = document.getElementById("matterCopy");
		frm.submit();
	}
}
function mybsplind() {
	document.getElementById("calendar_year").value = document.getElementById("holiday_ind").value;

}
function checkHolidaydata() {
	var frm = document.getElementById("procideHoliday");
	frm.submit();

}
function submitHolidaydata() {
	var frm2 = document.getElementById("SubmitHoliday");
	frm2.submit();

}
function submitMatterDataTransfer() {
	if (document.getElementById("fin_year").value == '') {
		alertMSG('info', '<strong>Please Enter Financial Year</strong>');
		document.getElementById("fin_year").focus();
		return false;
	}
	else if (document.getElementById("oldMatterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code (From)</strong>');
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else if (document.getElementById("newMatterCode").value == '') {
		alertMSG('info', '<strong>Please Enter Matter Code (To)</strong>');
		document.getElementById("newMatterCode").focus();
		return false;
	}
	else if (document.getElementById("oldMatterCode").value == document.getElementById("newMatterCode").value) {
		alertMSG('info', '<strong>Matter Code (From) and Matter Code (To) must be different</strong>');
		document.getElementById("oldMatterCode").value = '';
		document.getElementById("oldMatterDesc").value = '';
		document.getElementById("newMatterCode").value = '';
		document.getElementById("oldClientName").value = '';
		document.getElementById("newMatterCode").value = '';
		document.getElementById("newMatterDesc").value = '';
		document.getElementById("newClientCode").value = '';
		document.getElementById("newClientName").value = '';
		document.getElementById("oldMatterCode").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("matterDataTransfer");
		frm2.submit();
	}
}
function valid() {
	if (document.getElementById("start_date").value == "") {
		alertMSG('info', '<strong>Start date shouldnt be blank</strong>');
		document.getElementById("start_date").focus();
		return false;
	}
	if (document.getElementById("task_desc").value == "") {
		alertMSG('info', '<strong>Task description shouldnt be blank</strong>');
		document.getElementById("task_desc").focus();
		return false;
	}
	if (document.getElementById("task_freq").value == "") {
		alertMSG('info', '<strong>Task frequency shouldnt be blank</strong>');
		document.getElementById("task_freq").focus();
		return false;
	}
	if (document.getElementById("adv_notice").value == "") {
		alertMSG('info', '<strong>Advance notice period shouldnt be blank</strong>');
		document.getElementById("adv_notice").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("scheduleTask");
		frm2.submit();
	}
}
function checkFileUpload() {
	if (document.getElementById("file_name").value == "") {
		alertMSG('info', '<strong>Please Enter A Name</strong>');
		document.getElementById("file_name").focus();
		return false;
	}
	if (document.getElementById("userfiles").value == "") {
		alertMSG('info', '<strong>Select A File To Upload</strong>');
		document.getElementById("userfiles").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("fileSubmit");
		frm2.submit();
	}
}
function addRow(row, count) {
	var newRownum = parseInt(row + 1);
	var preRowNum = parseInt(newRownum - 1);
	document.getElementById('tRowCount').value = newRownum;
	document.getElementById("Add_row" + preRowNum).classList.add('d-none');
	$("#holiday").append('<tr id="tabRow' + newRownum + '"><td class="w-150 text-center"><input type="text" name="holiday_date' + newRownum + '" id="holiday_date' + newRownum + '" class="form-control datepicker2" value="" ></td><td class="w-250"><input type="text" name="holiday_day' + newRownum + '" id="holiday_day' + newRownum + '" class="form-control" value=""></td><td class=""><textarea name="holiday_desc' + newRownum + '" id="holiday_desc' + newRownum + '" class="form-control" rows="1"></textarea></td><td class="text-center TbladdBtn wd100"><input class="float-start me-2 text-center" type="button" name="Add_row' + newRownum + '" id ="Add_row' + newRownum + '" value="+" title="Add Row" onClick="addRow(' + newRownum + ', ' + newRownum + ')"  tabindex="' + newRownum + '">&nbsp; <input class=" float-start text-center delt" type="button" name="Remove_row' + newRownum + '" id="Remove_row' + newRownum + '"  value="-" title="Remove Row" onClick="removeRow(' + newRownum + ')" ></td></tr>');

}
function removeRow(id) {
	var row = document.getElementById("tabRow" + id);
	var row2 = document.getElementById("tRowCount");
	var preRowNum = parseInt(row2 - 1);
	if (row) {
		row.parentNode.removeChild(row);
		document.getElementById("Add_row" + preRowNum).classList.remove('d-none');
		document.getElementById("Add_row" + preRowNum).classList.add('d-block');
	}
}
function proc() {
	if (document.getElementById("query_id").value == "") {
		alertMSG('info', '<strong>Please Select A Query</strong>');
		document.getElementById("query_id").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("query_frm");
		frm2.submit();
	}
}
function procSearch() {
		var frm2 = document.getElementById("query_frm2");
		frm2.submit();
}
function search() {
	if (document.getElementById("search_by").value == "") {
		alertMSG('info', '<strong>Please Select What You Want To Search</strong>');
		document.getElementById("search_by").focus();
		return false;
	}
	if (document.getElementById("search_text").value == "") {
		var searchby = document.getElementById("search_by").value;
		alertMSG('info', '<strong>Please Enter ' + searchby + ' You Want To Search</strong>');
		document.getElementById("search_text").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("matter_infoFrm");
		frm2.submit();
	}
}
function paymentDataCheck() {

	var bcode = document.getElementById("branch_code").value;
	var psdt = document.getElementById("start_date").value;
	var pedt = document.getElementById("end_date").value;
	// var dbcode    = document.f1.daybook_code.value ;
	var cdate = document.getElementById("current_date").value;
	var ptype = document.getElementById("payee_payer_type").value;
	var pcode = document.getElementById("payee_payer_code").value;
	var pname = document.getElementById("payee_payer_name").value;
	pname = pname.replace(/&/ig, "|and|");

	var psdtymd = psdt.substr(6, 4) + psdt.substr(3, 2) + psdt.substr(0, 2);
	var pedtymd = pedt.substr(6, 4) + pedt.substr(3, 2) + pedt.substr(0, 2);
	var cdateymd = cdate.substr(6, 4) + cdate.substr(3, 2) + cdate.substr(0, 2);
	// 
	if (document.getElementById("branch_code").value == '') {
		alertMSG('info', '<strong>Please select Branch Code</strong>');
		document.getElementById("branch_code").focus();
		return false;
	}
	else if (document.getElementById("end_date").value == '') {
		alertMSG('info', '<strong>Please enter Period End Date</strong>');
		document.getElementById("end_date").focus();
		return false;
	}
	else if (psdtymd > cdateymd) {
		alertMSG('info', '<strong>Period Start Date must be <= Current Date</strong>');
		document.getElementById("start_date").focus();
		return false;
	}
	else if (pedtymd > cdateymd) {
		alertMSG('info', '<strong>Period End Date must be <= Current Date</strong>');
		document.getElementById("end_date").focus();
		return false;
	}
	else if (pedtymd < psdtymd) {
		alertMSG('info', '<strong>Period End Date must be >= Period Start Date</strong>');
		document.getElementById("end_date").focus();
		return false;
	}
	else if (document.getElementById("payee_payer_type").value == '') {
		alertMSG('info', '<strong>Please select Payee Type</strong>');
		document.getElementById("payee_payer_type").focus();
		return false;
	}
	else if (document.getElementById("payee_payer_type").value != 'O' && document.getElementById("payee_payer_code").value == '') {
		alertMSG('info', '<strong>Please Enter Payee</strong>');
		document.getElementById("payee_payer_code").focus();
		return false;
	}
	else if (document.getElementById("payee_payer_type").value == 'O' && document.getElementById("payee_payer_name").value == '') {
		alertMSG('info', '<strong>Please Enter Payee</strong>');
		document.getElementById("payee_payer_code").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("payment_frm");
		frm2.submit();
	}
}
function printpayment(divID) {
	//Get the HTML of div
	var divElements = document.getElementById(divID).innerHTML;
	var branch_code = document.getElementById("branchName").value;
	var end_date = document.getElementById("end_date").value;
	var payee_payer_name = document.getElementById("payee_payer_name").value;
	//document.getElementById("view").classList.add("d-none");
	//Get the HTML of whole page
	var oldPage = document.body.innerHTML;
	//Reset the page's HTML with div's HTML only
	document.body.innerHTML =
		"<html><head><title></title></head><body><table><thead><p class='text-center'>SINHA AND COMPANY <br>PAYMENTS MADE TO EMPLOYEE</p><table style='border=none'><tr><td width='3%'>Branch:</td><td width='20%'>" + branch_code + "</td><tr><td>Period:</td><td>UPTO " + end_date + "</td></tr><tr><td>Employee:</td><td>" + payee_payer_name + "</td></tr></table>" +
		divElements + "</body>";
	//Print Page
	window.print();
	//Restore orignal HTML
	document.body.innerHTML = oldPage;

}
function myPayeeType(valu) {
	var ptype = document.getElementById("payee_payer_type").value;

	document.getElementById("payee_payer_code").value = '';
	document.getElementById("payee_payer_name").value = '';

	if (ptype == '') {
		document.getElementById("payee_payer_code").disabled = true;
		document.getElementById("payee_payer_help").classList.add("d-none");
		document.getElementById("proc_button").focus();
	}
	else {
		document.getElementById("payee_payer_code").disabled = false;
		document.getElementById("payee_payer_help").classList.add("d-block");
		document.getElementById("payee_payer_help").classList.remove("d-none");
		document.getElementById("payee_payer_code").focus();
	}
}
function proc_vou(frm_id, id) {
	var sl = document.getElementById("serial_no").value;
	if (sl != '') {
		var frm2 = frm_id;
		document.getElementById(frm2 + id).target = '_blank';
		document.getElementById(frm2 + id).submit();
	}
}
function checkPaymentToParty() {
	var bcode = document.getElementById("branch_code").value;
	var vtype = document.getElementById("voucher_type").value;
	var finyr = document.getElementById("fin_year").value;
	var dbook = document.getElementById("daybook_code").value;
	var vchno = document.getElementById("voucher_no").value;
	// 
	if (document.getElementById("branch_code").value == '') {
		alertMSG('info', '<strong>Please select Branch Code</strong>');
		document.getElementById("branch_code").focus();
		return false;
	}
	else if (document.getElementById("voucher_type").value == '') {
		alertMSG('info', '<strong>Please enter Voucher Type</strong>');
		document.getElementById("voucher_type").focus();
		return false;
	}
	else if (document.getElementById("fin_year").value == '') {
		alertMSG('info', '<strong>Please enter Financial Year</strong>');
		document.getElementById("fin_year").focus();
		return false;
	}
	else if (document.getElementById("daybook_code").value == '') {
		alertMSG('info', '<strong>Please enter Daybook</strong>');
		document.getElementById("daybook_code").focus();
		return false;
	}
	else if (document.getElementById("voucher_no").value == '') {
		alertMSG('info', '<strong>Please enter Voucher No</strong>');
		document.getElementById("voucher_no").focus();
		return false;
	}
	else {
		var frm2 = document.getElementById("payment_to_party_frm");
		frm2.submit();
	}
}
function myselepage(frm_id, id) {
	var sl = document.getElementById("counsel_code").value;
	if (sl != '') {
		var frm2 = frm_id;
		document.getElementById(frm2 + id).target = '_blank';
		document.getElementById(frm2 + id).submit();
	}
}

function sub_realisation(frm_id) {
	var sl = document.getElementById("billNo").value;
	if (sl != '') {
		var frm2 = frm_id;
		document.getElementById(frm2).target = '_blank';
		document.getElementById(frm2).submit();
	}
	else {
		alertMSG('info', '<strong>Please Enter A Bill No</strong>');
	}
}
function getBillDetailsMatter(frm_id) {

	var psdt = document.getElementById('start_date').value;
	var pedt = document.getElementById('end_date').value;
	var brch_code = document.getElementById('branchCode').value;
	var clnt_code = document.getElementById('clientCode').value;
	var clnt_name = document.getElementById('clientName').value.replace('&', '_|_').replace('&', '_|_').replace('&', '_|_').replace('&', '_|_');
	clnt_name = clnt_name.replace("'", '-|-').replace("'", '-|-').replace("'", '-|-').replace("'", '-|-');
	var matr_code = document.getElementById('matterCode').value;
	var matr_desc = document.getElementById('matterDesc').value.replace('&', '_|_').replace('&', '_|_').replace('&', '_|_').replace('&', '_|_');
	matr_desc = matr_desc.replace("'", '-|-').replace("'", '-|-').replace("'", '-|-').replace("'", '-|-');
	var bill_stat = document.getElementById('bill_status').value;
	var psdtymd = psdt.substr(6, 4) + psdt.substr(3, 2) + psdt.substr(0, 2);
	var pedtymd = pedt.substr(6, 4) + pedt.substr(3, 2) + pedt.substr(0, 2);

	if (document.getElementById('end_date').value == '') {
		alertMSG('info', '<strong>Please Enter End Date</strong>');
		document.getElementById('end_date').focus();
		return false;
	}
	else if (document.getElementById('start_date').value == '') {
		alertMSG('info', '<strong>Please Enter Start Date</strong>');
		document.getElementById('start_date').focus();
		return false;
	}
	else if (pedtymd < psdtymd) {
		alertMSG('info', '<strong>End Date must be >= Start Date ........</strong>');
		document.getElementById('start_date').focus();
		return false;
	}
	else if (document.getElementById('branchCode').value == '') {
		alertMSG('info', '<strong>Enter Branch Code ........</strong>');
		document.getElementById('branchCode').focus();
		return false;
	}
	else if (document.getElementById('matterCode').value == '') {
		alertMSG('info', '<strong>Enter Matter Code ........</strong>');
		document.getElementById('matterCode').focus();
		return false;
	}
	else {
		var frm2 = frm_id;
		document.getElementById(frm2).submit();
	}
}
function myseleind(param1, param2, param3) {
	var slno = param1;
	var refslno = param2;
	var row = param3;
	//
	if (refslno != '' && refslno != 0) {

		var frm2 = 'finalbilltax';
		document.getElementById(frm2 + row).target = '_blank';
		document.getElementById(frm2 + row).submit();
		// var prog_name = 'rep-final-bill-tax' ; 
		// var bill_serial_no = 'x_x'+refslno ; 
		// var win = window.open(prog_name+"?param_id=<?php echo $param_id?>&my_menuid=<?php echo $my_menuid?>&params=<?php echo $params?>&query_id=<?php echo $query_id?>&query_module_code=<?php echo $query_module_code?>&query_name=<?php echo $query_name?>&query_program_name=<?php echo $query_program_name?>&branch_code=<?php echo $branch_code?>&start_date=<?php echo $start_date?>&end_date=<?php echo $end_date?>&client_code=<?php echo $client_code?>&client_name=<?php echo $client_name?>&matter_code=<?php echo $matter_code?>&matter_desc=<?php echo $matter_desc?>&bill_status=<?php echo $bill_status?>&bill_str="+bill_serial_no+"&row_count=1&print_ind1=on","bill_detail","top=0,left=0,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes") ;
	}
	else {
		var frm2 = 'finalbilltax';
		document.getElementById(frm2 + row).target = '_blank';
		document.getElementById(frm2 + row).submit();
		// var prog_name = 'rep_draft_bill_tax.php' ; 
		// var bill_serial_no = 'x_x'+slno ; 
		// var win = window.open(prog_name+"?param_id=<?php echo $param_id?>&my_menuid=<?php echo $my_menuid?>&params=<?php echo $params?>&query_id=<?php echo $query_id?>&query_module_code=<?php echo $query_module_code?>&query_name=<?php echo $query_name?>&query_program_name=<?php echo $query_program_name?>&branch_code=<?php echo $branch_code?>&start_date=<?php echo $start_date?>&end_date=<?php echo $end_date?>&client_code=<?php echo $client_code?>&client_name=<?php echo $client_name?>&matter_code=<?php echo $matter_code?>&matter_desc=<?php echo $matter_desc?>&bill_status=<?php echo $bill_status?>&bill_str="+bill_serial_no+"&row_count=1&print_ind1=on","bill_detail","top=0,left=0,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes") ;
	}
	win.resizeTo(screen.width, screen.height);
}

function myPrint() {

	var win = window.open("qry_bill_details_matter_rp.php?param_id=<?php echo $param_id?>&my_menuid=<?php echo $my_menuid?>&params=<?php echo $params?>&query_id=<?php echo $query_id?>&query_module_code=<?php echo $query_module_code?>&query_name=<?php echo $query_name?>&query_program_name=<?php echo $query_program_name?>&branch_code=<?php echo $branch_code?>&start_date=<?php echo $start_date?>&end_date=<?php echo $end_date?>&client_code=<?php echo $client_code?>&client_name=<?php echo $client_name?>&matter_code=<?php echo $matter_code?>&matter_desc=<?php echo $matter_desc?>", "repwin", "top=0,left=0,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes");
	win.resizeTo(screen.width, screen.height);
	//  window.location = "qry_bill_details_matter_pl_2.php?param_id=<?php echo $param_id?>&my_menuid=<?php echo $my_menuid?>&params=<?php echo $params?>&query_id=<?php echo $query_id?>&query_module_code=<?php echo $query_module_code?>&query_name=<?php echo $query_name?>&query_program_name=<?php echo $query_program_name?>&branch_code=<?php echo $branch_code?>&start_date=<?php echo $start_date?>&end_date=<?php echo $end_date?>&client_code=<?php echo $client_code?>&client_name=<?php echo $client_name?>&matter_code=<?php echo $matter_code?>&matter_desc=<?php echo $matter_desc?>&bill_status=<?php echo $bill_status?>"  ;
}
function getBillDetailsNotApp(frm2) {
	var asondt = document.getElementById('ason_date').value;
	var bcode = document.getElementById('branch_code').value;
	var cort_code = document.getElementById('courtCode').value;
	var cort_name = document.getElementById('courtName').value;
	var clnt_code = document.getElementById('clientCode').value;
	var clnt_name = document.getElementById('clientName').value.replace('&', '_|_').replace('&', '_|_').replace('&', '_|_').replace('&', '_|_');
	clnt_name = clnt_name.replace("'", '-|-').replace("'", '-|-').replace("'", '-|-').replace("'", '-|-');
	var matr_code = document.getElementById('matterCode').value;
	var matr_name = document.getElementById('matterDesc').value.replace('&', '_|_').replace('&', '_|_').replace('&', '_|_').replace('&', '_|_');
	matr_name = matr_name.replace("'", '-|-').replace("'", '-|-').replace("'", '-|-').replace("'", '-|-');
	//var repo_type  = document.getElementById('report_type.value ; 
	var outp_type = document.getElementById('output_type').value;
	var asdtymd = asondt.substr(6, 4) + asondt.substr(3, 2) + asondt.substr(0, 2);
	if (outp_type == 'Report') {
		document.getElementById(frm2).target = '_blank';
		document.getElementById(frm2).submit();
		//window.open("qry_bil_not_approved_rp.php?display_id=<?php echo $display_id?>&param_id=<?php echo $param_id?>&my_menuid=<?php echo $my_menuid?>&menu_id=<?php echo $menu_id?>&user_option=<?php echo $user_option?>&index=<?php echo $index?>&ord=<?php echo $ord?>&pg=<?php echo $pg?>&search_val=<?php echo $search_val?>&ason_date="+asondt+"&branch_code="+bcode+"&client_code="+clnt_code+"&client_name="+clnt_name+"&matter_code="+matr_code+"&matter_desc="+matr_name+"&court_code="+cort_code+"&court_name="+cort_name,"repwin","top=0,left=0,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes") ;
	}
	if (outp_type == 'Excel') {
		document.getElementById(frm2).submit();
		// window.open("qry_bil_not_approved_excel.php?display_id=<?php echo $display_id?>&param_id=<?php echo $param_id?>&my_menuid=<?php echo $my_menuid?>&menu_id=<?php echo $menu_id?>&user_option=<?php echo $user_option?>&index=<?php echo $index?>&ord=<?php echo $ord?>&pg=<?php echo $pg?>&search_val=<?php echo $search_val?>&ason_date="+asondt+"&branch_code="+bcode+"&client_code="+clnt_code+"&client_name="+clnt_name+"&matter_code="+matr_code+"&matter_desc="+matr_name+"&court_code="+cort_code+"&court_name="+cort_name,"repwin","top=0,left=0,menubar=yes,scrollbars=yes,resizable=yes,statusbar=yes") ;
	}

	document.getElementById('courtCode').value = '';
	document.getElementById('courtName').value = '';
	document.getElementById('clientCode').value = '';
	document.getElementById('clientName').value = '';
	document.getElementById('matterCode').value = '';
	document.getElementById('matterDesc').value = '';
}
function mySearch(frm2) {
	document.getElementById(frm2).submit();
}
function myRecSelect(row) {
	document.getElementById('clientSubmit' + row).submit();
}
function myMatterSelect(row) {
	document.getElementById('matterSubmit' + row).submit();
}
function caseDetailsClientMatterSearch(frm2) {
	document.getElementById(frm2).submit();
}
function match_gross() {
	//  var other_amount      = 0.00 ;

	var basic_rate = document.getElementById('basic_rate').value * 1;
	var hra_rate = document.getElementById('hra_rate').value * 1;
	var other_amount = document.getElementById('other_amount').value * 1;
	var special_allowance_rate = document.getElementById('special_allowance_rate').value * 1;
	var conveyance_allowance_rate = document.getElementById('conveyance_allowance_rate').value * 1;
	var gross_salary = document.getElementById('gross_salary').value * 1;



	total_amount = Math.round(basic_rate + hra_rate + other_amount + special_allowance_rate + conveyance_allowance_rate);
	document.getElementById('total_amount').value = total_amount; document.getElementById('total_amount').value;

	if (document.getElementById('gross_salary').value > 0 && document.getElementById('other_amount').value > 0) {
		if (gross_salary - total_amount != 0) {
			alert("Gross amount Does  not tally  ...");
			// document.getElementById('other_amount').focus();
			//         document.f1.other_amount.value = 0;
			//         format_number(document.f1.other_amount.value,2);
			// document.getElementById('other_amount').select();
			return false;
		}

	}
}
function calc_leave_balance(indx) {
	var opbal = document.getElementById('leave_opening_days_no'+indx).value * 1;
	var avail = document.getElementById('leave_availed_days_no'+indx).value * 1;
	var clbal = 0;

	clbal = opbal - avail;
	document.getElementById('leave_closing_days_no'+indx).value=opbal.toFixed(2);
	document.getElementById('leave_earned_days_no'+indx).value=avail.toFixed(2);
	document.getElementById('leave_availed_days_no'+indx).value=avail.toFixed(2);

}
function calc_hra() {
	var basic_rate = document.getElementById('basic_rate').value * 1;
	var hra_rate = document.getElementById('hra_rate').value * 1;
	if (document.getElementById('basic_rate').value * 1 > 0) {
		hra_rate = Math.round(basic_rate * 40 / 100);
	}

	document.getElementById('hra_rate').value = hra_rate; document.getElementById('hra_rate').value;

}

function get_bank_list() {
	if (document.getElementById('salary_pay_indicator').value == 'B') {
		//document.getElementById('bank_div').style.display ='block';
		document.getElementById('bank_code').disabled = false;
		document.getElementById('bank_account_no').className = "input_mand_int_style";
	} else {
		//document.getElementById('bank_div').style.display ='none';
		document.getElementById('bank_code').value = "";
		document.getElementById('bank_code').disabled = true;
		document.getElementById('bank_account_no').className = "accept_number_item";
	}
}
function format_field(vname,opt,deci){
	var valtxt = document.getElementById(vname).value;
	 var msg   = 'numeric.';
	  if(opt==2){
	   msg   = 'numeric or decimal value.';
	  }
	 if(deci==""){
	  deci = 2;
	 }
	  if(valtxt){
		if(!isNaN(valtxt)){ 
				 if(opt==2){
				  document.getElementById(vname).value= parseFloat(valtxt).toFixed(deci);
				 }
				 if(opt==1){
				  document.getElementById(vname).value= parseInt(valtxt);
				 }
		}else{
			 alert('Please enter '+ msg)
			 document.getElementById(vname).value ='';
			 document.getElementById(vname).focus();
		}
	  }
	}
function data_check(frm2) {
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var address = document.getElementById('email_id').value;
	if (document.getElementById('dob').value) {
		var byear = document.getElementById('dob').value.substr(6);
		var d = new Date();
		var curr_year = d.getFullYear();
		var age = curr_year - byear

	}
	var dob = document.getElementById('dob').value;
	var join_date = document.getElementById('join_date').value;
	var retirement_date = document.getElementById('retirement_date').value;
	var enroll_date = document.getElementById('enroll_date').value;
	var conf_date = document.getElementById('conf_date').value;
	var last_incr_date = document.getElementById('last_incr_date').value;
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var d1 = month + '-' + day + '-' + year;
	if (document.getElementById('employee_id').value == '' && document.getElementById('user_option').value != 'Add') {
		alert("Problem in Employee Code auto-generation....");
		document.getElementById('employee_id').focus();
		return false;
	}
	else if (document.getElementById('employee_initial').value == '') {
		alert("Please enter Initial of the Employee ...");
		document.getElementById('employee_initial').focus();
		return false;
	}
	else if (document.getElementById('employee_name').value == '') {
		alert("Please enter Name of the Employee ...");
		document.getElementById('employee_name').focus();
		return false;
	}
	else if (document.getElementById('email_id').value && reg.test(address) == false) {
		alert('Invalid Email Address');
		document.getElementById('email_id').value = '';
		document.getElementById('email_id').focus();
		return false;
	}
	else if (document.getElementById('gender').value == '') {
		alert("Please select Gender of the Employee ...");
		document.getElementById('gender').focus();
		return false;
	}
	else if (document.getElementById('dob').value && (Date.parse(dob) > Date.parse(d1))) {

		alert("Date of Birth cannot be greater than Current date...");
		document.getElementById('dob').value = '';
		document.getElementById('dob').focus();
		return false;
	}
	else if (document.getElementById('branch_code').value == '') {
		alert("Please enter Name of the Branch to which the employee is associated ...");
		document.getElementById('branch_code').focus();
		return false;
	}
	else if (document.getElementById('department_code').value == '') {
		alert("Please enter Name of the Department to which the employee is associated ...");
		document.getElementById('department_code').focus();
		return false;
	}
	else if (document.getElementById('designation_code').value == '') {
		alert("Please enter Designation of the Employee ...");
		document.getElementById('designation_code').focus();
		return false;
	}
	else if (document.getElementById('join_date').value && document.getElementById('dob').value
		&& (Date.parse(dob) >= Date.parse(join_date))) {
		alert("Joining date cannot be less than or equal to date of birth....");
		document.getElementById('join_date').value = '';
		document.getElementById('join_date').focus();
		return false;

	}
	else if (document.getElementById('conf_date').value && document.getElementById('dob').value &&
		(Date.parse(dob) >= Date.parse(conf_date))) {
		alert("Confirmation date cannot be less than or equal to date of birth....");
		document.getElementById('conf_date').value = '';
		document.getElementById('conf_date').focus();
		return false;

	}
	else if (document.getElementById('conf_date').value && document.getElementById('join_date').value &&
		(Date.parse(join_date) > Date.parse(conf_date))) {
		alert("Confirmation date cannot be less than  joining date....");
		document.getElementById('conf_date').value = '';
		document.getElementById('conf_date').focus();
		return false;

	}
	else if (document.getElementById('retirement_date').value && document.getElementById('dob').value &&
		(Date.parse(dob) >= Date.parse(retirement_date))) {

		alert("Retirment date cannot be less than or equal to date of birth....");
		document.getElementById('retirement_date').value = '';
		document.getElementById('retirement_date').focus();
		return false;

	}

	else if (document.getElementById('enroll_date').value && document.getElementById('dob').value &&
		(Date.parse(dob) >= Date.parse(enroll_date))) {

		alert("Enrollment date cannot be less than or equal to date of birth....");
		document.getElementById('enroll_date').value = '';
		document.getElementById('enroll_date').focus();
		return false;

	}


	else if (document.getElementById('retirement_date').value && document.getElementById('join_date').value &&
		(Date.parse(join_date) >= Date.parse(retirement_date))) {

		alert("Retirment date cannot be less than or equal to joining date...");
		document.getElementById('retirement_date').value = '';
		document.getElementById('retirement_date').focus();
		return false;

	}
	else if (document.getElementById('retirement_date').value && document.getElementById('conf_date').value &&
		(Date.parse(conf_date) >= Date.parse(retirement_date))) {

		alert("Retirment date cannot be less than or equal to confirmation date...");
		document.getElementById('retirement_date').value = '';
		document.getElementById('retirement_date').focus();
		return false;

	}
	else if (document.getElementById('retirement_date').value && document.getElementById('last_incr_date').value &&
		(Date.parse(last_incr_date) >= Date.parse(retirement_date))) {

		alert("Retirment date cannot be less than or equal to last increment date...");
		document.getElementById('retirement_date').value = '';
		document.getElementById('retirement_date').focus();
		return false;

	}
	else if (document.getElementById('last_incr_date').value && document.getElementById('dob').value &&
		(Date.parse(dob) >= Date.parse(last_incr_date))) {
		alert("Last increment date cannot be less than or equal to date of birth....");
		document.getElementById('last_incr_date').value = '';
		document.getElementById('last_incr_date').focus();
		return false;

	}
	else if (document.getElementById('last_incr_date').value && document.getElementById('join_date').value &&
		(Date.parse(join_date) >= Date.parse(last_incr_date))) {
		alert("Last increment date cannot be less than or equal to joining date...");
		document.getElementById('last_incr_date').value = '';
		document.getElementById('last_incr_date').focus();
		return false;

	}
	else if (document.getElementById('last_incr_date').value && document.getElementById('conf_date').value &&
		(Date.parse(conf_date) > Date.parse(last_incr_date))) {
		alert("Last increment date cannot be less than or equal to confirmation date...");
		document.getElementById('last_incr_date').value = '';
		document.getElementById('last_incr_date').focus();
		return false;

	}
    else if (document.getElementById('salary_pay_indicator').value && document.getElementById('salary_pay_indicator').value == 'B' && document.getElementById('bank_account_no').value == '') {

		alert("Please enter bank account number of the Employee ...");
		document.getElementById('bank_account_no').focus();
		return false;


	}
	else if (document.getElementById('salary_pay_indicator').value && document.getElementById('salary_pay_indicator').value == 'B' && document.getElementById('bank_code').value == '') {

		alert("Please enter bank code of the Employee ...");
		document.getElementById('bank_code').focus();
		return false;

	}

	else if (document.getElementById('status_code').value == '') {
		alert("Please select Employee status ...");
		document.getElementById('status_code').focus();
		return false;
	}
	else {
		document.getElementById(frm2).submit();
	}
}
function uploadfile(frm2) {
	document.getElementById(frm2).submit();
}
function uploadfile(frm2) {
	document.getElementById(frm2).submit();
}
function printBtn(option)
{
	document.getElementById('option').value=option;
//	document.getElementById('matter_infoFrm').submit();
}
function other_box()
{
	var option=document.getElementById('gender').value;
	if(option=='O')
	{
		document.getElementById("otherDiv").classList.remove('d-none');
		document.getElementById("otherDiv").classList.add('d-block');
		document.getElementById("others").classList.remove('d-none');
		document.getElementById("others").classList.add('d-block');
	}
}
function getExcel(opt) { 
    var frm2 = "clientReport";
    var myDiv = document.getElementById('main');
    document.getElementById('report').value = opt;
    
    if (opt === 'Print') {
        myDiv.addEventListener('click', function() {
            // Perform print function
            window.print();
        });
    } else {
        document.getElementById(frm2).submit();
    }
}






