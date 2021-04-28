// Set post request headers and variables
function postRequestHeaders(raw) {
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "text/plain");
    var requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: raw,
        redirect: 'follow'
    };
    return requestOptions;
}

// TP-Link Post request
function postTpLinkRequest(raw) {
    fetch("https://wap.tplinkcloud.com", postRequestHeaders(raw))
        .then(response => response.text())
        .then(result => console.log(result))
        .catch(error => console.log('error', error)
    );
}