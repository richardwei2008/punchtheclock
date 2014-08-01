Beyond.WeChat = {};

Beyond.WeChat.Config = {
    REDIRECT_URI_TEMPLATE : "http%3A%2F%2F" + "%s" + "%2F" + "%s", // (%s, %s) <= (window.location.hostname, attendance.html)
    HTTP_OAUTH_URI : "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc63c757bdae5dd41&redirect_uri=" + "%s" + "&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect" // (%s) <= (redirect_uri)
};

Beyond.WeChat = {
    isWeiXin : function() {
        var ua = window.navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == 'micromessenger') {
            return true;
        } else {
            return false;
        }
    },
    formatRedirectUrl : function(hostname, pagename) {
        var formatedUri = Beyond.Common.sprintf(this.Config.REDIRECT_URI_TEMPLATE, hostname, pagename);
        return formatedUri;
    },
    formatOauthUrl : function(redirectUri) {
        var formatedUri = Beyond.Common.sprintf(this.Config.HTTP_OAUTH_URI, redirectUri);
        return formatedUri;
    },
    redirectToPageWithOauth : function(gotoUri) {
        if (this.isWeiXin()) {
            var redirect_uri = this.formatRedirectUrl(window.location.hostname, gotoUri);  // "http%3A%2F%2F" + window.location.hostname + "%2Fattendance.html";
            var url = this.formatOauthUrl(redirect_uri); // "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc63c757bdae5dd41&redirect_uri="+ redirect_uri + "&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
            window.location.href = url;
        } else {
            window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/" + gotoUri;
        }
    }
};