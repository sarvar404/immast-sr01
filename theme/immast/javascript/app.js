
var phoneInpTag;
var otpId = "fitem_id_profile_field_otp_num";
var gen_otp_button_id = "gen_otp";
var ver_otp = "ver_otp";
var gen_otp_button;
var dobChekcInp = document.getElementById("id_profile_field_dob_enabled");
var formData = {};
var city = document.getElementById("id_city")
var country =  document.getElementById('id_country')
var state =  document.getElementById('id_state')
// country.addEventListener("change",(e)=>{
    
//     var country_val =  country.options[country.selectedIndex].value;
//     if (country_val !== ""){
//         console.log(country_val)
//     }
// })




if ((dobChekcInp && (window.location.pathname === "/enrol/index.php"))) {
    dobChekcInp.setAttribute('checked', 'checked');
    var formTag = document.querySelector('form');
    var allInpTags = formTag.querySelectorAll('input');
    var allSelectTags = formTag.querySelectorAll('select');
    allInpTags.forEach(element => {
        element.addEventListener('change', (e) => {
            formData[e.target.name] = e.target.value;
            send_data(formData)
        })
    })
    allSelectTags.forEach(element => {
        element.addEventListener('change', (e) => {
            formData[e.target.name] = e.target.options[e.target.selectedIndex].text;
            send_data(formData)
        })
    })


}

function send_data(formData) {
    
    url = "https://courses.immast.org/api/enroll_cap";
    console.log("sending data: ", formData)
    fetch(url, { method: 'POST', credentials: 'include', body: JSON.stringify({formdata:formData,id:document.getElementById("sessvar").value}) }).then((res) => res.json()).then((resdata) => {
        console.log(resdata)
    })

}

if (document.getElementById('id_profile_field_mobile_num')) {
    phoneInpTag = document.getElementById("id_profile_field_mobile_num");
    phoneInpTag.style.marginBottom = "5px"
    phoneInpTag.style.flexGrow = "1"
    phoneDiv = phoneInpTag.parentNode;
    phoneDiv.style.position = "relative"
    phoneDiv.querySelector("#id_error_profile_field_mobile_num").style.position = "absolute"
    phoneDiv.querySelector("#id_error_profile_field_mobile_num").style.bottom = "-15px"
    phoneDiv.querySelector("#id_error_profile_field_mobile_num").style.left = "10px"
    phoneDiv.classList.remove('align-items-start')
    // phoneDiv.classList.add('align-items-center')

    html = `
    <div id="${otpId}" class="form-group row fitem has-danger" >
    <div class="col-md-3 col-form-label d-flex pb-0 pr-md-0" >
                <label class="d-inline word-break " for="id_profile_field_otpnum">
                    Enter OTP.
                </label>
        
        <div class="form-label-addon d-flex align-items-center align-self-start">
                <div class="text-danger" title="Required">
                <i class="icon fa fa-exclamation-circle text-danger fa-fw " title="Required" aria-label="Required"></i>
                </div>
                
                </div>
    </div>
    <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text" style="display:flex;flex-grow:1;position:relative;">
        <input type="text" class="form-control is-invalid" style="margin-bottom:10px;" name="id_profile_field_otpnum" id="id_profile_field_otpnum" aria-describedby="id_error_profile_field_mobile_num" aria-invalid="true">
        <div class="form-control-feedback invalid-feedback" id="id_error_profile_field_mobile_num" style="position:absolute; bottom:-15px;left:10px; " tabindex="0">
        - Required</div>
        <button id="${ver_otp}" class="form-group  fitem" onclick="javascript: verify_otp(); return false;" style="margin-left:5px;" >
        Verify OTP
        </button>
        </div>
        
    </div>
    `




    if (document.querySelector('#id_category_2') && (window.location.pathname === "/login/signup.php")) {
        phoneDiv.innerHTML += `<button id="${gen_otp_button_id}" class="form-group  fitem" onclick="javascript: gen_otp_funct(); return false;" style="margin-left:10px;">
            Get OTP
            </button>`
        // var pincode = `
        // <div style="display:flex;align-items:center;justify-content:space-between;">
        // <label class="d-inline word-break " for="id_pin_code">
        // Enter Pincode
        // </label>
        // <div class="col-md-9 form-inline align-items-start felement" data-fieldtype="text" style="display:flex;flex-grow:1;position:relative; ">
        //  <input type="text" class="form-control is-invalid" style="margin-bottom:10px;" name="id_pin_code" id="id_pin_code" aria-describedby="id_error_profile_field_mobile_num" aria-invalid="true">
        // <div class="form-control-feedback invalid-feedback" id="id_error_profile_field_mobile_num" style="position:absolute; bottom:-15px;left:10px; " tabindex="0">
        // - Required</div></div>`;
        // <input type="text" class="form-control is-invalid" style="margin-bottom:10px;" name="id_pin_code" id="id_pin_code" aria-describedby="id_error_profile_field_mobile_num" aria-invalid="true"  onblur="javascript: getRegions(this); return false; "  onkeyup="javascript: getRegions(this); return false;">
        var formContainer = document.querySelector("#id_category_2")
        formContainer.innerHTML += html;
        // document.getElementById("fitem_id_lastname").insertAdjacentHTML('afterend', pincode)
        document.getElementById('id_submitbutton').setAttribute('disabled', 'disabled')
        // formContainer.classList.remove('collapsible')  // Remove the Toggleness for Mobile Num
    }

}
/*
function gen_otp_funct() {
    // return
    var aadhar = document.getElementById("id_profile_field_aadhar_no");
    console.log(aadhar.value)
    console.log(aadhar.value.length)
    if ((aadhar.value.match(/[0-9]/g)) && (aadhar.value.length == 12)) {
        // pattern="[A-Za-z]{3}" 
        var passPort = document.getElementById("id_profile_field_passport_no")
        if ((aadhar.value === "") && (passPort.value === "")) {
            alert("Please fill Aadhar/Passport field.")
        }
        else {
            if (passPort.value !== ""){
                if (!checkPassportDate()) {
                    alert("Passport Expiration date must be valid!");
                }else{
			get_otp_handler()	
	
		}
            }
            else {
            	
get_otp_handler();
		
		}
        }
    }
    else {
        alert("Enter valid aadhar number!")
    }


}
*/



