window.notiLoaderRedirect = function (target) {
    if (typeof PNotify == 'undefined') {
        console.warn('Warning - pnotify.min.js is not loaded.');
        return;
    }
    var cur_value = 1,
        progress;
    // Make a loader.
    var loader = new PNotify({
        title: "Please wait redirecting...",
        text: '<div class="progress" style="margin:0">\
                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">\
                <span class="sr-only">0%</span>\
                </div>\
                </div>',
        addclass: 'bg-primary border-primary',
        icon: 'icon-spinner4 spinner',
        hide: false,
        buttons: {
            closer: false,
            sticker: false
        },
        before_open: function (PNotify) {
            progress = PNotify.get().find("div.progress-bar");
            progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");

            // Pretend to do something.
            var timer = setInterval(function () {
                if (cur_value >= 100) {

                    // Remove the interval.
                    window.clearInterval(timer);
                    loader.remove();
                    window.location.href = target;
                    return;
                }
                cur_value += 1;
                progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");
            }, 65);
        }
    });
}
window.noti = function () {
    var _loaderRedirect = function (target) {
        var cur_value = 1,
            progress;
        // Make a loader.
        var loader = new PNotify({
            title: "Please wait redirecting...",
            text: '<div class="progress" style="margin:0">\
                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">\
                <span class="sr-only">0%</span>\
                </div>\
                </div>',
            addclass: 'bg-primary border-primary',
            icon: 'icon-spinner4 spinner',
            hide: false,
            buttons: {
                closer: false,
                sticker: false
            },
            before_open: function (PNotify) {
                progress = PNotify.get().find("div.progress-bar");
                progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");
                // Pretend to do something.
                var timer = setInterval(function () {
                    if (cur_value >= 100) {

                        // Remove the interval.
                        window.clearInterval(timer);
                        loader.remove();
                        window.location.href = target;
                        return;
                    }
                    cur_value += 1;
                    progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");
                }, 65);
            }
        });
    }
    var _loaderRedirectDynamic = function (target) {
        var percent = 0;
        var notice = new PNotify({
            text: "Please wait",
            addclass: 'bg-primary border-primary',
            type: 'info',
            icon: 'icon-spinner4 spinner',
            hide: false,
            buttons: {
                closer: false,
                sticker: false
            },
            opacity: .9,
            width: "170px"
        });
        setTimeout(function () {
            notice.update({
                title: false
            });
            var interval = setInterval(function () {
                percent += 2;
                var options = {
                    text: percent + "% complete."
                };
                if (percent == 80) options.title = "Almost There";
                if (percent >= 100) {
                    window.clearInterval(interval);
                    options.title = "Done!";
                    options.addclass = "bg-success border-success";
                    options.type = "success";
                    options.hide = true;
                    options.buttons = {
                        closer: true,
                        sticker: true
                    };
                    options.icon = 'icon-checkmark3';
                    options.opacity = 1;
                    options.width = PNotify.prototype.options.width;
                    window.location.href = target;
                }
                notice.update(options);
            }, 120);
        }, 2000);
    }
    return {
        default: function (options) {
            new PNotify({
                title: options.title ?? 'Info notice',
                text: options.text ?? 'Check me out! I\'m a notice.',
                icon: options.icon ?? 'icon-info22',
                type: options.type ?? 'info',
                addclass: 'alert alert-styled-left alert-arrow-left',
            });
        },
        loaderRedirect: function (target) {
            _loaderRedirect(target);
        },
        loaderRedirectDynamic: function (target) {
            _loaderRedirectDynamic(target);
        }
    }
}();
