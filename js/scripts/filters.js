SolleApp.filter('truncate', function(){
    return function (text, length, end) {
            if (isNaN(length))
                length = 10;
            if (end === undefined)
                end = "...";
            if (text.length <= length)
                return text;
            if (length < end.length)
                return text.substring(0, length);
            return text.substring(0, length-end.length+1).replace(/(^|\W+)\w*$/,end);
        };
});

SolleApp.filter('noHTML', function (){
    return function(text) {
        return text.replace(/<(.*?)>/g, ''); // remove html tags <p> etc...
    };
});
