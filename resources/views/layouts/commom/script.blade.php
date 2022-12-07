<script src="{{ asset('themes/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('themes/js/main/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('themes/js/plugins/loaders/blockui.min.js') }}"></script>
<script src="{{ asset('themes/js/plugins/notifications/pnotify.min.js') }}"></script>
<script src="{{ asset('themes/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

@if(Session::has('users'))
<script src="{{ asset('themes/js/plugins/ui/perfect_scrollbar.min.js') }}"></script>
<script>
    // Setup module
    var FixedSidebarCustomScroll = function() {
        var _componentPerfectScrollbar = function() {
            if (typeof PerfectScrollbar == 'undefined') {
                console.warn('Warning - perfect_scrollbar.min.js is not loaded.');
                return;
            }
            if (document.querySelector('.sidebar-fixed .sidebar-content')) {
                // Initialize
                var ps = new PerfectScrollbar('.sidebar-fixed .sidebar-content', {
                    wheelSpeed: 2,
                    wheelPropagation: true
                });
            }
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
@endif

<script src="{{ asset('themes/js/app.js') }}"></script>

@yield('scripts')
