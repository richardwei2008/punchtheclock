/**
 * Created by Richard on 2014/8/4.
 */
/*global $, jQuery, AppConfig, BMap, Beyond*/
// require baidu map
Beyond.namespace("BaiduMap");
Beyond.BaiduMap = {
    // 关于状态码
    // BMAP_STATUS_SUCCESS                  检索成功。对应数值“0”。
    // BMAP_STATUS_CITY_LIST                城市列表。对应数值“1”。
    // BMAP_STATUS_UNKNOWN_LOCATION         位置结果未知。对应数值“2”。
    // BMAP_STATUS_UNKNOWN_ROUTE            导航结果未知。对应数值“3”。
    // BMAP_STATUS_INVALID_KEY              非法密钥。对应数值“4”。
    // BMAP_STATUS_INVALID_REQUEST          非法请求。对应数值“5”。
    // BMAP_STATUS_PERMISSION_DENIED        没有权限。对应数值“6”。(自 1.1 新增)
    // BMAP_STATUS_SERVICE_UNAVAILABLE      服务不可用。对应数值“7”。(自 1.1 新增)
    // BMAP_STATUS_TIMEOUT                  超时。对应数值“8”。(自 1.1 新增)
    getCurrentPositionFailed: function (status) {
        "use strict";
        var message = "未知错误";
        switch (status) {
        case 0:
            message = "检索成功";
            break;
        case 1:
            message = "城市列表";
            break;
        case 2:
            message = "位置结果未知";
            break;
        case 3:
            message = "导航结果未知";
            break;
        case 4:
            message = "非法密钥";
            break;
        case 5:
            message = "非法请求";
            break;
        case 6:
            message = "没有权限";
            break;
        case 7:
            message = "服务不可用";
            break;
        case 8:
            message = "超时";
            break;
        default:
            break;
        }
        return message;
    },
    getDistance: function (lat_a, lng_a, lat_b, lng_b) {
        "use strict";
        var pk = 180 / Math.PI,
            a1 = lat_a / pk,
            a2 = lng_a / pk,
            b1 = lat_b / pk,
            b2 = lng_b / pk,
            t1 = Math.cos(a1) * Math.cos(a2) * Math.cos(b1) * Math.cos(b2),
            t2 = Math.cos(a1) * Math.sin(a2) * Math.cos(b1) * Math.sin(b2),
            t3 = Math.sin(a1) * Math.sin(b1),
            tt = Math.acos(t1 + t2 + t3);
        return 6366000 * tt;
    },
    addMyPosition: function (map, p) { // userIcon
        "use strict";
        var iconPath = "images/me.png", // (userIcon === "" ? "images/me.png" : "images/me.png"),
            myIcon = new BMap.Icon(
                iconPath,
                new BMap.Size(32, 32),
                {
                    offset: new BMap.Size(0, 0),
                    imageOffset: new BMap.Size(0, 0)   // 设置图片偏移
                }
            ),
            mk = new BMap.Marker(p, {icon: myIcon});
        map.addOverlay(mk);
    }
};