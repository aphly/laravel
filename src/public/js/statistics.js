function keyword(refer) {
    let search=refer.split(".")[1];
    let grep=null;
    let str=null;
    let keyword=null;
    switch(search){
        case "baidu":
            grep=/wd\=.*\&/i;
            str=refer.match(grep)
            keyword=str.toString().split("=")[1].split("&")[0];
            break;
        case "google":
            grep=/&q\=.*\&/i;
            str=refer.match(grep)
            keyword=str.toString().split("&")[1].split("=")[1];
            break;
    }
    return decodeURIComponent(keyword)
}
let token = document.getElementById('statistics').getAttribute('data-token');
window.onload = function() {
    if(token){
        let data = {
            language:navigator.language,
            platform:navigator.platform,
            userAgent:navigator.userAgent,
            webdriver:navigator.webdriver,
            url:window.location.href,
            referrer:document.referrer,
            keyword:keyword(document.referrer),
            _token:token
        }
        fetch('/center/statistics', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then((response) => response.json())
        .then((response) => {
            //console.log(response)
        })
    }
}
