//function to validate empty field
function check_empty() {
    if (document.getElementById('ctemail').value == ""
        || document.getElementById('ctinfo').value == ""
        || document.getElementById('cttitle').value == ""
        || document.getElementById('ctmsg').value == "") {
        alert("Please Input All Fields");
        //window.history.back();
        location.href = location.href;
        return false;
    }
    else {
        if (document.ctform.onsubmit && !document.ctform.onsubmit()) {
            return false;
        }
        document.ctform.submit();
        document.getElementById('close').click();
        return true;
    }
}

//function to display Popup
function div_show() {
    document.getElementById('ctabc').style.display = "block";
}

//function to hide Popup
function div_hide() {
    document.getElementById('ctabc').style.display = "none";
}