function gen_otp_funct() {
    var aadhar      = document.getElementById("id_profile_field_aadhar_no");
    var passPort    = document.getElementById("id_profile_field_passport_no")
    get_otp_handler();
    
    // console.log(aadhar.value)
    // console.log(passPort.value)
    
    // if ((aadhar.value !== "") && (aadhar.value !== null ) && (aadhar.value !== undefined )){
        //     if (( aadhar.value.match(/[0-9]/g)) && (aadhar.value.length == 12)){
            // get_otp_handler();
    //     }else{
    //         alert("Please fill valid aadhar!");
    //     }
    // }else if ((passPort.value !== "") && (passPort.value !== null ) && (passPort.value !== undefined )){
    //     get_otp_handler();
    // }else{
    //     alert("Please fill Aadhar/Passport field.")
    // }
}













function get_otp_handler(){
	
	numInp = document.getElementById("id_profile_field_mobile_num");
    email = document.getElementById("id_email")
    gen_otp_button = document.getElementById(gen_otp_button_id)
    // gen_otp_button.setAttribute('disabled', 'disabled')
    var url = "https://courses.immast.org/api/send_otp"
    console.log(numInp.value)
    console.log(email.value)
    if (numInp.value !== "" && email.value !== "") {
        var countryName = country[country.selectedIndex].text
        gen_otp_button.innerText = "Resend OTP"
        numInp.setAttribute('readonly', 'readonly');
        let xhr = new XMLHttpRequest();
        xhr.open("post", url, async = true)
        xhr.withCredentials = true
        xhr.send(JSON.stringify({ mobileNum: numInp.value,"email":email.value,"country":countryName }))

        xhr.onload = function () {
            if (xhr.status == 200) {
                alert("OTP Sent Successfully.")
            }
        }
    } else {
        alert("Enter valid Phone/Email!")
    }


}


function verify_otp() {
    var ver_inp = document.getElementById("id_profile_field_otpnum")
    if (ver_inp.value !== null) {
        otp = ver_inp.value;
        url = "https://courses.immast.org/api/verify_otp"

        let xhr = new XMLHttpRequest();
        xhr.open("post", url, async = true)
        xhr.withCredentials = true
        xhr.send(JSON.stringify({ "otp": otp }))

        xhr.onload = function () {
            if (xhr.status == 200) {
                if (xhr.responseText.includes('"type":"OK"')) {
                    alert("Mobile/Email Number Verified");
                    document.getElementById('id_submitbutton').removeAttribute('disabled', 'disabled')
                    ver_inp.setAttribute('disabled', 'disabled')
                }
                else {
                    alert("OTP is not correct.");
                }
            }
        }

    }

}

