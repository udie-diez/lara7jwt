<script src="{{ asset('themes/js/main/axios.min.js') }}"></script>
<script src="{{ asset('themes/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('themes/js/main/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('themes/js/plugins/loaders/blockui.min.js') }}"></script>
<script src="{{ asset('themes/js/plugins/notifications/pnotify.min.js') }}"></script>

@auth
<script src="{{ asset('themes/js/plugins/ui/perfect_scrollbar.min.js') }}"></script>
<script>
    // Setup module
    var FixedSidebarCustomScroll = function() {
        var _componentPerfectScrollbar = function() {
            if (typeof PerfectScrollbar == 'undefined') {
                console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
                return;
            }
            // Initialize
            var ps = new PerfectScrollbar('.sidebar-fixed .sidebar-content', {
                wheelSpeed: 2,
                wheelPropagation: true
            });
        };
        return {
            init: function() {
                _componentPerfectScrollbar();
            }
        }
    }();
    // Initialize module
    document.addEventListener('DOMContentLoaded', function() {
        FixedSidebarCustomScroll.init();
    });
</script>
@endauth

<script>
    axios.interceptors.request.use(
        (config) => {
            const token = getAccT();
            if (token) {
                config.headers["Authorization"] = `Bearer ${token}`;
            }
            return config;
        },
        (error) => {
            return Promise.reject(error);
        }
    );
    axios.interceptors.response.use(
        (res) => {
            return res;
        },
        async (err) => {
            const originalConfig = err.config;
            if (err.response) {
                // Access Token was expired
                if (err.response.status === 401 && !originalConfig._retry) {
                    originalConfig._retry = true;

                    try {
                        const rs = await refreshToken();
                        const { access_token, refresh_token } = rs.data;
                        window.localStorage.setItem("acct", access_token);
                        window.localStorage.setItem("reft", refresh_token);
                        axios.defaults.headers.common["Authorization"] = `Bearer ${access_token}`;

                        return axios(originalConfig);
                    } catch (_error) {
                        if (_error.response && _error.response.data) {
                            return Promise.reject(_error.response.data);
                        }

                        return Promise.reject(_error);
                    }
                }

                if (err.response.status === 403 && err.response.data) {
                    return Promise.reject(err.response.data);
                }
            }

            return Promise.reject(err);
        }
    );
    function getAccT() {
        return window.localStorage.getItem("acct");
    }
    function getRefT() {
        return window.localStorage.getItem("reft");
    }
    function refreshToken() {
        return axios.post("/auth/token-refresh", {
            headers: {
                'Authorization': `Bearer ${getRefT()}`
            },
        });
    }
    function convertFormToJSON(form) {
        return $(form)
            .serializeArray()
            .reduce(function (json, { name, value }) {
                json[name] = value;
                return json;
            }, {});
    }
</script>
<script src="{{ asset('themes/js/app.js') }}"></script>

@yield('scripts')
