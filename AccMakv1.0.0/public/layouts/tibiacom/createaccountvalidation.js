var accountHttp;
var emailHttp;

function GetXmlHttpObject() {
    var xmlHttp = null;
    try {
        xmlHttp = new XMLHttpRequest();
    } catch (e) {
        try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    return xmlHttp;
}

function checkAccount() {
    if (document.getElementById('accountname').value == "") {
        document.getElementById('accountname_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        document.getElementById('accountname_errormessage').innerHTML = "Please enter an account name!";
        return;
    }

    accountHttp = GetXmlHttpObject();
    if (accountHttp == null) {
        return;
    }

    var accountName = document.getElementById('accountname').value;
    var url = "/ajax/checkaccount?uid=" + Math.random() + "&account=" + accountName;

    accountHttp.onreadystatechange = AccountStateChanged;
    accountHttp.open("POST", url, true);
    accountHttp.setRequestHeader("Content-type", "application/json");
	accountHttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    accountHttp.send(null);
}

function AccountStateChanged() { 
    if (accountHttp.readyState == 4 && accountHttp.status == 200) {
        var message = JSON.parse(accountHttp.responseText).message;
        if (message == "Account Name Valid") {
            document.getElementById('accountname_errormessage').innerHTML = "";
            document.getElementById('accountname_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
        } else {
            document.getElementById('accountname_errormessage').innerHTML = message;
            document.getElementById('accountname_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        }
    }
}

function checkEmail() {
    if (document.getElementById('email').value == "") {
        document.getElementById('email_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        document.getElementById('email_errormessage').innerHTML = "Please enter your email address!";
        return;
    }

    emailHttp = GetXmlHttpObject();
    if (emailHttp == null) {
        return;
    }

    var email = document.getElementById('email').value;
    var url = "/ajax/checkemail?uid=" + Math.random() + "&email=" + email;

    emailHttp.onreadystatechange = EmailStateChanged;
    emailHttp.open("POST", url, true);
    emailHttp.setRequestHeader("Content-type", "application/json");
	emailHttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    emailHttp.send(null);
}

function EmailStateChanged() {
    if (emailHttp.readyState == 4 && emailHttp.status == 200) {
        var message = JSON.parse(emailHttp.responseText).message;
        if (message == "Email Address Valid") {
            document.getElementById('email_errormessage').innerHTML = "";
            document.getElementById('email_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
        } else {
            document.getElementById('email_errormessage').innerHTML = message;
            document.getElementById('email_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        }
    }
}

function checkPassword() {
    var passwordField = document.getElementById('password1').value;
    var confirmPasswordField = document.getElementById('password2').value;

    if (passwordField !== '' && passwordField.length >= 6 && passwordField.length <= 40) {
		const regex = /^(?=.*[a-zA-Z]{3,})(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-zA-Z\d!@#$%^&*]).+$/;
		if (regex.test(passwordField)){
			if(confirmPasswordField !== '') {
				if (passwordField === confirmPasswordField) {
					document.getElementById('password_errormessage').innerHTML = '';
					document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
				} else {
					document.getElementById('password_errormessage').innerHTML = 'Passwords do not match!';
					document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
				}
			} else {
				document.getElementById('password_errormessage').innerHTML = 'Please enter the password again!';
				document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
			}
			document.getElementById('password1_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
		} else {
            document.getElementById('password_errormessage').innerHTML = 'Passwords should contain at least 3 of a-z or A-Z, a number and a special character.';
			document.getElementById('password1_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
			document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        }
    } else {
        document.getElementById('password_errormessage').innerHTML = 'Please enter a password between 6 to 40 characters long.';
		document.getElementById('password1_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
    }
}

function confirmPassword() {
    var passwordField = document.getElementById('password1').value;
    var confirmPasswordField = document.getElementById('password2').value;
    
	if(passwordField !== '' && passwordField.length >= 6 && passwordField.length <= 40) {
		const regex = /^(?=.*[a-zA-Z]{3,})(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-zA-Z\d!@#$%^&*]).+$/;
		if (regex.test(passwordField)){
			if(confirmPasswordField !== '') {
				if (passwordField === confirmPasswordField) {
					document.getElementById('password_errormessage').innerHTML = '';
					document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
				} else {
					document.getElementById('password_errormessage').innerHTML = 'Passwords do not match!';
					document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
				}
			} else {
				document.getElementById('password_errormessage').innerHTML = 'Please enter the password again!';
				document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
			}
			document.getElementById('password1_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
		} else {
            document.getElementById('password_errormessage').innerHTML = 'Passwords should contain at least 3 of a-z or A-Z, a number and a special character.';
			document.getElementById('password1_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
			document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
		}
	} else {
        document.getElementById('password_errormessage').innerHTML = 'Please enter a password between 6 to 40 characters long.';
		document.getElementById('password1_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        document.getElementById('password2_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
    }
}