function getRegions(e) {
    if ((e.value !== "") && (e.value.length === 6)) {
        var url = `https://api.postalpincode.in/pincode/${e.value}`
        fetch(url, { method: "GET" }).then((res) => res.json()).then((jsonData) => {
            if (jsonData[0]["Status"] === "Success") {
                var rigon = jsonData[0]["PostOffice"][0]["Region"]
                var state = jsonData[0]["PostOffice"][0]["State"]
                var country = jsonData[0]["PostOffice"][0]["Country"]
                var city = rigon + ", " + state
                document.getElementById("id_city").value = city
                var cn = document.getElementById("id_country");
                options = cn.querySelectorAll('option');
                options.forEach((ele) => {
                    if (country === ele.text) {
                        ele.setAttribute("selected", "selected")
                    }
                })
            }
            else {
                console.log("Enter Correct Pincode")
                document.getElementById("id_city").value = ""
            }

        })
    } else {
        document.getElementById("id_city").value = ""
    }
}


$('div#myNav ul.menu li').on('click', function (e) {
    $(this).children('ul').toggle();
    $(this).siblings('li').find('ul').hide();
    e.stopPropagation();
});


/*
function showHidePreferences(e) {
    // console.log(location.pathname)
    var navList = document.querySelector(".nav.nav-tabs")
    var parentRoot = document.querySelector('.tab-content.mt-3');
    if (parentRoot) {

        setTimeout(() => {
            var link = location.hash;
            if (link) {
                var oldChild = parentRoot.querySelector('.active');
                oldChild.classList.remove('active')
                parentRoot.querySelector(`${link}`).classList.add("active");
                parentRoot.querySelector(`${link}`)
                console.log(parentRoot.querySelector('.active'))
                var oldNavTag = navList.querySelector('a[aria-selected="true"]')
                var newNavTag = navList.querySelector(`a[href="${link}"]`)
                oldNavTag.removeAttribute('aria-selected')
                newNavTag.setAttribute('aria-selected', "true");
                console.log(newNavTag)
                console.log(oldNavTag)
            }
        }, 500);
    }


}

*/

function setCountries(){
    url = "https://courses.immast.org/geoapi/child_geo_name"
    fetch(url,{method:"GET"}).then(res=> res.json()).then(res =>{
        // country.innerHTML += "<option value=''>Select Country</option>"
        Array.from(res["Records"]).forEach((ele)=>{
            html = `<option value="${ele.geo_id}">${ele.geo_name}</option>`;
            country.innerHTML += html
        })
    })
    
}

function setState(geo_id){
    url = `https://courses.immast.org/geoapi/child_geo_name?geo_id=${geo_id}`
    fetch(url,{method:"GET"}).then(res=>res.json()).then(res =>{
        state.innerHTML = "<option value=''>Select State</option>"
        city.innerHTML = "<option value=''>Select State</option>"
        Array.from(res["Records"]).forEach(ele => {
            html = `<option value="${ele.geo_id}">${ele.geo_name}</option>`;
            state.innerHTML += html
            
        })
    })

}

function setCity(geo_id){
    url = `https://courses.immast.org/geoapi/child_geo_name?geo_id=${geo_id}`
    fetch(url,{method:"GET"}).then(res=>res.json()).then(res =>{
        city.innerHTML = "<option value=''>Select City</option>"
        Array.from(res["Records"]).forEach(ele => {
            html = `<option value="${ele.geo_id}">${ele.geo_name}</option>`;
            city.innerHTML += html
        })
        city.innerHTML += "<option value='others'>Others</option>"
    })

}

function checkPassportDate() {
    var passPort_year = document.getElementById("id_profile_field_passport_expiry_year");
    var passPort_day = document.getElementById("id_profile_field_passport_expiry_day");
    var passPort_month = document.getElementById("id_profile_field_passport_expiry_month");
    var currentDate = new Date()
    if ((passPort_day[passPort_day.selectedIndex].text >= currentDate.getDate()) && (passPort_month.selectedIndex >= currentDate.getMonth()) && (passPort_year[passPort_year.selectedIndex].text >= currentDate.getFullYear())) {
        return true
    }
    else {
        return false;
    }
    // console.log(passPort_year[passPort_year.selectedIndex].text)

}
if (country){
    setCountries()
    country.addEventListener("change",(e)=>{
        geo_id  =  country.options[country.selectedIndex].value;
        if (geo_id !== ""){
            setState(geo_id)
        }
    })
    if (state){
        state.addEventListener("change",(e)=>{
            geo_id  =  state.options[state.selectedIndex].value;
            if (geo_id !== ""){
                setCity(geo_id)
            }
        })
    }
}


// showHidePreferences()
