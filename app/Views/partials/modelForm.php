<?php
 
 switch ($model) {
	case 'lookup': ?>
			<div class="modal fade dtatbl" id="lookup">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content" id="lookupHistoryContent"> </div>
					<div class="modal-content" id="lookupTableContent">
						<div class="modal-header">
							<h5 class="modal-title float-start" id="lookupHeading"> Loading... </h5>  
							<span class="close float-end border rounded" data-dismiss="modal">X</span>
						</div>
						<div class="modal-body">
							<div class="sec-bdr border-0" id="dataSection">
							    <div class="d-inline-block w-100">
								    <div class="col-md-12 mt-2 float-end">
									<form onsubmit="searchData(event)" id="searchForm">
									    <nav class="w-48 float-start me-0 mt-2">
                                            <ol class="breadcrumb"> <li class="breadcrumb-item" id="searchResult"> </li> </ol>
                                         </nav>
                                         <div class="input-group srchtop w-25 float-start me-1">
											<select class="form-select" id="selectOptions">
											    <option value="">--- Select Any---</option>
											</select>
										</div>
										<div class="input-group srchtop w-30 float-end me-0">
											<div class="form-outline">
												<input type="hidden" id="searchInfo" value="" class="form-control d-none">
												<input type="search" id="query" class="form-control" placeholder="search here..">
											</div>
											<button type="submit" class="btn btn-primary">
												<i class="fas fa-search"></i>
											</button>
										</div>
									</form>
								</div>
								</div>	
								<div class="col-md-12">
									<div class="tblMn scrltbl mt-3 table-scroll">
										<table class="table table-bordered tblmn w-100" id="lookupTable"></table>
									</div>
								</div>
								<div class="d-inline-block w-100 mb-1">
								<div class="d-nline-block w-50 text-start float-start">
									<div class="pagntnSec d-nline-block w-100">
										<span class="d-block float-start me-2 mt-2 gotoPge">Enter Page No: </span>
										<form onsubmit="searchData(event, true)" class="d-block float-start w-35">
											<input type="number" id="pg" min="1" class="form-control rounded-0 d-block float-start w-65" required="">
											<button type="submit" class="btn btn-primary" style="margin-left: 10px;border-radius: 0;padding: 6px 8px;"> Go </button>
										</form>   
									</div>
								</div>
								<div class="d-nline-block w-50 text-end float-start">
									<div class="pagntnSec d-nline-block w-100">
										<a onclick="changePage('first')"> <i class="fas fa-angle-double-left fnt14" title="First"></i> </a>
										<a onclick="changePage('prev')"> <i class="fas fa-angle-left fnt14 ms-2" title="Previous"></i> </a>
										<span class="mx-3">Page <b class="crntpg" id="presentPage"> ... </b> of <b id="totalPage"> ... </b></span>
										<a onclick="changePage('next')"> <i class="fas fa-angle-right fnt14 me-2" title="Next"></i> </a>
										<a onclick="changePage('last')"> <i class="fas fa-angle-double-right fnt14" title="Last"></i> </a>
									</div>
								</div>
								</div>
		
							</div>
						</div>
					</div>
				</div>
			</div> 
	<script>
	/************************ lookup script code ****************************/  
	
	function getParamsValue(params = '') {
		let flag = false;
		if (params.indexOf('&') !== -1 || params.indexOf('@') !== -1) {
			flag = true; const regex = /@([^&]*)/g; 
			const matches = params.match(regex);
			if(matches != null) {
				const elementsID = matches.map(match => match.substring(1));
				for(let elementID of elementsID) {
					try {
						if(elementID.indexOf("|") != -1) tagValue = elementID.substring(elementID.indexOf("|") + 1);
						else tagValue = document.getElementById(elementID).value;
						params = params.replace('@'+elementID, tagValue);
					} catch (error) {}
				}
			}
		}
		return {'value' : params, 'isModified': flag};
	}

	function showData (property, params, tagId, elements, properties, selectCase, query = '', pg = 1) {
		document.getElementById('lookupHistoryContent').style.display = 'none';
		document.getElementById('lookupTableContent').style.display = 'block';
		document.getElementById('lookupTable').innerHTML = '<div class="spinner-border text-primary loader" role="status"><span class="sr-only">Loading...</span></div>';
		// Code to set search form Display_id
		if (query == '') document.getElementById('query').value = '';
		document.getElementById('searchInfo').value = `${property}, ${params}, ${tagId}, ${elements}, ${properties}, ${selectCase}`;

		let rawParams = params.split("&"); 
		if(rawParams[0].split('=').length > 1) params = getParamsValue(params).value;
		else params = `display_id=${params}`; 

        document.getElementById('lookupHeading').innerText = 'Loading...';
        
        // pagination data at footer
        document.getElementById('totalPage').innerText = '...';
        document.getElementById('presentPage').innerText = '...'; document.getElementById('pg').max =  0;
    	document.getElementById('searchResult').innerHTML = `
    	    <span class="float-start"> Page No. ... of ... </span> <span class="pgeCunt"> Total ... Results Found  </span> `;
        	
		fetch(`${baseURL}/api/lookup?${params}&search_val=${query}&pg=${pg}`)
		.then((response) => response.json())
		.then((data) => {
			console.log(data);
			document.getElementById('lookupHeading').innerText = data.heading;
        
        	// pagination data at footer
        	document.getElementById('totalPage').innerText =  data.totalPage;
        	document.getElementById('presentPage').innerText =  data.pg;
        	document.getElementById('pg').max =  data.totalPage;
        	document.getElementById('searchResult').innerHTML =  `
        	    <span class="float-start"> Page No. ${data.pg} of ${data.totalPage} </span> 
                <span class="pgeCunt"> Total ${data.totalRecords} Results Found  </span>
        	`;
			        
			var tr = "<tbody>", th = "<thead> <tr><th></th>"; let selectOptions = '<option value="">--- Select Any---</option>', pIndex = 0;
			
			for (let heading of data.th) {
			    th = th + `<th class="fw-normal"> ${heading} </th>`;
			    selectOptions += `<option value="${data.searchParams[pIndex]}" ${(data.selectedOption == data.searchParams[pIndex]) ? 'selected' : ''}> ${heading} </option>`; 
			    pIndex++;
			}
			document.getElementById('selectOptions').innerHTML = selectOptions;
			th = th + '</tr> </thead>';
			console.log(th);
			
			if(data.td.length != 0) {
				for (let obj of data.td) {
					let row = '', td = '', lookupDataObj = {[tagId]: obj[property]};

					for(let index in properties) lookupDataObj[elements[index]] = obj[properties[index]]; 
					row = row + `<tr><td class="text-center"><input type="radio" class="cstm-inpt-rdo" value='${JSON.stringify(lookupDataObj)}' onclick="listData(this); showHistoryBtn(null, '${selectCase}'); makeFocus(this);" data-dismiss="modal"/></td>`;
					for (let dt in obj)  td = td + `<td> ${obj[dt]} </td>`;

					row = row + td + '</tr>'; tr = tr + row;
				}
            } else tr = `<td colspan="${data.th.length + 1}" class="text-center"> No Records Found !! </td>`; 

			let lookupTable = th + tr + "</tbody>";
			document.getElementById('lookupTable').innerHTML = lookupTable;
		});
	}

	function makeFocus(e) {
		let str = e.value.split(',')[0];
		if (str[str.length - 1] != '}') str = str + '}';
		let obj = JSON.parse(str);
		let id = Object.keys(obj)[0];
		document.getElementById(id).focus();
	}

	function listData(e) {
		let lookupData = JSON.parse(e.value);
		for(let data in lookupData) document.getElementById(data).value = lookupData[data];
	}

	function cleanData(e, tagId = '', validate = null, lookup = '') {
		if(tagId != '') {
			let tag = document.getElementById(tagId); tag.value = '';
			let tagstr = tag.nextElementSibling.onclick.toString();
			const regex = /\[([^\]]+)\]/g; const matches = tagstr.match(regex);
			
			if (matches) {
				const extractedStrings = matches.map(match => {
					return match.substring(2, match.length - 2); // Remove the square brackets
				});
				for(let index = 0; index < extractedStrings.length/2; index++) document.getElementById(extractedStrings[index]).value = '';
			}
		}

		if(validate != null) {
			lookup = document.getElementById(lookup);
			selectedValue = (e.value == '') ? '_' : e.value;
			if(validate.split("&").includes(selectedValue)) lookup.classList.add("d-none");
			else lookup.classList.remove("d-none");

		}
	}

	function fetchData(e, query, elements, property, selectCase, params = '') {
		if(e.value != ''){
			params = getParamsValue(params);
			console.log(params);
	
			if (params.isModified) params = `code=${e.value}&${params.value.replaceAll('%', '@All')}`;
			else params = `code=${e.value}`;
        	fetch(`${baseURL}/api/lookup/${selectCase}/${params}`)
        	.then((response) => response.json())
        	.then((data) => {
           	 	console.log(data);
            	if(data.length) {
                	for (index in elements) {
                   	 	for (let obj in data[0]) {
                        	if(property[index] == obj) document.getElementById(elements[index]).value = data[0][obj];
                    	}
                	}
           		} else {
                	e.value = '';
                	for (index in elements) document.getElementById(elements[index]).value = '';
                	Swal.fire({
                    	icon: 'info',
                    	html: '<strong> No Record Found !! </strong>'
                	})
           		}
        	});
    	} else for (let index of elements) {
			try {
				document.getElementById(index).value = '';
			} catch (error) {}
		}
	}

	function searchData(e, jumpToPage = false) {
    	e.preventDefault();
    
    	let searchInfo = document.getElementById('searchInfo').value;
    	let query = document.getElementById('query').value;
        let filterValue = document.getElementById('selectOptions').value;
        query = filterValue + '@' + query; if(filterValue == '' && query == '@') {  query = ''; }
        
		console.log(searchInfo);

    	let params = searchInfo.split(', ');
    	if (jumpToPage) {
        	let pg = document.getElementById('pg').value;
        	showData (params[0], params[1], params[2], params[3].split(','), params[4].split(','), params[5], query, pg);
        } else showData (params[0], params[1], params[2], params[3].split(','), params[4].split(','), params[5], query);
    }

	function changePage(page) {
        let searchInfo = document.getElementById('searchInfo').value;
    	let params = searchInfo.split(', ');
    
        let totalPage = parseInt(document.getElementById('totalPage').innerText);
        let presentPage = parseInt(document.getElementById('presentPage').innerText);
    	let query = document.getElementById('query').value;
    	let selectedOption = document.getElementById('selectOptions').value;
    	
    	if(query != '') query = selectedOption + '@' + query;
    	
    	switch (page) {
        	case 'first': showData (params[0], params[1], params[2], params[3].split(','), params[4].split(','), params[5], query, 1);
        		break;
        
            case 'prev': showData (params[0], params[1], params[2], params[3].split(','), params[4].split(','), params[5], query, presentPage - 1);
        		break;
        
            case 'next': showData (params[0], params[1], params[2], params[3].split(','), params[4].split(','), params[5], query, presentPage + 1);
        		break;
        
            case 'last': showData (params[0], params[1], params[2], params[3].split(','), params[4].split(','), params[5], query, totalPage);
        		break;
        }
    }


	//Sylvester//
	function duplicate_code_check(main_ac_code,user_option,mode) {
		
			if(mode == 'getTotNameCount') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				if(data.totalRow == 1) {
					alertMSG('info', '<strong> A/C Code Already Exist</strong>');
					document.getElementById("submit").disabled=true;
				} 
				if(data.totalRow == 0) 
				{ 
					
					document.getElementById("submit").disabled=false;
				}
				if(data.max_id!='')
				{
					
					value=parseInt(data.max_id)+1;
					document.getElementById("clientCode").value=value.toString().padStart(5, '0');
				}
			});
			}
			if(mode == 'getAcode') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				if(data.totalRow == 1) {
					alertMSG('info', '<strong> Code Already Exist</strong>');
					document.getElementById("submit").disabled=true;
				} 
				if(data.totalRow == 0) 
				{ 
					
					document.getElementById("submit").disabled=false;
				}
				if(data.max_id!='')
				{
					
					value=parseInt(data.max_id)+1;
					document.getElementById("maxCode").value=value.toString();
				}
			});
			}
			if(mode == 'getTotBankCount') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				if(data.totalRow == 1) {
					alertMSG('info', '<strong> Code Already Exist</strong>');
					document.getElementById("submit").disabled=true;
				} 
				if(data.totalRow == 0) 
				{ 
					
					document.getElementById("submit").disabled=false;
				}
				if(data.max_id!='')
				{
					
					value=parseInt(data.max_id)+1;
					//alert(value);
					document.getElementById("bank_code").value=value.toString();
				}
			});
			}
			if(mode == 'getTotCompanyCount') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				if(data.totalRow == 1) {
					alertMSG('info', '<strong> Code Already Exist</strong>');
					document.getElementById("submit").disabled=true;
				} 
				if(data.totalRow == 0) 
				{ 
					
					document.getElementById("submit").disabled=false;
				}
				if(data.max_id!='')
				{
					
					value=parseInt(data.max_id)+1;
					//alert(value);
					document.getElementById("company_code").value=value.toString().padStart(3, '0');
				}
			});
			}
			if(mode == 'getTotBranchCount') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				if(data.totalRow == 1) {
					alertMSG('info', '<strong> Code Already Exist</strong>');
					document.getElementById("submit").disabled=true;
				} 
				if(data.totalRow == 0) 
				{ 
					
					document.getElementById("submit").disabled=false;
				}
				if(data.max_id!='')
				{
					
					value=parseInt(data.max_id)+1;
					//alert(value);
					document.getElementById("branch_code").value=value.toString();
				}
			});
			}
			if(mode == 'getTotEmpCount') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				if(data.totalRow == 1) {
					alertMSG('info', '<strong> Code Already Exist</strong>');
					document.getElementById("submit").disabled=true;
				} 
				if(data.totalRow == 0) 
				{ 
					
					document.getElementById("submit").disabled=false;
				}
				if(data.max_id!='')
				{
					
					value=parseInt(data.max_id)+1;
					//alert(value);
					document.getElementById("employee_id").value=value.toString();
				}
			});
			}
			if(mode == 'getCompanyDetails') {
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
					document.getElementById('pan_no').value=data.pan_no;
					document.getElementById('tan_no').value=data.tan_no;
			});
			}
			if(mode == 'masSubAcc') { 
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
					document.getElementById('subCod'+user_option).value=parseInt(d)+1;
			});
			}
			if(mode == 'getsupNo') { 
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
					document.getElementById('supplier_code').value=parseInt(d)+1;
			});
			}
			if(mode == 'getTaxNo') { 
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
					document.getElementById('tax_code').value=parseInt(d)+1;
			});
			}
			if(mode == 'getactiNo') { 
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
				if(d==null){
					d=0;
				}
					document.getElementById('activity_code').value=parseInt(d)+1;
			});
			}
			if(mode == 'getExpNo') { 
			//   fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}`)
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
				if(d==null){
					d=0;
				}
					document.getElementById('expense_code').value=parseInt(d)+1;
			});
			}
			if(mode == 'getotherMasterNo') { 
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
				if(d==null){
					d=0;
				}
					document.getElementById('other_payee_code').value=parseInt(d)+1;
			});
			}
			if(mode == 'getnameNo') { 
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
				if(d==null){
					d=0;
				}
					document.getElementById('ceo_code').value=parseInt(d);
			});
			}
			if(mode == 'getexpsNo') { 
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
				if(d==null){
					d=0;
				}
					document.getElementById('exps_code').value=parseInt(d)+1;
			});
			}
			if(mode == 'getConsuNo') { 
			fetch(`${baseURL}/api/duplicate_code_check/${main_ac_code}/${user_option}/${mode}/notFound`)

			.then((response) => response.json())
			.then((data) => {
				var d=data.max_id;
				if(d==null){
					d=0;
				}
					document.getElementById('consultant_code').value=parseInt(d)+1;
			});
			}

	}
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
	//end//

	function showHistory(url = '', params = '') {
			params = getParamsValue(params).value;
			document.getElementById('lookupTableContent').style.display = 'none';
			document.getElementById('lookupHistoryContent').style.display = 'block';
			let lookup = document.getElementById('lookupHistoryContent');
			let heading = `
				<div class="modal-header">			
					<h5 class="modal-title float-start" id="lookupHeading"> SINHA AND COMPANY </h5>  
					<span class="close float-end border rounded" data-dismiss="modal">X</span>
				</div>`;
			lookup.innerHTML = heading + `
				<div class="modal-body pb-5">
					<div class="spinner-border text-primary loader" role="status"><span class="sr-only">Loading...</span></div>  
				</div>
			`;
	
			fetch(`${baseURL}/${url}?${params}`)
			.then((response) => response.json())
			.then((response) => {
				if(response.status == 'success') lookup.innerHTML = heading + response.page;
				else lookup.innerHTML = heading + `<h6 class="modal-title text-center p-5"> No Records Found !! </h6>`;
			});
	} 
	
	function showHistoryBtn(e = null, id) {
		try {
			if(e != null) {
				if (e.value != '') document.getElementById(id).style.display = 'block';
				else document.getElementById(id).style.display = 'none';
			} else document.getElementById(id).style.display = 'block';		
		} catch (error) {}
	}
	function mymattercheck(matter_code,user_option,mode) {
		mode="myMatter";
		if(mode == 'myMatter') { 
			fetch(`${baseURL}/api/mymattercheck/${matter_code}/${mode}/notFound`)

			.then((response) => response.json())
			.then((client_qry) => {
				document.getElementById('initial_name').value=client_qry.initial_name;
				document.getElementById('initial_code').value=client_qry.initial_code;
				document.getElementById('court_name').value=client_qry.court_name;
				document.getElementById('status_desc').value=client_qry.pesent_desc;
				document.getElementById('subject_desc').value=client_qry.subject_desc;
				document.getElementById('judge_name').value=client_qry.judge_name;
				document.getElementById('reference_desc').value=client_qry.reference_desc;
				document.getElementById('court_code').value=client_qry.court_code;	
			});
			}
	}
	//Added By Sylvester On 02-02-2024
	function getAddress(code, client_code) {
    // Remove existing rows
    $("#addrTab").find('tr').remove();

    fetch(`${baseURL}/api/getAddress/${code}/${client_code}/notFound`)
        .then((response) => response.json())
        .then((data) => {
			$("#addrTab").append('<tr class="fs-14" ><th class="border wd100"><span>Code</span></th><th class="border w-250"><span>Address Line 1</span></th><th class="border w-250"><span>Address Line 2</span></th><th class="border w-250"><span>Address Line 3</span></th><th class="border w-250"><span>Address Line 4</span></th><th class="border w-150"><span>City</span></th><th class="border w-150"><span>Pin</span></th><th class="border w-150"><span>State</span></th><th class="border w-150"><span>Country</span></th><th class="border w-150"><span>Phone</span></th><th class="border w-150"><span>Fax</span></th><th class="border w-250"><span>Email</span></th></tr>'); 
            for (let i = 0; i < data.count; i++) {
                $("#addrTab").append('<tr id="tabRows" class="fs-14 border-0"><td class="border"><span>' + data[i].address_code + '</span></td><td class="border"><span>' + data[i].address_line_1 + '</span></td><td class="border"><span>' + data[i].address_line_2 + '</span></td><td class="border"><span>' + data[i].address_line_3 + '</span></td><td class="border"><span>' + data[i].address_line_4 + '</span></td><td class="border"><span>' + data[i].city + '</span></td><td class="border"><span>' + data[i].pin_code + '</span></td><td class="border"><span>' + data[i].state_code + '</span></td><td class="border"><span>' + data[i].country + '</span></td><td class="border"><span>' + data[i].phone_no + '</span></td><td class="border"><span>' + data[i].fax_no + '</span></td><td class="border"><span>' + data[i].email_id + '</span></td></tr>');
            }
        });
}
	function getAttention(code,client_code) 
	{
		$("#addrTab2").find('tr').remove();
		fetch(`${baseURL}/api/getAttention/${code}/${client_code}/Found`)
		.then((response) => response.json())
		.then((data) => {
			$("#addrTab2").append('<tr class="fs-14"><th class="border w-250"><span>Attention Name</span></th><th class="border w-250"><span>Designation</span></th><th class="border w-150"><span>Phone</span></th><th class="border w-150"><span>Fax</span></th><th class="border w-150"><span>Mobile</span></th><th class="border w-250"><span>Email</span></th></tr>'); 
			for (let i = 0; i < data.count2; i++) {
				$("#addrTab2").append('<tr id="tabRows2" class="fs-14 border-0"><td class="border"><span>'+ data[i].attention_name +'</span></td><td class="border"><span>'+ data[i].designation +'</span></td><td class="border"><span>'+ data[i].phone_no +'</span></td><td class="border"><span>'+ data[i].fax_no +'</span></td><td class="border"><span>'+ data[i].mobile_no +'</span></td><td class="border"><span>'+ data[i].email_id +'</span></td></tr>'); 
			}
			});
	}
	function getBillDetails() 
	{ 
		//alert(fin_year);
		var	fin_year=document.getElementById('fin_year').value;
		var	bill_no=document.getElementById('bill_no').value;
		fetch(`${baseURL}/api/getBillDetails/${fin_year}/${bill_no}/Found`)
		.then((response) => response.json())
		.then((data) => {
			
			 	if(data.bill_date!=null){document.getElementById('bill_date').value=data.bill_date;}else{document.getElementById('bill_date').value='';}
				if(data.bill_date!=null){document.getElementById('bill_date').value=data.bill_date;}else{document.getElementById('bill_date').value='';}
				if(data.client_name!=null){document.getElementById('client_name').value=data.client_name;}else{document.getElementById('client_name').value='';}
				if(data.address_line_1!=null){document.getElementById('address_line_1').value=data.address_line_1+''+data.address_line_2+''+data.address_line_3+''+data.address_line_4;}else{document.getElementById('address_line_1').value='';}
				if(data.attention_name!=null){document.getElementById('attention_name').value=data.attention_name;}else{document.getElementById('attention_name').value='';}
				if(data.matter_code!=null){document.getElementById('matter_code').value=data.matter_code;}else{document.getElementById('matter_code').value='';}
				if(data.matter_desc){document.getElementById('matter_desc').value=data.matter_desc;}else{document.getElementById('matter_desc').value='';}
				if(data.initial_name!=null){document.getElementById('initial_name').value=data.initial_name;}else{document.getElementById('initial_name').value='';}
				if(data.bill_cause!=null){document.getElementById('bill_cause').value=data.bill_cause;}else{document.getElementById('bill_cause').value='';}
				
				if(data.bill_amount_inpocket!=null){var bill_amount_inpocket=data.bill_amount_inpocket;document.getElementById('bill_amount_inpocket').value=data.bill_amount_inpocket;}else{var bill_amount_inpocket='0.00';document.getElementById('bill_amount_inpocket').value='0.00';}
				if(data.bill_amount_outpocket!=null){var bill_amount_outpocket=data.bill_amount_outpocket;document.getElementById('bill_amount_outpocket').value=data.bill_amount_outpocket;}else{var bill_amount_outpocket='0.00';document.getElementById('bill_amount_outpocket').value='0.00';}
				if(data.bill_amount_counsel!=null){var bill_amount_counsel=data.bill_amount_counsel;document.getElementById('bill_amount_counsel').value=data.bill_amount_counsel;}else{var bill_amount_counsel='0.00';document.getElementById('bill_amount_counsel').value='0.00';}
				if(data.service_tax_amount!=null){var service_tax_amount=data.service_tax_amount;document.getElementById('service_tax_amount').value=data.service_tax_amount;}else{var service_tax_amount='0.00';document.getElementById('service_tax_amount').value='0.00';}
				document.getElementById('bill_amount_total').value=(parseFloat(bill_amount_inpocket)+parseFloat(bill_amount_outpocket)+parseFloat(bill_amount_counsel)+parseFloat(service_tax_amount)).toFixed(2);	
				
				if(data.advance_amount_inpocket!=null){
				document.getElementById('advance_amount_inpocket').value=data.advance_amount_inpocket;	
				}
				else
				{
					document.getElementById('advance_amount_inpocket').value='0.00';
				}
				if(data.advance_amount_outpocket!=null){
				document.getElementById('advance_amount_outpocket').value=data.advance_amount_outpocket;
				}
				else
				{
					document.getElementById('advance_amount_outpocket').value='0.00';
				}
				if(data.advance_amount_counsel!=null){	
				document.getElementById('advance_amount_counsel').value=data.advance_amount_counsel;	
				}
				else
				{
					document.getElementById('advance_amount_counsel').value='0.00';
				}
				if(data.advance_amount_service_tax!=null){
				document.getElementById('advance_amount_service_tax').value=data.advance_amount_service_tax;
				}
				else
				{
					document.getElementById('advance_amount_service_tax').value='0.00';
				}
				if(data.advance_amount_inpocket!=null){ var advance_amount_inpocket=data.advance_amount_inpocket }else{var advance_amount_inpocket='0.00';}
				if(data.advance_amount_outpocket!=null){ var advance_amount_outpocket=data.advance_amount_outpocket}else{advance_amount_outpocket='0.00';} 
				if(data.advance_amount_counsel!=null){ var advance_amount_counsel=data.advance_amount_counsel}else{advance_amount_counsel='0.00';}
				if(data.advance_amount_service_tax!=null){ var advance_amount_service_tax=data.advance_amount_service_tax}else{advance_amount_service_tax='0.00';}
				document.getElementById('advance_amount_total').value=(parseFloat(advance_amount_inpocket)+parseFloat(advance_amount_outpocket)+parseFloat(advance_amount_counsel)+parseFloat(advance_amount_service_tax)).toFixed(2);
				
				if(data.realise_amount_inpocket!=null){var realise_amount_inpocket=data.realise_amount_inpocket;document.getElementById('realise_amount_inpocket').value=data.realise_amount_inpocket;}else{var realise_amount_inpocket='0.00';document.getElementById('realise_amount_inpocket').value='0.00';}
				if(data.realise_amount_outpocke!=null){var realise_amount_outpocke=data.realise_amount_outpocke;document.getElementById('realise_amount_outpocket').value=data.realise_amount_outpocket;}else{var realise_amount_outpocke='0.00';document.getElementById('realise_amount_outpocket').value='0.00';}
				if(data.realise_amount_counsel!=null){var realise_amount_counsel=data.realise_amount_counsel;document.getElementById('realise_amount_counsel').value=data.realise_amount_counsel;}else{var realise_amount_counsel='0.00';document.getElementById('realise_amount_counsel').value='0.00';}
				if(data.realise_amount_service_tax){var realise_amount_service_tax=data.realise_amount_service_tax;document.getElementById('realise_amount_service_tax').value=data.realise_amount_service_tax;}else{var realise_amount_service_tax='0.00';document.getElementById('realise_amount_service_tax').value='0.00';}
				document.getElementById('realise_amount_total').value=(parseFloat(realise_amount_inpocket)+ parseFloat(realise_amount_outpocke) + parseFloat(realise_amount_counsel)+parseFloat(realise_amount_service_tax)).toFixed(2);
								
				if(data.deficit_amount_inpocket!=null){var deficit_amount_inpocket=data.deficit_amount_inpocket;document.getElementById('deficit_amount_inpocket').value=data.deficit_amount_inpocket;}else{var deficit_amount_inpocket='0.00';document.getElementById('deficit_amount_inpocket').value='0.00';}
				if(data.deficit_amount_outpocket!=null){var deficit_amount_outpocket=data.deficit_amount_outpocket;document.getElementById('deficit_amount_outpocket').value=data.deficit_amount_outpocket;}else{var deficit_amount_outpocket='0.00';document.getElementById('deficit_amount_outpocket').value='0.00';}
				if(data.deficit_amount_counsel!=null){var deficit_amount_counsel=data.deficit_amount_counsel;document.getElementById('deficit_amount_counsel').value=data.deficit_amount_counsel;}else{var deficit_amount_counsel='0.00';document.getElementById('deficit_amount_counsel').value='0.00';}
				if(data.deficit_amount_service_tax!=null){var deficit_amount_service_tax=data.deficit_amount_service_tax;document.getElementById('deficit_amount_service_tax').value=data.deficit_amount_service_tax;}else{var deficit_amount_service_tax='0.00';document.getElementById('deficit_amount_service_tax').value='0.00';}
				document.getElementById('deficit_amount_total').value=(parseFloat(deficit_amount_inpocket)+ parseFloat(deficit_amount_outpocket) + parseFloat(deficit_amount_counsel) + parseFloat(deficit_amount_service_tax)).toFixed(2);
				if(data.bill_amount_inpocket){var bill_amount_inpocket=data.bill_amount_inpocket}else{var bill_amount_inpocket='0.00';}
				if(data.advance_amount_inpocket){var advance_amount_inpocket=data.advance_amount_inpocket}else{var advance_amount_inpocket='0.00';}
				document.getElementById('balance_amount_inpocket').value=(parseFloat(bill_amount_inpocket) - parseFloat(advance_amount_inpocket) ).toFixed(2) ;
				if(data.bill_amount_outpocket!=null){var bill_amount_outpocket=data.bill_amount_outpocket}else{var bill_amount_outpocket='0.00';}
				if(data.advance_amount_outpocket){var advance_amount_outpocket=data.advance_amount_outpocket}else{var advance_amount_outpocket='0.00';}
				if(data.realise_amount_outpocket!=null){var realise_amount_outpocket=data.realise_amount_outpocket}else{var realise_amount_outpocket='0.00';}
				if(data.deficit_amount_outpocket!=null){var deficit_amount_outpocket=data.deficit_amount_outpocket}else{var deficit_amount_outpocket='0.00';}
				document.getElementById('balance_amount_outpocket').value=(parseFloat(bill_amount_outpocket) - parseFloat(advance_amount_outpocket) - parseFloat(realise_amount_outpocket) - parseFloat(deficit_amount_outpocket)).toFixed(2) ;
				if(data.bill_amount_counsel!=null){var bill_amount_counsel=data.bill_amount_counsel}else{var bill_amount_counsel='0.00';}
				if(data.advance_amount_counsel!=null){var advance_amount_counsel=data.advance_amount_counsel}else{var advance_amount_counsel='0.00';}
				if(data.realise_amount_counsel!=null){var realise_amount_counsel=data.realise_amount_counsel}else{var realise_amount_counsel='0.00';}
				if(data.deficit_amount_counsel!=null){var deficit_amount_counsel=data.deficit_amount_counsel}else{var deficit_amount_counsel='0.00';}
				document.getElementById('balance_amount_counsel').value=(parseFloat(bill_amount_counsel) - parseFloat(advance_amount_counsel) - parseFloat(realise_amount_counsel) - parseFloat(deficit_amount_counsel)).toFixed(2) ;
				if(data.service_tax_amount!=null){var service_tax_amount=data.service_tax_amount}else{var service_tax_amount='0.00';}
				if(data.advance_amount_service_tax!=null){var advance_amount_service_tax=data.advance_amount_service_tax}else{var advance_amount_service_tax='0.00';}
				if(data.realise_amount_service_tax!=null){var realise_amount_service_tax=data.realise_amount_service_tax}else{var realise_amount_service_tax='0.00';}
				if(data.deficit_amount_service_tax!=null){var deficit_amount_service_tax=data.deficit_amount_service_tax}else{var deficit_amount_service_tax='0.00';}
				document.getElementById('balance_amount_service_tax').value=(parseFloat(service_tax_amount)- parseFloat(advance_amount_service_tax) - parseFloat(realise_amount_service_tax) - parseFloat(deficit_amount_service_tax)).toFixed(2);
				if(data.bill_amount_inpocket!=null){var bill_amount_inpocket=data.bill_amount_inpocket; }else{var bill_amount_inpocket='0.00';}
				if(data.baln_amount_outpocket!=null){var baln_amount_outpocket=data.baln_amount_outpocket}else{var baln_amount_outpocket='0.00';}
				if(data.baln_amount_counsel!=null){var baln_amount_counsel=data.baln_amount_counsel}else{var baln_amount_counsel='0.00';}
				if(data.baln_amount_service_tax!=null){var baln_amount_service_tax=data.baln_amount_service_tax}else{var baln_amount_service_tax='0.00';}
				document.getElementById('balance_amount_total').value=(parseFloat(bill_amount_inpocket) + parseFloat(baln_amount_outpocket) + parseFloat(baln_amount_counsel)  + parseFloat(baln_amount_service_tax)).toFixed(2);
				document.getElementById('displayid').value=document.getElementById('display_id').value;
				document.getElementById('menuid').value=document.getElementById('menu_id').value;
				document.getElementById('queryId').value=document.getElementById('query_id').value;
				document.getElementById('branchcode').value=document.getElementById('branchCode').value;
				document.getElementById('finYear').value=fin_year;
				document.getElementById('billNo').value=bill_no;

			});
	}
	function myRecSelectall(serial_no) 
	{
		fetch(`${baseURL}/api/myRecSelectall/${serial_no}/Found`)
		.then((response) => response.json())
		.then((data) => {
			document.getElementById('serial_no').value=data.serial_no || '';
			document.getElementById('activity_date').value=data.activity_date || '';
			document.getElementById('matter_code').value=data.matter_code|| '';
			document.getElementById('matter_desc').value=data.matter_desc|| '';
			document.getElementById('client_code').value=data.client_code|| '';
			document.getElementById('client_name').value=data.client_name|| '';
			document.getElementById('judge_name').value=data.judge_name|| '';
			document.getElementById('appear_for').value=data.appear_for|| '';
			document.getElementById('prev_date').value=data.prev_date|| '';
			document.getElementById('prev_fixed_for').value=data.prev_fixed_for|| '';
			document.getElementById('next_date').value=data.next_date|| '';
			document.getElementById('next_fixed_for').value=data.next_fixed_for|| '';
			document.getElementById('remarks').value=data.remarks|| '';
			document.getElementById('ref_billinfo_serial_no').value=data.ref_billinfo_serial_no|| '';
			document.getElementById('final_bill_no').value=data.final_bill_no|| '';
			document.getElementById('final_bill_date').value=data.bill_date|| '';
			document.getElementById('letter_no').value=data.letter_no|| '';
			document.getElementById('letter_date').value=data.letter_date|| '';
			document.getElementById('header_desc').value=data.header_desc.replace( /(<([^>]+)>)/ig, '')|| '';
			document.getElementById('status_desc').value=data.status_desc|| '';
			document.getElementById('billable_desc').value=data.billable_desc|| '';
			}); 
	}
	function chkCaseDet(serial_no) 
	{
		fetch(`${baseURL}/api/chkCaseDet/${serial_no}/Found`)
		.then((response) => response.json())
		.then((data) => {
			document.getElementById('serial_no_BP').value=data.serial_no || '';
			document.getElementById('activity_date_BP').value=data.activity_date || '';
			document.getElementById('matter_code_BP').value=data.matter_code|| '';
			document.getElementById('client_code_BP').value=data.client_code|| '';
			document.getElementById('judge_name_BP').value=data.judge_name|| '';
			document.getElementById('appear_for_BP').value=data.appear_for|| '';
			document.getElementById('prev_date_BP').value=data.prev_date|| '';
			document.getElementById('prev_fixed_for_BP').value=data.prev_fixed_for|| '';
			document.getElementById('next_date_BP').value=data.next_date|| '';
			document.getElementById('next_fixed_for_BPss').value=data.next_fixed_for;
			document.getElementById('letter_no_BP').value=data.letter_no|| '';
			document.getElementById('letter_date_BP').value=data.letter_date|| '';
			
			document.getElementById('header_desc_BP').value=data.header_desc.replace(/<p>/g, "").replace(/<\/p>/g, "")|| '';
			document.getElementById('status_desc_BP').value=data.status_desc || '';
			document.getElementById('billable_desc_BP').value=data.billable_desc|| '';
			}); 
	}
	function checkCaseNo()
	{
		var caseNo=document.getElementById('matterDesc1').value;
		var courtCode=document.getElementById('courtCode').value;
		fetch(`${baseURL}/api/checkCaseNo/${caseNo}/Found`)
		.then((response) => response.json())
		.then((data) => {
			if(data.court_code==courtCode)
			{
				alertMSG('info', '<strong>Case Number Already Exist Under This Court</strong>');
				document.getElementById('caseNo').value='';
				document.getElementById('caseYear').value='';
				document.getElementById('matterDesc1').value= '';
				document.getElementById("caseNo").focus();
			}
			}); 
	}
</script>
	<?php break;
	case 'crud': ?>
			<div class="modal fade" id="popupEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal Edit</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					...
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
				</div>
			</div>
			
			<div class="modal fade" id="popupDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal Delete</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					...
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div>
				</div>
			</div>
			
			<div class="modal fade" id="popupView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Modal View</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					...
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
				</div>
			</div>
			
			<div class="modal fade" id="popupAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
					...
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
				</div>
			</div>` 
	<?php break;
}

?>


