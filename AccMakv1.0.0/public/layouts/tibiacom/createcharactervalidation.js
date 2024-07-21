var nameHttp;

function checkName() {
    if (document.getElementById('charname').value == "") {
        document.getElementById('charname_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        document.getElementById('charname_errormessage').innerHTML = "Please enter a name for your character!";
        return;
    }

    nameHttp = GetXmlHttpObject();
    if (nameHttp == null) {
        return;
    }

    var charName = document.getElementById('charname').value;
    var url = "/ajax/checkname?uid=" + Math.random() + "&name=" + charName;

    nameHttp.onreadystatechange = NameStateChanged;
    nameHttp.open("POST", url, true);
    nameHttp.setRequestHeader("Content-type", "application/json");
	nameHttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);
    nameHttp.send(null);
}

function NameStateChanged() {
    if (nameHttp.readyState == 4 && nameHttp.status == 200) {
        var message = JSON.parse(nameHttp.responseText).message;
        if (message == "Character Name Valid") {
            document.getElementById('charname_errormessage').innerHTML = "";
            document.getElementById('charname_indicator').style.backgroundImage = "url('" + IMAGES + "/ok.gif')";
        } else {
            document.getElementById('charname_errormessage').innerHTML = message;
            document.getElementById('charname_indicator').style.backgroundImage = "url('" + IMAGES + "/nok.gif')";
        }
    }
}