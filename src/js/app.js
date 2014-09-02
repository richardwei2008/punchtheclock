/*global $, jQuery, window: true, browser: true, alert: true, BMap, AppConfig, Beyond*/
var App = {};
App = {
    Config: AppConfig,
    alert: function (obj) {
        "use strict";
        Beyond.Common.alert(this.Config.DEBUG, obj);
    },
    map: {},
    target: {},
    distance : 99999,
    globalUser: {},
    initMap: function () {
        "use strict";
        App.map = new BMap.Map("bmp");
        App.target = new BMap.Point(this.Config.LOCATION.COMPANY.lng, this.Config.LOCATION.COMPANY.lat); // 定位 家
        var marker = new BMap.Marker(App.target);
        App.map.addOverlay(marker);
        App.map.centerAndZoom(App.target, 25);
    },
    processAttendance: function (p) {
        "use strict";
        // App.alert('您的位置：' + p.lng + ',' + p.lat);
        // App.alert("Now:" + JSON.stringify(p));
        // App.alert("D:" + JSON.stringify(App.target));

        var located = new BMap.Point(p.lng, p.lat);
        App.distance = Beyond.BaiduMap.getDistance(App.target.lat, App.target.lng, p.lat, p.lng);
        // App.alert('您的距离（雁荡大厦）：' + App.distance); // r对象的point属性也是一个对象，这个对象的lng属性表示经度，lat属性表示纬度。
        if (App.distance > 500) {
            $("#err_distance").text(Math.ceil(App.distance));
            $.mobile.changePage('#err', {transition: 'pop', role: 'dialog'});
        } else if (App.distance > 200) {
            $("#warn_distance").text(Math.ceil(App.distance));
            $("#relocate").unbind('click').bind("click", App.punch);
            $("#confirm").unbind('click').bind('click', App.confirmAndSubmitAttendance(located));
            $.mobile.changePage('#warn', {transition: 'pop', role: 'dialog'});
        } else {
            // App.alert("accurate");
            App.confirmAndSubmitAttendance(located)(this);
        }
    },
    punch: function () {
        "use strict";
        var geolocation = new BMap.Geolocation(), //实例化浏览器定位对象。
        // 下面是getCurrentPosition方法。调用该对象的getCurrentPosition()，与HTML5不同的是，
        // 这个方法原型是getCurrentPosition(callback:function[, options: PositionOptions])，
        // 也就是说无论成功与否都执行回调函数1，第二个参数是关于位置的选项。 因此能否定位成功需要在回调函数1中自己判断。
            that = this;
        geolocation.getCurrentPosition(function (r) { //定位结果对象会传递给r变量
            if (this.getStatus() === BMAP_STATUS_SUCCESS) { // 通过Geolocation类的getStatus()可以判断是否成功定位。
                var located = r.point;
                App.processAttendance(located);
            } else {
                // this.alert('Failed' + Beyond.BaiduMap.getCurrentPositionFailed(this.getStatus()));
                if (!Beyond.Browser.versions.mobile) {
                    // App.alert("非移动终端环境，尝试模拟数据定位");
                    App.processAttendance(this.Config.MOCK_LOCATED.COMPANY);
                }
            }
        }, {
            enableHighAccuracy: true
        });
    },
    confirmAndSubmitAttendance: function (located) {
        "use strict";
        return function () {
            var userInfo = Beyond.WeChat.requireOauth(App.processAttendanceAfterAuth),
                userIcon = "";
            // App.alert(JSON.stringify(userInfo));
            if (userInfo !== null) {
                userIcon = userInfo.headimgurl;
            }
            if (!$.isEmptyObject(userInfo.openid)) {
                Beyond.BaiduMap.addMyPosition(App.map, located, userIcon);
                $("#punch").html("退&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;出");
                $("#punch").unbind('click').bind("click", function () {
                    window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/oa.html";
                });
                alert("签到成功！");
            } else {
                $("#err_distance").text(Math.ceil(this.distance));
                $("#errMsg").text("获得用户信息失败，无法签到，请重新登陆");
                $.mobile.changePage('#err', {transition: 'pop', role: 'dialog'});
            }
            return;
        };
    },
    processAttendanceAfterAuth: function (globalUser) {
        "use strict";
        return (function () {
            // alert(this);
            // alert(App);
            // App.alert("processAfterAuth");
            // App.alert("After-authCallback " + JSON.stringify(globalUser));
            $.ajax({
                async: false,
                type: 'POST',
                url: "services/AttendanceService.php",
                data: JSON.stringify(globalUser),
                dataType: 'json',
                success: function (data, textStatus, xhr) {
                    // App.alert("Post-submit (success) " + xhr.responseText);
                },
                error: function (xhr) {
                    $("#fatalMsg").html(xhr.responseText);
                    $.mobile.changePage('#fatal', {transition: 'pop', role: 'dialog'});
                }
            });
        }(this));
    },
    viewHistory : function() {
        $.mobile.changePage('#history', "slideup");
    },
    backToPunch : function() {
        $.mobile.changePage('#mainPunch', "slide");
    },
    initLeave: function () {
        "use strict";
        //        App.alert("Init leave");
        App.globalUser = Beyond.WeChat.requireOauth(App.processLeaveOnLoad);
        var userIcon = "images/me.png";
        if (App.globalUser !== null
            && App.globalUser.headimgurl !== null
            && App.globalUser.headimgurl !== "") {
            userIcon = App.globalUser.headimgurl;
        }
//        App.alert(userIcon);
        $("#icon").attr("src", userIcon);
    },
    leave: function () {
        "use strict";
//        App.alert("Am leaving");
        App.processLeave(App.globalUser);
    },
    processLeave: function (p) {
        "use strict";
        App.confirmAndSubmitLeave(p)(this);
    },
    confirmAndSubmitLeave: function (userInfo) {
        "use strict";
        return function () {
            App.processLeaveAfterAuth(userInfo);
            var userIcon = "";
            if (userInfo !== null) {
                userIcon = userInfo.headimgurl;
            }
            return;
        };
    },
    processLeaveOnLoad: function (globalUser) {
        "use strict";
        return (function () {
            // App.alert("processLeaveOnLoad " + JSON.stringify(globalUser));
            $.ajax({
                async: false,
                type: 'POST',
                url: "services/UserService.php",
                data: JSON.stringify(globalUser),
                dataType: 'json',
                success: function (data, textStatus, xhr) {
//                    App.alert("Post-submit (success) " + JSON.stringify(data.me.name));
                    // $('#name').val(data.me.name);
                    $('#name').text(data.me.name);
                },
                error: function (xhr) {
                    $("#fatalMsg").html(xhr.responseText);
                    $.mobile.changePage('#fatal', {transition: 'pop', role: 'dialog'});
                }
            });
        }(this));
    },
    processLeaveAfterAuth: function (globalUser) {
        "use strict";
        return (function () {
            // App.alert("Before-submit processLeaveAfterAuth " +   JSON.stringify($("#mainForm").serializeJSON()));
            $.ajax({
                async: false,
                type: 'POST',
                url: "services/LeaveService.php",
                contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                timeout : 1000,
                dataType : 'json',
                data: JSON.stringify({method : "POST", openid: globalUser.openid, data : $("#mainForm").serializeJSON()}),
                success: function (response) {
                    // App.alert("Post-submit processLeaveAfterAuth (success) " + xhr.responseText);
                    if (response.success) {
//                        App.alert("Post-submit processLeaveAfterAuth (success) " + JSON.stringify(response));
                        // window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/submitted.html";
                        if (!$.isEmptyObject(globalUser.openid)) {
                            $("#leave").html("退&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;出");
                            $("#leave").unbind('click').bind("click", function () {
                                window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf('/')) + "/oa.html";
                            });
                            alert("请假成功！");
                        } else {
                            $("#errMsg").text("获得用户信息失败，无法请假，请重新登陆");
                            $.mobile.changePage('#err', {transition: 'pop', role: 'dialog'});
                        }
                    } else {
                        switch (response.code) {
                            case 'E001':
                                $("#errorMsg").html(response.message);
                                break;
                            default :
                                $("#errorMsg").html("未知错误，请联系管理员—" + JSON.stringify(response));
                                break;
                        }
                        $.mobile.changePage('#err', {transition: 'pop', role: 'dialog'});
                    }
                },
                error: function (xhr) {
                    $("#fatalMsg").html(xhr.responseText);
                    $.mobile.changePage('#fatal', {transition: 'pop', role: 'dialog'});
                }
            });
        }());
    }
};




