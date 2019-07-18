window.SessionTimeoutManager = {
    sessionTimeout: null,
    defaults: {
        message: 'Your session is about to expire.',
        keepAliveUrl: '/keep-alive',
        keepAliveAjaxRequestType: 'POST',
        redirUrl: '/session-timed-out',
        logoutUrl: '/logout',
        warnAfter: 780000, // 13 minutes
        redirAfter: 900000, // 15 minutes
        appendTime: true // appends time stamp to keep alive url to prevent caching.
    },

    init() {
        if (window.location.pathname != '/' && window.location.pathname != '/login') {
            this.sessionTimeout = $.sessionTimeout(this.defaults);
            this.sessionTimeout.refresh();
        }

        this.registerHttpInterceptor();
    },

    registerHttpInterceptor() {
        axios.interceptors.response.use((response) => {
            this.refresh();

            return response;
        }, (error) => {
            this.refresh();

            return Promise.reject(error);
        });
    },

    refresh() {
        this.sessionTimeout.refresh();
    },

    getKeepAliveUrl() {
        return this.defaults.keepAliveUrl;
    }
};

window.SessionTimeoutManager.init();