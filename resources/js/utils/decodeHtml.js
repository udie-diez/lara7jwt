window.decodeHtml = function (value) {
    let doc = new DOMParser().parseFromString(value, "text/html");
    return doc.documentElement.textContent;
}
