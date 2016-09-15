(function($){

    $.extend({
        routes: {
            list:{},
            add : function(route,routeFC){
                if(typeof(route) != 'string') return;
                if(typeof(routeFC) != 'function' && typeof(routeFC) != 'object') return;

                if (route[route.length - 1] !== '/') { route += '/'; }
                var routeexp = '^' + route;

                var paramNum = 0;
                var paramArr = route.match(/(:\S+?\/)/ig);
                if(!!paramArr){
                    var dataArr = route.match(/(\S+?\/)/ig);
                    for(var i=0;i<paramArr.length;i++){
                        if(paramArr[paramArr.length-i-1] != dataArr[dataArr.length-i-1])
                            return;
                    }

                    for(i=0;i<paramArr.length;i++)
                        paramArr[i] = paramArr[i].substr(1,paramArr[i].length-2);

                    routeexp = '^';
                    for(i=0;i<dataArr.length - paramArr.length;i++)
                        routeexp += dataArr[i];

                    for(i=0;i<paramArr.length;i++)
                        routeexp += '(\\S+?)\\/';

                    paramNum = paramArr.length;
                }

                //execute url
                var execute = function(data){
                    if(typeof(routeFC) == 'function'){
                        this.routeFC(data,this);
                    } else if(typeof(routeFC) == 'object') {
                        this.routeFC.render(data,this);
                    } else {
                        console.log('routeFC is not function or object');
                    }
                };

                // extract parameters from hash
                var extract = function(hash) {
                    var context = {};
                    if(!this.param)
                        return context;

                    var dataArr = this.exp.exec(hash);
                    for(var i=1;i<dataArr.length;i++){
                        context[this.param[i-1]] = dataArr[i];
                    }
                    return context;
                };


                return this.list[route] = {
                    name: route,
                    route: route,
                    exp: new RegExp(routeexp, 'i'),
                    routeFC: routeFC,
                    level: route.length - route.replace(new RegExp("/", "g"), '').length - paramNum,
                    param:paramArr,
                    execute: execute,
                    extract: extract
                }
            },
            start:function(){
                $.routes.load(location.hash);
            },
            default:function(route){
                var rHash = '';
                var rParam = '';
                var hashArr = /(.*)\?(.*)/i.exec(route);

                if(!hashArr || hashArr.length != 3){
                    rHash = route;
                } else {
                    rHash = hashArr[1];
                    rParam = hashArr[2];
                }

                if(rHash[rHash.length - 1] != '/')
                    rHash += '/';

                this.defaultRoute = {
                    hash : rHash,
                    param : rParam
                };
            },
            findRoute: function(hash) {
                var route;
                // search for routes
                $.each(this.list, function(i, match) {
                    // check for a match
                    var exp = match.exp.exec(hash);
                    if (exp) {
                        if ((route && match.level > route.level) || !route) {
                            route = match;
                        }
                    }
                });
                return route;
            },
            load: function(hash) {
                if($.isEmptyObject(this.list))
                    return;

                var realHash = '';
                var hashArr = /#(.*)\?(.*)/i.exec(hash);

                if(!hashArr || hashArr.length != 3){
                    realHash = hash.substr(1);  //remove '#'
                } else {
                    realHash = hashArr[1];
                }

                if(realHash[realHash.length - 1] != '/')
                    realHash += '/';

                var route = $.routes.findRoute(realHash);

                // route not found
                if (!route) {
                    if(realHash != this.defaultRoute.hash){
                        location.hash = !!this.defaultRoute.param ? (this.defaultRoute.hash + '?' + this.defaultRoute.param) : this.defaultRoute.hash;
                    }
                    return;
                }

                // execute route
                $.routes.current = route;
                $.routes.current.param = route.extract(realHash);
                route.execute($.routes.current.param);
            },
            current: null,
            defaultRoute:{
                hash:'/',
                param:''
            }
        }
    });

    // Listen for hash change event
    $(window).bind('hashchange', function() {
        $.routes.load(location.hash);
    });

}(jQuery));