window.convertFormToJSON = function (form) {
    if (typeof jQuery == 'undefined') {
        console.warn('Warning - jquery.min.js is not loaded.');
        return;
    }
    return $(form)
        .serializeArray()
        .reduce(function (json, { name, value }) {
            json[name] = value;
            return json;
        }, {});
}
