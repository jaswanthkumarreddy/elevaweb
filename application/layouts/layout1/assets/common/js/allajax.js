function checkmcat(str) {
    str = str.replace(/\s{2,}/g, ' ');
    if (str.length == 0) { 
        document.getElementById("main-cat-error").innerHTML = "Enter main category";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("main-cat-error").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "/admin/checkdatabasedata/?id=" + str, true);
        xmlhttp.send();
    }
}
function checkscat(str) {
    str = str.replace(/\s{2,}/g, ' ');
    if (str.length == 0) { 
        document.getElementById("sub-cat-error").innerHTML = "Enter sub category";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("sub-cat-error").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "/admin/checkdatabasedata/sub/?id=" + str, true);
        xmlhttp.send();
    }
}
function mainadd(str) {
    str = str.replace(/\s{2,}/g, ' ');
    if (str.length == 0) { 
        document.getElementById("adding-massage").innerHTML = "Enter main category first";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("adding-massage").innerHTML = xmlhttp.responseText;
                $('.main_input input').val("");
                updatemainchatlist();
            }
        }
        xmlhttp.open("GET", "/admin/checkdatabasedata/mainchatadd/?id=" + str, true);
        xmlhttp.send();
    }
}
function subadd(str,str1) {
    str = str.replace(/\s{2,}/g, ' ');
    if (str == 0) { 
        document.getElementById("adding-massage").innerHTML = "Select main category";
        return;
    }else if (str1.length == 0) { 
        document.getElementById("adding-massage").innerHTML = "Enter sub category";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("adding-massage").innerHTML = xmlhttp.responseText;
                $('.main_input input').val("");
                 $('.sub_input input').val("");
                 updatesubchatlist();
            }
        }
        xmlhttp.open("GET", "/admin/checkdatabasedata/subcatadd/?id=" + str+"&id1="+ str1, true);
        xmlhttp.send();
    }
}
function insert_product_data(formdata){
    $.ajax({
                type: "POST",
                url: "/admin/Checkdatabasedata/insert_product_data/",
                data: formdata,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                     $(".insert-product-error").html(data);
                     $(".loader-ajax").hide();

                }
            });
}
function deleteentry(tablename,id){
     if (tablename.length == 0) { 
        return false;
    }else if (id.length == 0) { 
        return false;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $("#delete-masage").html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "/admin/checkdatabasedata/deleterow/?tablename=" + tablename+"&id="+ id, true);
        xmlhttp.send();
    }
}
function addtocart(id,qnt,prod_id){
     if (id.length == 0) { 
        return false;
    }else if (qnt.length == 0) { 
        return false;
    }else if (prod_id.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $(".input-qnt-number").val(1);
                $('.addtocart_massage').html(xmlhttp.responseText);
                carttotalval();
            }
        }
        xmlhttp.open("GET", "/order/Addtocart/?id=" + id+"&qnt="+ qnt+"&prod_id="+ prod_id, true);
        xmlhttp.send();
    }
}
function addtocartfromwishlist(id,qnt,prod_id,massagebox){
     if (id.length == 0) { 
        return false;
    }else if (qnt.length == 0) { 
        return false;
    }else if (prod_id.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $(".input-qnt-number").val(1);
                massagebox.html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "/order/Addtocart/?id=" + id+"&qnt="+ qnt+"&prod_id="+ prod_id, true);
        xmlhttp.send();
    }
}
    function updatecart(id,val,$input){
        if (id.length == 0) { 
            return false;
        }else if (val.length == 0) { 
            return false;
        }else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    $arrval = xmlhttp.responseText.trim().split('/');
                    $input.val($arrval[1]);
                    $("#grandtotal").html("Rs "+$arrval[0]);
                }
            }
            xmlhttp.open("GET", "/order/addtocart/updatecartbyuser/?id=" + id+"&val="+ val, true);
            xmlhttp.send();
        }
    }
    function removeitem(id,$catlist){
        if (id.length == 0) { 
            return false;
        }else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    $arrval = xmlhttp.responseText.trim().split('/');
                    if($arrval[0] == 0){
                        $catlist.fadeOut();
                        $("#grandtotal").html("Rs "+$arrval[1]);
                    }if($arrval[1].trim() == ""){
                      $("#grandtotal").html("Rs 0");  
                       $(".checkoutbtn").fadeOut();
                    }
                    
                }
            }
            xmlhttp.open("GET", "/order/addtocart/removieitem/?id=" + id, true);
            xmlhttp.send();
        }
    }
    function updateuserdata(gender,fname,lname,email,mob_no){
        if (gender.length == 0) { 
            return false;
        }else if (fname.length == 0) { 
            return false;
        }else if (lname.length == 0) { 
            return false;
        }else if (email.length == 0) { 
            return false;
        }else if (mob_no.length == 0) { 
            return false;
        }else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //$arrval = xmlhttp.responseText.trim().split('/');
                    $('#error_msg').html(xmlhttp.responseText);
                }
            }
            xmlhttp.open("GET", "/user/profile/update_user_pro/?gender=" + gender+"&fname="+fname+"&lname="+lname+"&email="+email+"&mob_no="+mob_no, true);
            xmlhttp.send();
        }
    }
    function addaddress(name,mob,address,city,pin){
        if (name.length == 0) { 
            return false;
        }else if (mob.length == 0) { 
            return false;
        }else if (address.length == 0) { 
            return false;
        }else if (city.length == 0) { 
            return false;
        }else if (pin.length == 0) { 
            return false;
        }else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    //$arrval = xmlhttp.responseText.trim().split('/');
                    if(xmlhttp.responseText == 1){                        
                    $('#error_msg').html("Your address get added!");
                    updatealladdresslist();
                     document.forms['userform'].reset();
                    }else{
                        $('#error_msg').html("Somethimg went wrong!");
                    }
                }
            }
            xmlhttp.open("GET", "/user/profile/addnewaddress/?name=" + name+"&mob="+mob+"&address="+address+"&city="+city+"&pin="+pin, true);
            xmlhttp.send();
        }
    }
    function addsubadmin(first,last,mail,pass){
         if (first.length == 0) { 
            return false;
        }else if (last.length == 0) { 
            return false;
        }else if (mail.length == 0) { 
            return false;
        }else if (pass.length == 0) { 
            return false;
        }else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert(xmlhttp.responseText);
                }
            }
            xmlhttp.open("GET", "/admin/superadmin/manageuser/subadminadd/?first=" + first+"&last="+last+"&mail="+mail+"&pass="+pass, true);
            xmlhttp.send();
        }
    }
    function wishlisttoadd(id,productid){
         if (id.length == 0) { 
            return false;
        }else if (productid.length == 0) { 
            return false;
        }else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    $(".addtocart_massage").html(xmlhttp.responseText);
                }
            }
            xmlhttp.open("GET", "/order/Wishlist/add/?id="+id+"&prodid="+productid, true);
            xmlhttp.send();
        }
    }
