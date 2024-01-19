import $ from 'jquery';

export function getRequest(url, data, headers) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "GET",
            url: url,
            data: data,
            headers: headers,
            dataType: "json",
            success: function (response) {
                resolve(response); // Risolve la promessa con i dati della risposta
            },
            error: function (error) {
                reject(error); // Rigetta la promessa in caso di errore
            }
        });
    });
}

// conversione hex-base64
export function convertHexToBase64(str) {
    return btoa(String.fromCharCode.apply(null,
        str.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" "))
    );
}

// parametro url
export function getUrl() {
    const url = new URL(window.location.href);
    return url;
}

export function codificaParamURL(item) {
    return btoa(encodeURIComponent(item));
}

export function decodificaParamURL(item) {
    return atob(decodeURIComponent(item));
}