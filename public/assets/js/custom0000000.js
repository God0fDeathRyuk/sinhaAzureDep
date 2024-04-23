$(document).ready(function(){
	// $('.dwnArw').click(function(){
	// 	$(this).toggleClass('bi bi-chevron-up');
	// 	$(this).parent().siblings().slideToggle('slow');
	// });
	
	// $('.mn-mnu-arw').click(function(){		
	// 	if( $('.dwnArw').hasClass('bi-chevron-up') ){
	// 		$('.dwnArw').trigger('click');
	// 	}
	// });

	

	 $('.mnuMn').click(function(){
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
});


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
    title: {display: false},
	legend: {display: false}
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
		format = format.replace("MM", month.toString().padStart(2,"0"));        

		//replace the year
		if (format.indexOf("yyyy") > -1) {
			format = format.replace("yyyy", year.toString());
		} else if (format.indexOf("yy") > -1) {
			format = format.replace("yy", year.toString().substr(2,2));
		}

		//replace the day
		format = format.replace("dd", day.toString().padStart(2,"0"));

		return format;
	}


    /* form submit code */
    // function formOption(option) {
    //     var form = document.getElementById("caseStatus");
    //     var SerialNo = document.getElementById("SerialNo");

    //     form.action = `${baseURL}/case/status/${option}`;

    //     if(option != 'add') SerialNo.required = true; 
    //     else SerialNo.required = false;
    // }

    function formOption(url='', option = '', formId) {
      var form = document.getElementById(formId);
      var inputs = document.getElementsByTagName('input');
  
      form.action = url + option;
  
        if(option == 'add') {
        for (let input of inputs) 
          input.required = false;
      }
    }

    function resetFields() {
        textarea = document.getElementsByTagName('textarea');
        for(let input of textarea) input.innerHTML = '';
    }

    function submitForm() {
        document.getElementById('caseStatusForm').submit();
    }

$(document).ready(function(){
	$('.prcdClk').click(function(){
    	$('.tbl-shw-hde').addClass("d-blck");
    	$(this).prop("disabled", true);
    	$('.btnSve').prop("disabled", false);
    });
});

function grant_perm(e)
{
  var checked_all = '' ;
  var total_row=document.getElementById("total_row").value;
  if(e=='C')
  { 
	
	for(i=1;i<=total_row;i++)
	{
	  document.getElementById("chk"+i).checked= true;
	}
  }
}
function grant_perm2(e)
{
  var checked_all = '' ;
  
  var total_row=document.getElementById("total_row").value
  if(e=='N')
  { 
	for(i=1;i<=total_row;i++)
	{
		document.getElementById("chk"+i).checked= false;
	}
  }
}
function grant_all()
{
   var total_nos   = document.getElementById("total_row").value;
   var checked_all = '' ;
   for(i=1;i<=total_nos;i++)
   {
	  document.getElementById("initial_perm"+i).checked= true;
   }
}

function revoke_all()
{
	var total_nos   = document.getElementById("total_row").value;
	var checked_all = '' ;
	for(i=1;i<=total_nos;i++)
	{
	   document.getElementById("initial_perm"+i).checked= false;
	}
}
function check_quick_link(i)
  {
      if (document.getElementById("quick_link"+i).checked && document.getElementById("chk"+i).checked)
      {
          alert("Please give permission first ...");
          document.getElementById("quick_link"+i).checked =  false
      }
  }
  function cchange(count){
	var cTrue=document.getElementById("initial_perm"+count).value
		if(cTrue=='Y')
		{
			$("#initial_perm"+count).val('N');
		}
		if(cTrue=='')
		{
			$("#initial_perm"+count).val('Y');
		}
		if(cTrue=='N')
		{
			$("#initial_perm"+count).val('Y');
		}

  }
  function displayPage()
{
	
	$('#page_menu').addClass('d-block');
	$('#page_menu').removeClass('d-none');

	$('#permission_on_menu').addClass('d-none');
	
}

function account_master_check()
   {
	var main_ac_code=document.getElementById("main_ac_code").value;
	var main_ac_desc=document.getElementById("main_ac_desc").value;
	var act_group_code=document.getElementById("act_group_code").value;
	var act_group_desc=document.getElementById("act_group_desc").value;
    if(main_ac_code =='')
	   {
		  alertMSG('info', '<strong> A/C Code Cannot Be Empty</strong>');
		  document.getElementById("main_ac_code").focus();
		  return false;
	   }
       else if(main_ac_desc =='')
	   {
		alertMSG('info', '<strong> A/C Name Cannot Be Empty</strong>');
		  document.getElementById("main_ac_desc").focus();
		  return false;
	   }
	   else if(act_group_code == "" || act_group_desc == "")
       {
		alertMSG('info', '<strong> A/C Group Cannot Be Empty</strong>');
		document.getElementById("act_group_desc").focus();
          return false;
       }
	   document.getElementById("submit").disabled=true;
   
}
function client_code_check()
   {
	var client_code=document.getElementById("clientCode").value;
	if(client_code!='')
	{
		alert("Your Client Code Is : "+client_code);
	}

   }
   /*========================== anindita 01-08-2023======================*/ 
//    function validnumbercheck() 
//    {
// 	var grandAmt=0;
// 	var tRowCount1=$("#row_no1").val();
// 	var baistax = $("#bill_amount_inpocket_stax").val();
// 	var baintax = $("#bill_amount_inpocket_ntax").val();
// 		for(i=1;i<=tRowCount1;i++)
// 		{
// 			if(document.getElementById("service_tax_ind"+i).checked== false){
// 				var bilAmt=$("#billed_amount"+i).val();
// 				grandAmt=parseInt(grandAmt)+parseInt(bilAmt);
// 				$("#bill_amount_inpocket_ntax").val(grandAmt);
// 				$("#bill_amount_inpocket").val(grandAmt);
// 				$("#total_bill_amount_ntax").val(grandAmt);
// 				$("#bill_amount_inpocket").val(grandAmt);
// 			}
// 		}
// 	}
	function validnumbercheck(e) {
		
		var key = window.event ? e.keyCode : e.which;
		
		//alert(key);
		//if (key=="48" || key=="49" || key=="50" || key=="51" || key=="52" || key=="53" || key=="54" || key=="55" || key=="56" || key=="57")
		var keychar = String.fromCharCode(key);
		reg = /\d/;
		if(reg.test(keychar) || key=="46" || key=="8" || key=="0")
		{
			return true
		}
		else
		{
			return false
		}
	}

	function changeCase(fld,n) {
		// var test = fld.value;
		// alert(test);
	var ind = 1;
	if(fld.value != '')
	{
		var val = fld.value;
		val = val.toUpperCase();
	
		if(val == 'I' || val == 'C')
		{
			fld.value = val;
		}
		else
		{
			alert("Enter Proper value .......        \n\nEither \" I \" for Inpocket... \n\nOr \n\n\" C \" for Counsel ...");
			fld.focus();
			fld.select();
			ind = 0;
			return false;
		}
	}
	}

	function amtValidate(fld,r_no,grid_no) {
	if(fld.value < 0)
	{
		alert('Amount must be > 0')
		//fld.focus() ;
		return false;
	}
	else
	{
		// validateNumber(fld, "Amount : ",2);
		clacTotalAmt();
		if(fld.value > 0)
		{
		if(grid_no == 'Grid1')
		{
			document.getElementById("printer_ind"+r_no).checked = true ;
			document.getElementById("prn_seq_no"+r_no).focus() ;
		}
		if(grid_no == 'Grid2')
		{
			document.getElementById("printer"+r_no).checked = true ;
			document.getElementById("prn_seq"+r_no).focus() ;
		}
		}
	}
	} 
	function clacTotalAmt() {
	calcCounselTot();
	calcInpocketTot();
	calcOutpocketTot();

	var total = 0;
	var inpocket   = document.getElementById("bill_amount_inpocket").value;
	var outpocket  = document.getElementById("bill_amount_outpocket").value; 
	var counsel    = document.getElementById("bill_amount_counsel").value; 

	total = inpocket + outpocket + counsel;
	total = parseFloat(total).toFixed(2);
	document.getElementById("total_amount").value = total;
	}
	function calcCounselTot() {
	var tot_row = document.getElementById("row_no1").value;
	tot_row = tot_row;
	var cousel_amt = 0;
	var tot_counsel = 0;
	for(var i=1; i<=tot_row; i++)
	{
		if(document.getElementById("io_ind"+i).value == 'C')
		{
			counsel_amt = document.getElementById("billed_amount"+i).value;
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
	for(var i=1; i<=tot_row; i++)
	{
		//alert(eval("document.f1.io_ind"+i+".value"));
		if(document.getElementById("io_ind"+i).value == 'I')
		{
			inpocket_amt = document.getElementById("billed_amount"+i).value;
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
	for(var i=1; i<=tot_row; i++)
	{
		outocket_amt = document.getElementById("amount"+i).value;
		tot_outpocket = tot_outpocket + outocket_amt;
	}
	tot_outpocket = parseFloat(tot_outpocket).toFixed(2);
	document.getElementById("bill_amount_outpocket").value = tot_outpocket;
	}


	function inpocket_delRow(e, n) {
		var row = document.getElementById("Ctd"+n);
		var tax_per = document.getElementById("tax_per").value;
		document.getElementById("service_tax_ind"+n).checked = false;
		if(document.getElementById("inp_ok_ind"+n).value == 'Y') {
			$(e).parent('tr').addClass('rowSlcted');
			document.getElementById("inp_ok_ind"+n).value = 'N';
			document.getElementById("inp_ok_ind"+n).style.background='rgb(163 200 213)';
			document.getElementById("inp_ok_ind"+n).style.color='rgb(163 200 213)';
			row.style.background='rgb(163 200 213)';
			calc_service_tax(n,tax_per,'Grid1');
		} else  {
			$(e).parent('tr').removeClass('rowSlcted');
			document.getElementById("inp_ok_ind"+n).value='Y';
			document.getElementById("inp_ok_ind"+n).style.background='#fff';
			document.getElementById("inp_ok_ind"+n).style.color='#fff';
			row.style.background='#fff';
			calc_service_tax(n,tax_per,'Grid1');
		}
	}

	function outpocket_delRow(e, n) {
		var row = document.getElementById("Ctd2"+n);
		var tax_per = document.getElementById("tax_per").value;
		document.getElementById("tax_ind"+n).checked=false;
		if(document.getElementById("out_ok_ind"+n).value=='Y') {
			$(e).parent('tr').addClass('rowSlcted');
			document.getElementById("out_ok_ind"+n).value='N';
			document.getElementById("out_ok_ind"+n).style.background='rgb(163 200 213)';
			document.getElementById("out_ok_ind"+n).style.color='rgb(163 200 213)';
			row.style.background='rgb(163 200 213)';
			calc_service_tax(n,tax_per,'Grid2');
			//adjust_value(n);
		} else {
			$(e).parent('tr').removeClass('rowSlcted');
			document.getElementById("out_ok_ind"+n).value='Y';
			document.getElementById("out_ok_ind"+n).style.background='#fff';
			document.getElementById("out_ok_ind"+n).style.color='#fff';
			row.style.background='#fff';
			calc_service_tax(n,tax_per,'Grid2');
			//row.style.background='#ECE8D7';
			//re_adjust_value(n);
		}
		//amount_calc();
	}

	function calc_service_tax(rno,taxper,gridno) 
	{
	if(gridno == "Grid1")
	{
		var inp_ok_ind = document.getElementById("inp_ok_ind"+rno).value;
		if(document.getElementById("service_tax_ind"+rno).checked == true)
		{
			var ioind  = document.getElementById("io_ind"+rno).value ;  
			var bilamt = document.getElementById("billed_amount"+rno).value ;  
			var taxamt = (bilamt*taxper)/100; 
			
			document.getElementById("service_tax_percent"+rno).value=taxper ;
			document.getElementById("service_tax_amount"+rno).value=taxamt ;   
			format_number(document.getElementById("service_tax_amount"+rno),2) ;
		
			calc_total_amount();
		} 
		else
		{
			var ioind  = document.getElementById("io_ind"+rno).value ;  
			var bilamt = document.getElementById("billed_amount"+rno).value ;  
			// var taxamt = Math.round(bilamt*taxper/100,0);
			var taxamt = (bilamt*taxper)/100;

			document.getElementById("service_tax_percent"+rno).value='' ;
			document.getElementById("service_tax_amount"+rno).value ='' ; 
		
			calc_total_amount();
		} 
	}
	else if(gridno == "Grid2")
	{
		if(document.getElementById("tax_ind"+rno).checked == true)
		{
		var ioind  = 'O' ;  
		var bilamt = document.getElementById("amount"+rno).value ;
		var taxamt = (bilamt*taxper)/100;


		document.getElementById("tax_percent"+rno).value=taxper ;
		document.getElementById("tax_amount"+rno).value=taxamt ;   
		format_number(document.getElementById("tax_amount"+rno),2) ;
		
		calc_total_amount();

		
		} 
		else
		{
		var ioind  = 'O' ;  
		var bilamt = document.getElementById("amount"+rno).value;  
	
		var taxamt = (bilamt*taxper)/100;


		document.getElementById("tax_percent"+rno).value='' ;
		document.getElementById("tax_amount"+rno).value ='' ; 
		
		calc_total_amount();
		} 
	}
	}

	function calc_total_amount() {
	var ip_stax = 0; var ip_ntax = 0; var ip_amt = 0; var svtax_ip = 0 ;
	var op_stax = 0; var op_ntax = 0; var op_amt = 0; var svtax_op = 0 ;
	var cn_stax = 0; var cn_ntax = 0; var cn_amt = 0; var svtax_cn = 0 ;
	
	for(i=1; i<=document.getElementById('row_no1').value; i++)
	{
		var ioind  = document.getElementById("io_ind"+i).value ;  
		var bilamt = document.getElementById("billed_amount"+i).value;  
		var taxamt = document.getElementById("service_tax_amount"+i).value;
		var inp_ok_ind = document.getElementById("inp_ok_ind"+i).value ;
		if(inp_ok_ind == 'Y')
		{
		if(document.getElementById("service_tax_ind"+i).checked == true)
		{
			if(ioind == 'I')
			{
				ip_stax += parseFloat(bilamt) ;  ip_ntax += 0;  ip_amt  += parseFloat(bilamt) ;  svtax_ip += parseFloat(taxamt);
			}  
			else if(ioind == 'C')
			{
			cn_stax += parseFloat(bilamt);  cn_ntax += 0;  cn_amt  += parseFloat(bilamt);  svtax_cn += parseFloat(taxamt);
			}  
		} 
		else if(document.getElementById("service_tax_ind"+i).checked == false)
		{
			if(ioind == 'I')
			{
			ip_stax += 0 ;       ip_ntax += parseFloat(bilamt);  ip_amt  += parseFloat(bilamt) ;  svtax_ip += 0 ;
			}  
			else if(ioind == 'C')
			{
			cn_stax += 0 ;       cn_ntax += parseFloat(bilamt);  cn_amt  += parseFloat(bilamt);  svtax_cn += 0 ;
			}  
		} 
		}
	}
	
	//---
	for(i=1; i<=document.getElementById("row_no2").value; i++)
	{
		var ioind  = 'O' ;  
		var bilamt = document.getElementById("amount"+i).value;  
		var taxamt = document.getElementById("tax_amount"+i).value;
		var out_ok_ind = document.getElementById("out_ok_ind"+i).value;
		if(out_ok_ind == 'Y')
		{
		if(document.getElementById("tax_ind"+i).checked == true)
		{
			op_stax += parseFloat(bilamt);  op_ntax += 0;  op_amt  += parseFloat(bilamt);  svtax_op += parseFloat(taxamt);
		} 
		else if(document.getElementById("tax_ind"+i).checked == false)
		{
			op_stax += 0 ;       op_ntax += parseFloat(bilamt);  op_amt  += parseFloat(bilamt);  svtax_op += 0 ;
		} 
		}
	}  
	//---
	var tbamt_stax  = parseFloat(ip_stax)+ parseFloat(op_stax)  + parseFloat(cn_stax);  
	var tbamt_ntax  = parseFloat(ip_ntax)    + parseFloat(op_ntax)  + parseFloat(cn_ntax);  
	var tbill_amt   = parseFloat(ip_amt)+ parseFloat(op_amt)+ parseFloat(cn_amt);  
	var tstax_amt   = parseFloat(svtax_ip)   + parseFloat(svtax_op) + parseFloat(svtax_cn) ;  

	var nbill_amt   = tbill_amt+tstax_amt;           
	
	//---
	if(ip_stax   > 0) { document.getElementById("bill_amount_inpocket_stax").value  = ip_stax;     format_number(document.getElementById("bill_amount_inpocket_stax"), 2) ; } else { document.getElementById("bill_amount_inpocket_stax").value   = '' ; }
	if(ip_ntax   > 0) { document.getElementById("bill_amount_inpocket_ntax").value  = ip_ntax;     format_number(document.getElementById("bill_amount_inpocket_ntax"), 2) ; } else { document.getElementById("bill_amount_inpocket_ntax").value   = '' ; }
	if(ip_amt    > 0) { document.getElementById("bill_amount_inpocket").value       = ip_amt;      format_number(document.getElementById("bill_amount_inpocket"),      2) ; } else { document.getElementById("bill_amount_inpocket").value        = '' ; }
	if(svtax_ip  > 0) { document.getElementById("service_tax_inpocket").value       = svtax_ip;    format_number(document.getElementById("service_tax_inpocket"),      2) ; } else { document.getElementById("service_tax_inpocket").value        = '' ; }

	if(op_stax   > 0) { document.getElementById("bill_amount_outpocket_stax").value = op_stax;     format_number(document.getElementById("bill_amount_outpocket_stax"), 2) ; } else { document.getElementById("bill_amount_outpocket_stax").value = '' ; }
	if(op_ntax   > 0) { document.getElementById("bill_amount_outpocket_ntax").value = op_ntax;     format_number(document.getElementById("bill_amount_outpocket_ntax"), 2) ; } else { document.getElementById("bill_amount_outpocket_ntax").value = '' ; }
	if(op_amt    > 0) { document.getElementById("bill_amount_outpocket").value      = op_amt;      format_number(document.getElementById("bill_amount_outpocket"),      2) ; } else { document.getElementById("bill_amount_outpocket").value      = '' ; }
	if(svtax_op  > 0) { document.getElementById("service_tax_outpocket").value      = svtax_op;    format_number(document.getElementById("service_tax_outpocket"),      2) ; } else { document.getElementById("service_tax_outpocket").value      = '' ; }

	if(cn_stax   > 0) { document.getElementById("bill_amount_counsel_stax").value   = cn_stax;     format_number(document.getElementById("bill_amount_counsel_stax"), 2) ; } else { document.getElementById("bill_amount_counsel_stax").value     = '' ; }
	if(cn_ntax   > 0) { document.getElementById("bill_amount_counsel_ntax").value   = cn_ntax;     format_number(document.getElementById("bill_amount_counsel_ntax"), 2) ; } else { document.getElementById("bill_amount_counsel_ntax").value     = '' ; }
	if(cn_amt    > 0) { document.getElementById("bill_amount_counsel").value        = cn_amt;      format_number(document.getElementById("bill_amount_counsel"),      2) ; } else { document.getElementById("bill_amount_counsel").value          = '' ; }
	if(svtax_cn  > 0) { document.getElementById("service_tax_counsel").value        = svtax_cn;    format_number(document.getElementById("service_tax_counsel"),      2) ; } else { document.getElementById("service_tax_counsel").value          = '' ; }

	if(tbamt_stax > 0) { document.getElementById("total_bill_amount_stax").value    = tbamt_stax;  format_number(document.getElementById("total_bill_amount_stax"),   2) ; } else { document.getElementById("total_bill_amount_stax").value       = '' ; }
	if(tbamt_ntax > 0) { document.getElementById("total_bill_amount_ntax").value    = tbamt_ntax;  format_number(document.getElementById("total_bill_amount_ntax"),   2) ; } else { document.getElementById("total_bill_amount_ntax").value       = '' ; }
	if(tbill_amt  > 0) { document.getElementById("total_amount").value              = tbill_amt;   format_number(document.getElementById("total_amount"),             2) ; } else { document.getElementById("total_amount").value                 = '' ; }
	if(tstax_amt  > 0) { document.getElementById("total_service_tax").value         = tstax_amt;   format_number(document.getElementById("total_service_tax"),        0) ; } else { document.getElementById("total_service_tax").value            = '' ; }
	//alert(nbill_amt);
	if(nbill_amt  > 0) { document.getElementById("net_bill_amount").value           = nbill_amt;   format_number(document.getElementById("net_bill_amount"),          0) ; } else { document.getElementById("net_bill_amount").value              = '' ; }
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
	function format_number(object1,object2) {
		var amt1    = object1.value;
		var decp    = object2;
		var mamt1   = amt1;
		var mdecp   = decp;
		var strlen  = mamt1.length;
		var decpos  = mamt1.indexOf(".");
		var strint  = "";
		var strdec  = "";
		var intlen  = 0 ;
		var declen  = 0 ;
		var aint    = "" ;
		var adec    = "" ;
		var aintlen = 0 ;
		var adeclen = 0 ;
		var aintlen = 0 ;
		var adeclen = 0 ;
		var aintno  = 0 ;
		var adecno  = 0 ;
		var ndec    = "" ;
		var ndecno  = 0 ;
		var decfac  = "10" ;
		var intadd  = 0 ;
		var decadd  = 0 ;
		var digcnt  = 0 ;
		var retint  = "";
		var retdec  = "";
		//------ Find Ount Sign
		if(object1.value < 0)
		{
		   var sign = '-';
		}
		else
		{
		   var sign = '';
		}
		//------ Find Out Integer and Decimals  
		if (decpos == -1)
		{
		   strint = mamt1.substr(0,strlen);
		   strdec = "";
		}  
		else
		{
		   strint = mamt1.substr(0,decpos);
		   strdec = mamt1.substr(decpos+1);
		}  
		if (strint == "") { strint = "0" ; }
		//------ Re-generate Integer and Decimals without comma
		for (i=0; i<strint.length; i++)
		{
		   if (strint.substr(i,1) >= 0 || strint.substr(i,1) <= 9)
		   {
			 aint = aint + strint.substr(i,1);
		   }
		}
  
		for (i=0; i<strdec.length; i++)
		{
		   if (strdec.substr(i,1) >= 0 || strdec.substr(i,1) <= 9)
		   {
			 adec = adec + strdec.substr(i,1);
		   }
		}
		aintlen = aint.length ;
		adeclen = adec.length ;
		aintno  = parseInt(aint);
		adecno  = parseInt(adec);
		ndec    = decfac + adec ;
		//------- Formatting Decimal Places 
		if (adeclen > parseInt(mdecp))
		{
		   decadd = 0;
		   if (parseInt(ndec.substr(2+parseInt(mdecp),1)) >= 5) { decadd = 1; }
		   ndecno  = parseInt(ndec.substr(0,2+parseInt(mdecp))) + decadd ;
		   adec    = ("" + ndecno).substr(2,parseInt(mdecp)) ;
		   intadd  = ("" + ndecno).substr(1,1) ;
		   aintno  = aintno + parseInt(intadd) ;
		   aint    = "" + aintno ;
		 } 
		 adeclen = adec.length ;
		 retdec  = adec ;
		 for (i=(adeclen+1); i<=parseInt(mdecp); i++)
		 {
			retdec = retdec + '0' ;
		 }
		 //------- Formatting Integer Places 
		 aintlen = aint.length ;
		 retint  = "" ;
		 for (i=(aintlen-1); i>=0; i--)
		 {
		   digcnt = digcnt + 1;
		   if (digcnt == 4)
		   {
			  retint = retint ;
			  digcnt = 1;
		   }
		   retint = aint.substr(i,1) + retint;
		 }
		 //------ Return Value  
		 rvalue = retint ;   
		 if (retdec != "") { rvalue = rvalue + "." + retdec; }  
		 //----------------------------------------------
		 object1.value=sign+rvalue;
	}

	function myCheckPrintInd(rowno,gridno) {
	if (gridno == 'Grid1') 
	{
		if (document.getElementById("printer_ind"+rowno).checked == false)
		{
		document.getElementById("billed_amount"+rowno).value = '' ;
		clacTotalAmt();
		}	
	}
	else if (gridno == 'Grid2') 
	{
		if (document.getElementById("printer"+rowno).checked == false)
		{
		document.getElementById("amount"+rowno).value = '' ;
		clacTotalAmt();
		}	
	}
		
	}

	function make_date(obj) {
	var objdate=obj.value;
	var datelen=objdate.length;
	if(objdate != '' && objdate !='00-00-0000')
	{
	var dtCh = "-" ;
	var dtCh1 = "/" ;
	var dtCh2 = "." ;
	index=objdate.indexOf(dtCh);
	index1=objdate.indexOf(dtCh1);
	index2=objdate.indexOf(dtCh2);
	if(objdate.indexOf('--',0) != -1)
	{
		Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
		//alert("Invalid Date Format");	
		obj.value='';
		//obj.focus();
	}
	else
	{
	if((index>0 && index1<0) || (index1>0 && index<0))
	{
		var pos11     = objdate.indexOf(dtCh);
		var pos12     = objdate.indexOf(dtCh,pos11+1);
	}
	else if((index<0 && index1<0))
	{
		var pos11=1;
		var pos12=1;
	}
	if(((pos11>0 && pos12>0) || (pos11<0 && pos12<0)))
	{
	if(datelen==4)		//dmyy
	{
		first=objdate.substr(0,1);
		dd=0+first;
		second=objdate.substr(1,1);
		mm=0+second;
		last=objdate.substr(2,3);
		if(last<=80)
		{
		yyyy=20+last;
		}
		if(last>80)
		{
		yyyy=19+last;
		}
		obj.value=dd+"-"+mm+"-"+yyyy;
	}
	else if(datelen==5)		//d-m-y
	{
		if(index>=1 || index1>=1 || index2>=2)
		{
			first=objdate.substr(0,1);
			dd=0+first;
			second=objdate.substr(2,1);
			mm=0+second;
			last=objdate.substr(4,3);
			yyyy=200+last;
			obj.value=dd+"-"+mm+"-"+yyyy;
		}
		else
		{
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date Format");	
		    obj.value='';
			//obj.focus();
		}
	}
	else if(datelen==6)		//d-m-yy,ddmmyy,dd-m-y,d-mm-y
	{
		if(index==1 || index1==1  || index2==1)		//d-m-yy
		{
			first=objdate.substr(0,1);
			dd=0+first;
			if(pos12==3)
			{
				second=objdate.substr(2,1);
				mm=0+second;
				last=objdate.substr(4,4);
				if(last<=80)
				{
				yyyy=20+last;
				}
				if(last>80)
				{
				yyyy=19+last;
				}
				obj.value=dd+"-"+mm+"-"+yyyy;
			}
			else if(pos12==4)
			{
				second=objdate.substr(2,2);
				mm=second;
				last=objdate.substr(5,6);
				yyyy=200+last;
				if(mm>12 || mm<='00')
				{
					Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
					//alert("Invalid Date");	
					obj.value='';
					//obj.focus();
				}
				obj.value=dd+"-"+mm+"-"+yyyy;
			}
			else
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");	
				obj.value='';
				//obj.focus();
			}
		}	
		else if(index==2 || index1==2 || index2==2)
		{
			first=objdate.substr(0,2);
			second=objdate.substr(3,1);
			mm=0+second;
			last=objdate.substr(5,6);
			yyyy=200+last;
			if(first>31 || first<='00')
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");
				obj.value='';	
				//obj.focus();
			}
			else
			{
				obj.value=first+"-"+mm+"-"+yyyy;
			}
		}
		else if(index==-1 && index==-1 && index2==-1)		//ddmmyy
		{
			first=objdate.substr(0,2);
			second=objdate.substr(2,2);
			last=objdate.substr(4,4);
			if(last<=80)
			{
			yyyy=20+last;
			}
			if(last>80)
			{
			yyyy=19+last;
			}
			if(first>31 || first<='00')
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");	
				obj.value='';
				//obj.focus();
			}
			else if(second>12 || second<='00')
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");
				obj.value='';	
				//obj.focus();
			}
			else if(first<=31 && second<=12)
			{
				obj.value=first+"-"+second+"-"+yyyy;
			}
		}
		else
		{
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			alert("Invalid Date Format");	
	     	obj.value='';
			//obj.focus();
		}
	}
	else if(datelen==7)		//dd-m-yy,d-mm-yy,dd-mm-y
	{
		if(index==2 || index1==2 || index2==2)	//dd-m-yy
		{
			first=objdate.substr(0,2);
			if(pos12==4)
			{
				second=objdate.substr(3,1);
				mm=0+second;
				last=objdate.substr(5,8);
				if(last<=80)
				{
				yyyy=20+last;
				}
				if(last>80)
				{
				yyyy=19+last;
				}
				if(first>31 || first<='00')
				{
					Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
					//alert("Invalid Date");	
					obj.value='';
					//obj.focus();
				}
				else 
				{
					obj.value=first+"-"+mm+"-"+yyyy;
				}	
			}
			else if(pos12==5)
			{
				second=objdate.substr(3,2);
				mm=second;
				last=objdate.substr(6,6);
				yyyy=200+last;
				if(mm>12 || mm<='00')
				{
					Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
					//alert("Invalid Date");	
					obj.value='';
					//obj.focus();
				}
				else
				{
					obj.value=first+"-"+mm+"-"+yyyy;
				}
			}
			else
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");
				obj.value='';	
				//obj.focus();
			}
		}
		else if(index==1 || index1==1 || index2==1)
		{
			first=objdate.substr(0,1);
			dd=0+first;
			second=objdate.substr(2,2);
			last=objdate.substr(5,8);
			if(last<=80)
			{
			yyyy=20+last;
			}
			if(last>80)
			{
			yyyy=19+last;
			}
			if(second>12 || second<='00')
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");
				obj.value='';	
				//obj.focus();
			}
			else 
			{
				obj.value=dd+"-"+second+"-"+yyyy;
			}	
		}
		else
		{
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date Format");	
			obj.value='';
			//obj.focus();
		}
	}
	else if(datelen==8)		//d-m-yyyy,ddmmyyyy,dd-mm-yy
	{
		if(index>=1 || index1>=1 || index2>=1)
		{
			if(index==1 || index1==1 || index2==1)		//d-m-yyyy
			{
				first=objdate.substr(0,1);
				dd=0+first;
				second=objdate.substr(2,1);
				mm=0+second;
				last=objdate.substr(4,8);
				yyyy=last;
				obj.value=dd+"-"+mm+"-"+yyyy;
			}
			if(index==2 || index1==2 || index2==2)		//dd-mm-yy
			{
				first=objdate.substr(0,2);
				dd=first;
				second=objdate.substr(3,2);
				mm=second;
				last=objdate.substr(6,8);
				if(last<=80)
				{
				yyyy=20+last;
				}
				if(last>80)
				{
				yyyy=19+last;
				}
				if(dd>31 || dd<='00')
				{
					Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
					//alert("Invalid Date");
					obj.value='';	
					//obj.focus();
				}
				else if(mm>12 || mm<='00')
				{
					Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
					//alert("Invalid Date");
					obj.value='';	
					//obj.focus();
				}
				else if(dd<=31 && mm<=12)
				{
					obj.value=dd+"-"+mm+"-"+yyyy;
				}
			}
			else
			{
				Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date Format");	
				obj.value='';
				//obj.focus();
			}
		}	
		else if(index==-1 && index==-1 && index2==-1)	//ddmmyyyy
		{
			first=objdate.substr(0,2);
			second=objdate.substr(2,2);
			last=objdate.substr(4,8);
			yyyy=last;
			if(first>31 || first<='00')
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");
				obj.value='';	
				//obj.focus();
			}
			else if(second>12 || second<='00')
			{
				Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
				//alert("Invalid Date");
				obj.value='';	
				//obj.focus();
			}
			else if(first<=31 && second<=12)
			{
				obj.value=first+"-"+second+"-"+yyyy;
			}
		}
		else
		{
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date Format");
			obj.value='';
			//obj.focus();
		}
	}
	else if(datelen==9)			//dd-m-yyyy
	{
		first=objdate.substr(0,2);
		second=objdate.substr(3,1);
		mm=0+second;
		last=objdate.substr(5,9);
		yyyy=last;
		if(first>31 || first<='00')
		{
			Swal.fire({ text: 'Invalid Date !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date");
			obj.value='';	
			//obj.focus();
		}
		else 
		{
			obj.value=first+"-"+mm+"-"+yyyy;
		}	
	}
	else if(datelen==10)		//dd-mm-yyyy
	{
		first=objdate.substr(0,2);
		dd=first;
		second=objdate.substr(3,2);
		mm=second;
		last=objdate.substr(6,10);
		yyyy=last;
		if(dd>31 || dd<='00')
		{
			Swal.fire({ text: 'Invalid Date !!! Please Enter a valid Day ... ' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date !!! Please Enter a valid Day ...");	
			obj.value='';
			//obj.focus();
		}
		else if(mm>12 || mm<='00')
		{
			Swal.fire({ text: 'Invalid Date !!! Please Enter a valid Month ... ' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date !!! Please enter a valid Month...");
			obj.value='';	
			//obj.focus();
		}
		else if(dd<=31 && mm<=12)
		{
			obj.value=dd+"-"+mm+"-"+yyyy;
		}
		else
		{
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date Format");
			obj.value='';
			//obj.focus();
		}
	}
		else
		{
			Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
			//alert("Invalid Date Format");	
			obj.value='';
			//obj.focus();
		}
	}
	else
	{
		Swal.fire({ text: 'Invalid Date Format !!' }).then((result) => { setTimeout(() => {obj.focus()}, 500) });
		//alert("Invalid Date Format");
		obj.value=''; 
		//obj.focus();
	}
	}
  }
    }

	function isDate(dtStr,obj1) {
     if(dtStr != '' && dtStr !='00-00-0000')
     {
        var minYear     = 1900 ;
        var maxYear     = 2100 ;
        var dtCh        = "-" ;
	    var pos1        = dtStr.indexOf(dtCh) ;
	    var pos2        = dtStr.indexOf(dtCh,pos1+1) ;
	    var strDay      = dtStr.substring(0,pos1) ;
	    var strMonth    = dtStr.substring(pos1+1,pos2) ;
	    var strYear     = dtStr.substring(pos2+1) ;
	    var intday      = (strDay*1)   ;
	    var intmonth    = (strMonth*1) ; 
	    var intyear     = (strYear*1)

	    if (strYear.length != 4 || intyear == 0 || intyear < minYear || intyear > maxYear)
	    {
			Swal.fire({ text: 'Please Enter a valid 4 digit Year ...' }).then((result) => { setTimeout(() => {obj1.focus()}, 500) });
		  //alert("Please Enter a valid 4 digit Year ........ " ) ;
			obj1.value="";
			//obj1.focus();
		  
		  return false ;
	    }

        if (strMonth.length != 2 || intmonth < 1 || intmonth > 12)
	    {
			Swal.fire({ text: 'Please Enter a valid Month ...' }).then((result) => { setTimeout(() => {obj1.focus()}, 500) });
		  //alert("Please Enter a valid Month ........ " ) ;
			obj1.value="";
			//obj1.focus();
		  return false ;
	    }

        if (strDay.length != 2 || intday < 1 || intday > DaysInMonth(intyear,intmonth))
	    {
			Swal.fire({ text: 'Please Enter a valid Day ...' }).then((result) => { setTimeout(() => {obj1.focus()}, 500) });
		  //alert("Please Enter a valid Day ........ " ) ;
			obj1.value="";
			//obj1.focus();
		  	return false ;
	    }
     }
     return true;
   }

   function DaysInMonth(yyno,mthno) {
   	   if (mthno==4 || mthno==6 || mthno==9 || mthno==11) { return 30 ; }
   	   if (mthno==1 || mthno==3 || mthno==5 || mthno== 7 || mthno== 8 || mthno== 10 || mthno== 12 ) { return 31 ; }
   	   if (mthno==2 ) { return (((yyno % 4 == 0) && ((!(yyno % 100 == 0)) || (yyno % 400 == 0))) ? 29 : 28 ) ; }
   }

	function dateValid(obj1,obj2,param,lb1,lb2) {
      if (!isDate(obj1.value,obj1)) {
          return false;
      }
      var dt1 = parseFloat((obj1.value).substr(6,4)+(obj1.value).substr(3,2)+(obj1.value).substr(0,2));
      var dt2 = parseFloat((obj2.value).substr(6,4)+(obj2.value).substr(3,2)+(obj2.value).substr(0,2));
      if (param == 'L') {
		console.log(dt1, dt2);
          if (dt1 > dt2) {
              alert(lb1+" can not be greater than "+lb2+" ...");
			  //document.getElementById("activity_date"+test).value="";
			  obj1.value = "";
			  obj1.focus();
              chk_flag == 0; 
              return false;
          } else { chk_flag = 1; }
      }
      else if (param == 'G') {
          if (dt1 < dt2) {
              alert(lb1+" can not be less than "+lb2+" ...");
              obj1.value = "";
			  obj1.focus();
              return false;
          }
      }
      else if (param == 'E') {
          if (dt1 == dt2) {
              alert(lb1+" can not be equal to "+lb2+" ...");
              obj1.value = "";
			  obj1.focus();
              return false;
          }
      }
      else if (param == 'GE') {
          if (dt1 >= dt2) {
              alert(lb1+" can not be less than equal to "+lb2+" ...");
			  obj1.value = "";
			  obj1.focus();
              return false;
          }
      }
      else if (param == 'LE') {
          if (dt1 <= dt2) {
              alert(lb1+" can not be greater than equal to "+lb2+" ...");
              obj1.value = "";
			  obj1.focus();
              return false;
          }
      }
      return true;
  	}

	function chkActivity(fld) {
		if(fld.value != "")   
		{ 
			let obj = document.getElementById("end_date");
			make_date(fld);
			if(obj != null){
				if(obj.value != '')
				{ 
					dateValid(fld,document.getElementById("end_date"),'L',"Activity Date"," Bill period...");
					// dateValid(fld,document.f1.start_date,'G',"Activity Date"," Bill period...");
				} 
			} else {
					dateValid(fld,document.getElementById("cur_date"),'L',"Bill Date","Current Date");
					//document.getElementById("billDateUpto").value = document.getElementById("cur_date").value;
				}
		} 
	}
	function chkActivity2(fld,test) {
		if(fld.value != "") 
		{ 
			make_date(fld);
			if(document.getElementById("end_date").value != '')
			{ 
				dateValid(fld,document.getElementById("end_date"),'L',"Activity Date"," Bill period...");
				// dateValid(fld,document.f1.start_date,'G',"Activity Date"," Bill period...");
			}
		} 
	}

	function setFormat(obj,dec) {
        if (obj.value != '' && obj.value != 0.00) { format_number(obj,dec) ; } else { obj.value = '' ; }
    }
	
	function calc_total() {
        var xIptBillAmt     = document.billSummaryCorrection2.bill_amount_inpocket.value        ;
        var xOptBillAmt     = document.billSummaryCorrection2.bill_amount_outpocket.value       ;
		//alert(xOptBillAmt);
        var xCnsBillAmt     = document.billSummaryCorrection2.bill_amount_counsel.value       ;
        var xSvtBillAmt     = document.billSummaryCorrection2.bill_amount_service_tax.value     ;
        var xTotBillAmt     = (parseFloat(xIptBillAmt)+parseFloat(xOptBillAmt)+parseFloat(xCnsBillAmt)+parseFloat(xSvtBillAmt)); 
		//alert(xTotBillAmt);
        //
        var xIptRealAmt     = document.billSummaryCorrection2.realise_amount_inpocket.value        ;
        var xOptRealAmt     = document.billSummaryCorrection2.realise_amount_outpocket.value       ;
        var xCnsRealAmt     = document.billSummaryCorrection2.realise_amount_counsel.value         ;
        var xSvtRealAmt     = document.billSummaryCorrection2.realise_amount_service_tax.value     ;
        var xTotRealAmt     = (parseFloat(xIptRealAmt)+parseFloat(xOptRealAmt)+parseFloat(xCnsRealAmt)+parseFloat(xSvtRealAmt));      
        //
        var xIptAdjAmt      = document.billSummaryCorrection2.adjusted_amount_inpocket.value       ;
        var xOptAdjAmt      = document.billSummaryCorrection2.adjusted_amount_outpocket.value      ;
        var xCnsAdjAmt      = document.billSummaryCorrection2.adjusted_amount_counsel.value        ;
        var xSvtAdjAmt      = document.billSummaryCorrection2.adjusted_amount_service_tax.value    ;
        var xTotAdjAmt      = (parseFloat(xIptAdjAmt)+parseFloat(xOptAdjAmt)+parseFloat(xCnsAdjAmt)+parseFloat(xSvtAdjAmt));                    
        //
        var xIptDefAmt      = document.billSummaryCorrection2.deficit_amount_inpocket.value        ;
        var xOptDefAmt      = document.billSummaryCorrection2.deficit_amount_outpocket.value       ;
        var xCnsDefAmt      = document.billSummaryCorrection2.deficit_amount_counsel.value         ;
        var xSvtDefAmt      = document.billSummaryCorrection2.deficit_amount_service_tax.value     ;
        var xTotDefAmt      = (parseFloat(xIptDefAmt)+parseFloat(xOptDefAmt)+parseFloat(xCnsDefAmt)+parseFloat(xSvtDefAmt));
        //
        var xIptBalAmt      = parseFloat(xIptBillAmt-xIptRealAmt-xIptAdjAmt-xIptDefAmt)   ;                    
        var xOptBalAmt      = parseFloat(xOptBillAmt-xOptRealAmt-xOptAdjAmt-xOptDefAmt)   ;                    
        var xCnsBalAmt      = parseFloat(xCnsBillAmt-xCnsRealAmt-xCnsAdjAmt-xCnsDefAmt)   ;                    
        var xSvtBalAmt      = parseFloat(xSvtBillAmt-xSvtRealAmt-xSvtAdjAmt-xSvtDefAmt)   ;                    
        var xTotBalAmt      = parseFloat(xTotBillAmt-xTotRealAmt-xTotAdjAmt-xTotDefAmt)   ;     
        //
        var xIptBkdAmt      = document.billSummaryCorrection2.booked_amount_inpocket.value        ;
        var xOptBkdAmt      = document.billSummaryCorrection2.booked_amount_outpocket.value       ;
        var xCnsBkdAmt      = document.billSummaryCorrection2.booked_amount_counsel.value         ;
        var xSvtBkdAmt      = document.billSummaryCorrection2.booked_amount_service_tax.value     ;
        var xTotBkdAmt      = (parseFloat(xIptBkdAmt)+parseFloat(xOptBkdAmt)+parseFloat(xCnsBkdAmt)+parseFloat(xSvtBkdAmt));                  
        //
        var xIptRcvAmt      = parseFloat(xIptBalAmt-xIptBkdAmt) ;            
        var xOptRcvAmt      = parseFloat(xOptBalAmt-xOptBkdAmt) ;            
        var xCnsRcvAmt      = parseFloat(xCnsBalAmt-xCnsBkdAmt) ;            
        var xSvtRcvAmt      = parseFloat(xSvtBalAmt-xSvtBkdAmt) ;            
        var xTotRcvAmt      = parseFloat(xTotBalAmt-xTotBkdAmt) ;            
        //
        
        document.billSummaryCorrection2.bill_amount_total.value               = xTotBillAmt;       setFormat(document.billSummaryCorrection2.bill_amount_total,         2) ;
        document.billSummaryCorrection2.realise_amount_total.value            = xTotRealAmt;       setFormat(document.billSummaryCorrection2.realise_amount_total,      2) ;
        document.billSummaryCorrection2.adjusted_amount_total.value           = xTotAdjAmt;        setFormat(document.billSummaryCorrection2.adjusted_amount_total,     2) ;
        document.billSummaryCorrection2.deficit_amount_total.value            = xTotDefAmt;        setFormat(document.billSummaryCorrection2.deficit_amount_total,      2) ;
        //
        document.billSummaryCorrection2.balance_amount_inpocket.value         = xIptBalAmt;        setFormat(document.billSummaryCorrection2.balance_amount_inpocket,        2) ;
        document.billSummaryCorrection2.balance_amount_outpocket.value        = xOptBalAmt;        setFormat(document.billSummaryCorrection2.balance_amount_outpocket,       2) ;
        document.billSummaryCorrection2.balance_amount_counsel.value          = xCnsBalAmt;        setFormat(document.billSummaryCorrection2.balance_amount_counsel,         2) ;
        document.billSummaryCorrection2.balance_amount_service_tax.value      = xSvtBalAmt;        setFormat(document.billSummaryCorrection2.balance_amount_service_tax,     2) ;
        document.billSummaryCorrection2.balance_amount_total.value            = xTotBalAmt;        setFormat(document.billSummaryCorrection2.balance_amount_total,           2) ;
        //
        document.billSummaryCorrection2.receivable_amount_inpocket.value      = xIptRcvAmt;        setFormat(document.billSummaryCorrection2.receivable_amount_inpocket,     2) ;
        document.billSummaryCorrection2.receivable_amount_outpocket.value     = xOptRcvAmt;        setFormat(document.billSummaryCorrection2.receivable_amount_outpocket,    2) ;
        document.billSummaryCorrection2.receivable_amount_counsel.value       = xCnsRcvAmt;        setFormat(document.billSummaryCorrection2.receivable_amount_counsel,      2) ;
        document.billSummaryCorrection2.receivable_amount_service_tax.value   = xSvtRcvAmt;        setFormat(document.billSummaryCorrection2.receivable_amount_service_tax,  2) ;
        document.billSummaryCorrection2.receivable_amount_total.value         = xTotRcvAmt;        setFormat(document.billSummaryCorrection2.receivable_amount_total,        2) ;
        //
        if(document.billSummaryCorrection2.balance_amount_total.value > 0) { document.billSummaryCorrection2.status_code.value = 'O' ; document.billSummaryCorrection2.status_desc.value = 'Outstanding' ; } else { document.billSummaryCorrection2.status_code.value = 'S' ; document.billSummaryCorrection2.status_desc.value = 'Settled' ; }
    }
	
	function getBillInfo(e) {
		if(e.value != '') {
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
		}else {
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
		if(e.value != '') {
			fetch(`${baseURL}/api/matterInfo/${e.value}/notFound`)
			.then((response) => response.json())
			.then((data) => {
				if(data.hasOwnProperty('oth_qry')) {
					document.getElementById("otherCaseCount").value = data.oth_case_cnt;
					for (let matter of data.oth_qry) { num++;
						matter_data.innerHTML = `
							<div id="div_other_cases" class="d-block float-start w-100">
								<input type="checkbox" class="d-block float-start me-2 cbxInpt" name="oth_case_row${num}"/>
								<input type="text" class="form-control d-block float-start w-94" name="oth_case_no_subj_desc<?= $i+1 ?>" value="${matter.case_no_subject_desc}" readonly/>
								<input type="hidden" name="oth_row_no${num}" value="${matter.oth_row_no}" readonly/>
								<input type="hidden" name="oth_case_no${num}" value="${matter.oth_case_no}" readonly/>
								<input type="hidden" name="oth_subj_desc${num}" value="${matter.oth_subj_desc}" readonly/>
							</div>
						`;
					}
				}
			});
		}
    }

	function myFinalBillSerial(e) {
		 
		if(e.value != '') {
			let bill_year = document.getElementById("billYear").value; 
			fetch(`${baseURL}/api/FinalBillSerial/${e.value}/${bill_year}`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				let realised_amount = data.realised_amount;
				let num_row = data.num_row;
				if(realised_amount > 0){
					Swal.fire({
						icon: 'info',
						html: '<strong> Bill has already been realised </strong>'
					})
				} else if(num_row == null) {
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
		}else {
			e.value = '';
			document.getElementById('billYear').value = '';
			Swal.fire({
				icon: 'info',
				html: '<strong> No Record Found !! </strong>'
			})
        }
    }

	function selectAll_from()
	{
		var rowNo = document.billCopying2.row_counter1.value;
		for(var i=1; i<=rowNo; i++)
		{
			document.getElementById("copy_ind"+i).checked = true;
		}
	}

	function deSelectAll_from()
	{
		var rowNo = document.billCopying2.row_counter1.value;
		for(var i=1; i<=rowNo; i++)
		{
			//if(document.f1.grant_bill[0].checked == true;
			document.getElementById("copy_ind"+i).checked=false;
		}
	}
	function selectAll_to()
	{
		var rowNo = document.billCopying3.row_counter2.value;
		for(var i=1; i<=rowNo; i++)
		{
			document.getElementById("new_copy_ind_i"+i).checked=true;
		}
	}

	function deSelectAll_to()
	{
		var rowNo = document.billCopying3.row_counter2.value;
		for(var i=1; i<=rowNo; i++)
		{
			//alert('abc');
			//if(document.f1.grant_bill[0].checked == true;
			document.getElementById("new_copy_ind_i"+i).checked=false;
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

			if(billNo == 'billCopying2') {
				document.getElementById('billCopying3').classList.remove("d-none");
				document.getElementById('BilCpyprcdBtn2').disabled = true; 
				document.billCopying3.row_counter2.value = response.count;
				document.getElementById("slctAll2").checked =  (response.count > 0) ? true : false;
				
				for (let record of response.records) {
					tr += `
						<tr>
							<td id="Ctd${key}" class="d-none">
								<input type="hidden" name="ok_ind${key}" value="" readonly />
								<input type="hidden" name="srl_no${key}" value="${record.serial_no}" readonly />
							</td>
							<td> <input type="text" class="form-control" name="date${key}" value="${record.date}" readonly /> </td>
							<td class="brkwrd"> <textarea name="details${key}" class="form-control" readonly>${(record.details) ? record.details : ''}</textarea> </td>
							<td> <input type="text" class="form-control" name="amount${key}" value="${record.amount}" readonly /> </td>
							<td class="w-2 text-center" style="width:2%;"> <input type="checkbox" name="new_copy_ind_i${key}" id="new_copy_ind_i${key}" checked /> </td>
						</tr>
					`; key++;
				} document.getElementById('2ndThead').innerHTML = tr;

				$('.myform').children().unwrap().wrapAll("<form class='frmmm' action='' method='post'></form>");
				$('.frmDv1').unwrap();
				document.getElementById('submitBtn').classList.remove("d-none");
				

			} else {
				document.getElementById('billCopying2').classList.remove("d-none");
				document.getElementById('BilCpyprcdBtn').disabled = true;
				document.getElementById("cur_date").value = response.cur_date;
				document.billCopying2.row_counter1.value = response.count;
				document.getElementById("slctAll").checked =  (response.count > 0) ? true : false;

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
							<td><input type="text" class="form-control" name="activity_date${key}" value="${dateFormat(record.activity_date, 'MM-dd-yyyy')}" readonly /></td>
							<td><input type="text" class="form-control" name="counsel_code${key}" value="${record.counsel_code}" readonly /></td>
							<td><textarea class="form-control" name="activity_desc${key}" readonly> ${(record.activity_desc) ? record.activity_desc : ''} </textarea></td>
							<td class="text-center w-7"><input type="text" class="form-control text-center" name="io_ind${key}" value="${record.io_ind}" readonly /></td>
							<td><input type="text" class="form-control" name="billed_amount${key}" value="${record.billed_amount}" readonly /></td>
							<td class="text-center"><input type="checkbox" class="cbxInpt" name="copy_ind${key}" id="copy_ind${key}" value="Y" checked /></td>
						</tr>
					`; key++;
				} document.getElementById('1stThead').innerHTML = tr;
			}
		});
	}

	function chkEntryDate(fld) {
		if(fld.value != "") 
		{ 
			make_date(fld); 
			dateValid(fld,document.getElementById("cur_date"),'L',"Entry Date","Current Date");
		} 
	}

	function chkBriefDate(fld,n) {
		// if(document.f1.user_option.value == 'Select')
		// {
		// 	if(fld.value == "") { alert('Brief Date : ',1); fld.focus(); return false; }
		// 	//addRow(n);
		// }
		if(fld.value != "")
		{ 
		make_date(fld);
		dateValid(fld,document.getElementById("cur_date"),'L',"Brief Date","Current Date")
		}
	}

	function chkMemoDate(fld) {
		if(fld.value != "") 
		{ 
			make_date(fld); 
			dateValid(fld,document.getElementById("cur_date"),'L',"Memo Date","Current Date");
		} 
	}

	function chkInstrumentDate(fld) {
		if(fld.value != "") 
		{ 
			make_date(fld); 
			dateValid(fld,document.getElementById("cur_date"),'L',"Instrument Date","Current Date");
		} 
	}

	function validateNumber(item, str, decimal) { 
		if(isNaN(item.value) == true) {
			//alert(item.value);
			alert(str+' should be a valid Number ........');
			item.select();
			item.focus();
			return false;
		} else {
		   if(item.value <= 0 ) {
			   alert(str+' should be a Positive .........');
			   item.value = '';
			   item.focus();
			   return false;
		   }
		}
		format_number(item, decimal);
		return true;
	
	}

	function chkCounselFeeRecd(fld,n)
	{
		if(fld.value != ""){ validateNumber(fld, "Counsel Fee Receive : ",2); }
		calc_total_counsel_recd();
	}

	function chkClerkFeeRecd(fld,n)
	{
		if(fld.value != ""){ validateNumber(fld, "Clerk Fee Receive : ",2); }
		calc_total_clerk_recd();
	}


	function chkPeonFeeRecd(fld,n)
	{
		if(fld.value != ""){ validateNumber(fld, "Peon Fee Receive : ",2); }
		calc_total_peon_recd();
	}

	function calc_total_counsel() {
      var total_row = document.f1.row_counter.value;
      var counsel_total = 0;
      for(i=1; i <= total_row; i++)
      {
        voucher_ok_ind = eval("document.f1.voucher_ok_ind"+i+".value");
		//console.log(voucher_ok_ind);
        if(voucher_ok_ind=='Y')
        {
          counsel_total = counsel_total + eval("document.f1.counsel_fee"+i+".value")*1;
        }
      }
      counsel_total = parseFloat(counsel_total).toFixed(2);
      document.f1.counsel_fee.value = counsel_total;      
  	}

	function calc_total_clerk() {
		var total_row = (document.f1.row_counter.value)*1;
		var clerk_total = 0;
		for(i=1; i <= total_row; i++)
		{
			voucher_ok_ind = eval("document.f1.voucher_ok_ind"+i+".value");
			if(voucher_ok_ind=='Y')
			{
			clerk_total = clerk_total + eval("document.f1.clerk_fee"+i+".value")*1;
			}
		}
		clerk_total = parseFloat(clerk_total).toFixed(2);
		document.f1.clerk_fee.value = clerk_total;      
	}
  
	function calc_total_peon() {
		var total_row = (document.f1.row_counter.value)*1;
		var peon_total = 0;
		for(i=1; i <= total_row; i++)
		{
			voucher_ok_ind = eval("document.f1.voucher_ok_ind"+i+".value");
			if(voucher_ok_ind=='Y')
			{
			peon_total = peon_total + eval("document.f1.peon_fee"+i+".value")*1;
			}
		}
		peon_total = parseFloat(peon_total).toFixed(2);
		document.f1.peon_fee.value = peon_total;      
	}
//-------------------------------------------------  
    function calc_total_counsel_recd() {
		var total_row = (document.f1.row_counter.value)*1;
		var counsel_total_recd = 0;
		for(i=1; i <= total_row; i++)
		{
			voucher_ok_ind = eval("document.f1.voucher_ok_ind"+i+".value");
			if(voucher_ok_ind=='Y')
			{
			counsel_total_recd = counsel_total_recd + eval("document.f1.counsel_fee_recd"+i+".value")*1;
			}
		}
		counsel_total_recd = parseFloat(counsel_total_recd).toFixed(2);
		document.f1.counsel_fee_recd.value = counsel_total_recd;      
	}

	function calc_total_clerk_recd() {
		var total_row = (document.f1.row_counter.value)*1;
		var clerk_total_recd = 0;
		for(i=1; i <= total_row; i++)
		{
			voucher_ok_ind = eval("document.f1.voucher_ok_ind"+i+".value");
			if(voucher_ok_ind=='Y')
			{
			clerk_total_recd = clerk_total_recd + eval("document.f1.clerk_fee_recd"+i+".value")*1;
			}
		}
		clerk_total_recd = parseFloat(clerk_total_recd).toFixed(2);
		document.f1.clerk_fee_recd.value = clerk_total_recd;      
	}
	
	function calc_total_peon_recd() {
		var total_row = (document.f1.row_counter.value)*1;
		var peon_total_recd = 0;
		for(i=1; i <= total_row; i++)
		{
			voucher_ok_ind = eval("document.f1.voucher_ok_ind"+i+".value");
			if(voucher_ok_ind=='Y')
			{
			peon_total_recd = peon_total_recd + eval("document.f1.peon_fee_recd"+i+".value")*1;
			}
		}
		peon_total_recd = parseFloat(peon_total_recd).toFixed(2);
		document.f1.peon_fee_recd.value = peon_total_recd;      
	}


	
	// function calc_newtax_percent(rno) {
	// 	var basamt  = eval("document.f1.counsel_fee"+rno+".value") * 1 ;
	// 	var ntaxper = eval("document.f1.new_tax_percent"+rno+".value") * 1 ;

	// 	if (ntaxper > 0) 
	// 	{
	// 		var ntaxamt = (basamt*ntaxper/100) ; eval("document.f1.new_tax_amount"+rno+".value = '"+ntaxamt+"'");  format_number(eval("document.f1.new_tax_amount"+rno),2); 
	// 	}
	// 	else 
	// 	{
	// 		var ntaxamt = '' ; eval("document.f1.new_tax_amount"+rno+".value = '"+ntaxamt+"'"); 
	// 	}

	// 	calc_newcess_percent(rno) ;
	// }

	function calc_newtax_amount(rno) {
		var ntaxamt = eval("document.f1.new_tax_amount"+rno+".value") * 1 ;
		if (ntaxamt > 0) { format_number(eval("document.f1.new_tax_amount"+rno),2); }

		calc_newcess_percent(rno) ;
	}

	//-------------------------------------------------
	function calc_newcess_percent(rno) {
		var ntaxamt  = eval("document.f1.new_tax_amount"+rno+".value") * 1 ;
		var ncessper = eval("document.f1.new_tax_cess_percent"+rno+".value") * 1 ;

		if (ncessper > 0) 
		{
			var ncessamt = (ntaxamt*ncessper/100) ; eval("document.f1.new_tax_cess_amount"+rno+".value = '"+ncessamt+"'");  format_number(eval("document.f1.new_tax_cess_amount"+rno),2); 
		}
		else 
		{
			var ncessamt = '' ; eval("document.f1.new_tax_cess_amount"+rno+".value = '"+ncessamt+"'"); 
		}

		//---
		calc_newhecess_percent(rno) ;
	}
	function calc_newcess_amount(rno) {
	  var ncessamt = eval("document.f1.new_tax_cess_amount"+rno+".value") * 1 ;
	  if (ncessamt > 0) { format_number(eval("document.f1.new_tax_cess_amount"+rno),2); }
	
	  calc_newhecess_percent(rno) ;
	}
	
	function calc_newhecess_percent(rno) {
	  var ntaxamt  = eval("document.f1.new_tax_amount"+rno+".value") * 1 ;
	  var nhcesper = eval("document.f1.new_tax_hecess_percent"+rno+".value") * 1 ;
	
	  if (nhcesper > 0) 
	  {
		 var nhcesamt = (ntaxamt*nhcesper/100) ; eval("document.f1.new_tax_hecess_amount"+rno+".value = '"+nhcesamt+"'");  format_number(eval("document.f1.new_tax_hecess_amount"+rno),2); 
	  }
	  else 
	  {
		 var nhcesamt = '' ; eval("document.f1.new_tax_hecess_amount"+rno+".value = '"+nhcesamt+"'"); 
	  }
	
	  //---
	  calc_row_total_tax(rno) ;
	}
	
	//-------------------------------------------------
	function calc_newhecess_amount(rno) {
	  var nhcessamt = eval("document.f1.new_tax_hecess_amount"+rno+".value") * 1 ;
	  if (nhcessamt > 0) { format_number(eval("document.f1.new_tax_hecess_amount"+rno),2); }
	  //---
	  calc_row_total_tax(rno) ;
	}

	function calc_row_total_tax(rno) {
		var ntaxper   = eval("document.f1.new_tax_percent"+rno+".value") * 1 ;
		var ncessper  = eval("document.f1.new_tax_cess_percent"+rno+".value") * 1 ;
		var nhcessper = eval("document.f1.new_tax_hecess_percent"+rno+".value") * 1 ;
		var nttaxper  = ntaxper + ((ntaxper*ncessper/100).toFixed(3))*1 + ((ntaxper*nhcessper/100).toFixed(3))*1 ;  
		if (nttaxper > 0) { eval("document.f1.new_tax_total_percent"+rno+".value = '"+nttaxper+"'"); format_number(eval("document.f1.new_tax_total_percent"+rno),3); }
		
		var ntaxamt   = eval("document.f1.new_tax_amount"+rno+".value") * 1 ;
		var ncessamt  = eval("document.f1.new_tax_cess_amount"+rno+".value") * 1 ;
		var nhcessamt = eval("document.f1.new_tax_hecess_amount"+rno+".value") * 1 ;
		var nttaxamt  = Math.round(ntaxamt + ncessamt + nhcessamt) ;
		if (nttaxamt > 0) { eval("document.f1.new_tax_total_amount"+rno+".value = '"+nttaxamt+"'"); format_number(eval("document.f1.new_tax_total_amount"+rno),2); }

		calc_row_total(rno) ;
		calc_total_counsel();
		calc_total_clerk();
		calc_total_peon();
	}

	function calc_row_total(rno) {
		var cnslamt   = eval("document.f1.counsel_fee"+rno+".value") * 1 ;
		var clrkamt   = eval("document.f1.clerk_fee"+rno+".value") * 1 ;
		var peonamt   = eval("document.f1.peon_fee"+rno+".value") * 1 ;

		var nttaxamt  = eval("document.f1.new_tax_total_amount"+rno+".value") * 1 ;
		var rtotalamt = cnslamt + clrkamt + peonamt + nttaxamt ;
		if (rtotalamt > 0) { eval("document.f1.gross_amount"+rno+".value = '"+rtotalamt+"'"); format_number(eval("document.f1.gross_amount"+rno),2); }
	}

	function myqtrno(e) {
		 
		if(e.value != '') {
			let finc_year = document.getElementById("finYear").value; 
			let brch_code = document.getElementById("branchCode").value;
			var qtrsdt = '' ;
		 	var qtredt = '' ; 
			fetch(`${baseURL}/api/returnNo/${e.value}/${brch_code}/${finc_year}`)
			.then((response) => response.json())
			.then((data) => {
				console.log('============================> ');
				console.log(data);
				let num_row = data.num_row;
				if(num_row == null) {
					e.value = '';
					Swal.fire({
						icon: 'info',
						html: '<strong> TDS Return No not yet Entered ... Please enter it FIRST ... </strong>'
					})
				} else {
					if (e.value == 1)       { qtrsdt = '01-04-' + finc_year.substr(0,4) ;  qtredt = '30-06-' + finc_year.substr(0,4) ; }
					else if (e.value == 2)  { qtrsdt = '01-07-' + finc_year.substr(0,4) ;  qtredt = '30-09-' + finc_year.substr(0,4) ; }
					else if (e.value == 3)  { qtrsdt = '01-10-' + finc_year.substr(0,4) ;  qtredt = '31-12-' + finc_year.substr(0,4) ; }
					else if (e.value == 4)  { qtrsdt = '01-01-' + finc_year.substr(5,4) ;  qtredt = '31-03-' + finc_year.substr(5,4) ; }
		 
		 			document.getElementById("startDate").value = qtrsdt ;
					document.getElementById("endDate").value = qtredt ; 
					document.getElementById("tdsReturnNo").value = data.tds_return_no;
				}
			});
		}else {
			e.value = '';
			document.getElementById('billYear').value = '';
			Swal.fire({
				icon: 'info',
				html: '<strong> No Record Found !! </strong>'
			})
        }
    }

	
	$(".readonly").on('keydown paste focus mousedown', function(e){
		if(e.keyCode != 9) // ignore tab
			e.preventDefault();
	});

	function checkBoxValidation () {
        if ($('#listTable .child-checkbox:checked').length > 0) {
            $("#btnLaser").prop("disabled", false);
        } else {
            $("#btnLaser").prop("disabled", true);
        }
	} checkBoxValidation();

	$(document).ready(function() {
		$("#listTable .child-checkbox").click(function() {
			checkBoxValidation();
		});
    });
	
function case_type_no_year(fld, user_option) {
	document.all.case_type_value.value = document.all.case_type_code[document.all.case_type_code.selectedIndex].text;
	var f1 = document.getElementById("caseTypeCode").value
	var f11 = document.getElementById("caseTypeValue").value
	var f2 = document.getElementById("caseNo").value
	var f3 = document.getElementById("caseYear").value
	var flag = 'Y';
	if (flag == "Y" && user_option != "View") {
		if (f3 != '') {
			document.getElementById("matterDesc1").value = f11 + " " + f2 + "  " + f3;
			document.getElementById("caseYear").value = f3;

		}
		else {

			document.getElementById("matterDesc1").value = f11 + " " + f2;
		}
	}
}
function proceedOn() {
	var matterCode = document.getElementById("matterCode").value;
	var action = document.getElementById("pageMode").value;
	document.getElementById("proceed").href = "/sinhaco/master/matter-masteraddedit/" + action + "?code_code=" + matterCode;

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