function removefromwishlist(id,prod_id,media,massagebox){
     if (id.length == 0) { 
        return false;
    }else if (prod_id.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $(".input-qnt-number").val(1);
                if(xmlhttp.responseText == 0){
                    media.fadeOut();
                }else{
                     massagebox.html(xmlhttp.responseText);
                 }
            }
        }
        xmlhttp.open("GET", "/user/wishlistitems/removeitemwishlist/?id="+ id +"&prod_id="+ prod_id, true);
        xmlhttp.send();
    }
}
function removeaddress(id,address_box){
    if (id.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if(xmlhttp.responseText==0){
                    address_box.fadeOut();
                }
            }
        }
        xmlhttp.open("GET", "/user/profile/removieaddress/?id="+ id, true);
        xmlhttp.send();
    }
}
function deletemainchat(id,chat,hideto){
    if (id.length == 0) { 
        return false;
    }else if (chat.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                hideto.fadeOut(1000);
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/deletemainchat/?id="+ id.trim()+"&chat="+chat.trim(), true);
        xmlhttp.send();
    }
}
function deletesubchat(id,chat,schat,hideto){
    if (id.length == 0) { 
        return false;
    }else if (chat.length == 0) { 
        return false;
    }else if (schat.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                hideto.fadeOut(1000);
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/deletesubchat/?id="+ id.trim()+"&chat="+chat.trim()+"&schat="+schat.trim(), true);
        xmlhttp.send();
    }
}
function deleteproduct(id,hideto){
    if (id.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if(xmlhttp.responseText==0){
                    hideto.fadeOut(1000);
                }else{
                    alert("try again");
                }
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/deleteproduct/?id="+ id.trim(), true);
        xmlhttp.send();
    }
}
function productonrow(id,prodid,collum,td){
    if (id.length == 0) { 
        return false;
    }else if (prodid.length == 0) { 
        return false;
    }else if (collum.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                td.html(xmlhttp.responseText);
                td.find('input', this).focus();
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/placeinputeintr/?id="+ id.trim()+"&prodid="+prodid.trim()+"&collum="+collum.trim(), true);
        xmlhttp.send();
    }
}
function productonrowupdate(id,prodid,collum,td,val){
    if (id.length == 0) { 
        return false;
    }else if (prodid.length == 0) { 
        return false;
    }else if (collum.length == 0) { 
        return false;
    }else if (val.length == 0) { 
        return false;
    }else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                td.html(xmlhttp.responseText);
                td.find('input', this).focus();
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/updateinputeintr/?id="+ id.trim()+"&prodid="+prodid.trim()+"&collum="+collum.trim()+"&val="+val.trim(), true);
        xmlhttp.send();
    }
}




//count the items in cart
function carttotalval(){
     if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $("#totalitemincart").html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "/order/Addtocart/countincart/", true);
        xmlhttp.send();
}
function updatealladdresslist(){
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $("#all_address_list").html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "/user/Profile/addressstatus", true);
        xmlhttp.send();
}

function updatemainchatlist(){
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $("#main-select-input").html(xmlhttp.responseText);
                $("#main-select-input-form").html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/mainchatlist/", true);
        xmlhttp.send();
}
function updatesubchatlist(){
    if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                $("#sub-select-input-form").html(xmlhttp.responseText);
            }
        }
        xmlhttp.open("GET", "/admin/Checkdatabasedata/subchatlist/", true);
        xmlhttp.send();
}