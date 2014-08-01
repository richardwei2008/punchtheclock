var Beyond = {};

Beyond.Common = {
    alert : function(isEnabled, obj) {
        if (isEnabled) {
            alert(obj);
        }
    },
    sprintf : function(text) {
        var i = 1,
            args = arguments;
        return text.replace(/%s/g, function() {
            return (i < args.length) ? args[i++] : "";
        });
    }

};