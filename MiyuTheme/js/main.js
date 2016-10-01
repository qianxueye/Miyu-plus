$(document).ready(function() {
    page_init();
    imagejson();
    $(window).resize(function() {
        imagejson();
        if (document.body.clientWidth >= 481) {
            $("#background,#sidebar").show(0);
            $("#wrapper,#main").removeClass("opacity blur-saturate");
        } else {
            $("#background,#sidebar").hide(0);
            $("#wrapper,#main").removeClass("opacity blur-saturate");
        }
    });
});
function page_init() {
    page_button();
    $("#background-view").toggle(function() {
        $("#header,#wrapper,#imagesource,.control-o").stop();
        $(".login-call,.w-search").fadeOut(100);
        $("#header,#wrapper").fadeOut(300);
        $("#imagesource").fadeIn(200);
        $("#background").removeClass("blur-saturate");
        $("#background").addClass("saturate");
        $(".searchbutton,.loginbutton,#up-page,#down-page,.switchbutton").unbind();
        $(".control-o").animate({
            opacity: "toggle"
        },
        800);
    },
    function() {
        $("#header,#wrapper,#imagesource,.control-o").stop();
        $("#header,#wrapper").fadeIn(300,
        function() {
            $("#header,#wrapper").removeAttr("style");
        });
        $("#imagesource").fadeOut(200);
        $("#background").removeClass("saturate");
        $("#background").addClass("blur-saturate");
        page_button();
        $(".control-o").animate({
            opacity: "toggle"
        },
        800);
    });

    $("#wrapper,#sidebar").click(function() {
        $(".hide").hide(100)
    });
    $(".control-s").click(function() {
        $("#button-group").stop().animate({
            opacity: "toggle"
        },
        300,
        function() {
            $(".hide").hide(200);
        });
    });
    $('#mail').blur(function() {
        $('.gravatar-v').attr('src', '//cn.gravatar.com/avatar/' + $.md5($('#mail').val()) + '?s=128&r=X')
    });

}

function page_button() {
    $(".loginbutton").unbind('click').click(function() {
        $(".login-call").clicksw(200);
        $(".w-search").closesw(200)
    });
    $(".searchbutton").unbind('click').click(function() {
        $(".w-search").clicksw(200);
        $(".login-call").closesw(200)
    });
    $("#up-page").unbind('click').click(function() {
        $("html,body").animate({
            scrollTop: 0
        },
        600)
    });
    $("#down-page").unbind('click').click(function() {
        $("html,body").animate({
            scrollTop: $("#wrapper").height()
        },
        600)
    });
    $(".switchbutton").click(function() {
        swclolr();
        $(".hide").closesw(200);
        $(this).switchicon();
    });
    $("#show-sidebar").click(function() {
        $("#sidebar").clicksw(200);
        $("#background").clicksw(200);
        $("#wrapper").stop(true, false);
        $("#wrapper").toggleClass("opacity");
        $(".control-o,.control-s").fadeToggle();
        if (displayed("#button-group")) $("#button-group").fadeOut();
        $("html,body").css("height", "100%");
        setTimeout(function() {
            $("#wrapper,#main").toggleClass("blur-saturate")
        },
        200);
        setTimeout(function() {
            if (!displayed("#sidebar")) $("html,body").removeAttr("style")
        },
        700);
    });
}

function swclolr() {
    $("#checked").clicksw(0);
    $("#unchecked").clicksw(0);
    stylesw();
    setTimeout(function() {
        imagejson()
    },
    500);
}

$.fn.switchicon = function() {
    $("#switch-icon").stop(true, false);
    $("#switch-icon").fadeIn().delay(300);
    $("#switch-icon").fadeOut();

}
function stylesw() {
    if (displayed("#unchecked")) {
        document.getElementById("colorstyle").setAttribute("href", themeurl + "css/Light.css");
        setCookie("color", "Light", 365);
    } else {
        document.getElementById("colorstyle").setAttribute("href", themeurl + "css/Dark.css");
        setCookie("color", "Dark", 365);
    }
}

function imagejson() {
    if (typeof(imgsrc) != "object" && Object.prototype.toString.call(imgsrc).toLowerCase() != "[object object]") return false;
    if (displayed("#dark-wallpaper") && ("DarkImage" in imgsrc)) {
        document.getElementById('illust-name').innerHTML = imgsrc.DarkImage.illust_name;
        document.getElementById('illust-desc').innerHTML = imgsrc.DarkImage.illust_desc;
        document.getElementById('illust-src').innerHTML = imgsrc.DarkImage.illust_source;
    }
    if (displayed("#light-wallpaper") && ("LightImage" in imgsrc)) {
        document.getElementById('illust-name').innerHTML = imgsrc.LightImage.illust_name;
        document.getElementById('illust-desc').innerHTML = imgsrc.LightImage.illust_desc;
        document.getElementById('illust-src').innerHTML = imgsrc.LightImage.illust_source;
    }
    if (displayed("#portrait-wallpaper") && ("MobileImage" in imgsrc)) {
        document.getElementById('illust-name').innerHTML = imgsrc.MobileImage.illust_name;
        document.getElementById('illust-desc').innerHTML = imgsrc.MobileImage.illust_desc;
        document.getElementById('illust-src').innerHTML = imgsrc.MobileImage.illust_source;
    }
    if ("Image" in imgsrc) {
        document.getElementById('illust-name').innerHTML = imgsrc.Image.illust_name;
        document.getElementById('illust-desc').innerHTML = imgsrc.Image.illust_desc;
        document.getElementById('illust-src').innerHTML = imgsrc.Image.illust_source;
    }

}
function displayed(tag) {
    if ($(tag).css("display") == undefined) return false;
    else if ($(tag).css("display") == "none") return false;
    return true;
}

$.fn.clicksw = function(time) {
    $(this).css("display") == "none" ? $(this).fadeIn(time) : $(this).fadeOut(time);
};
$.fn.closesw = function(time) {
    $(this).each(function() {
        if ($(this).css("display") != "none") $(this).fadeOut(time);
    });
};

$.fn.toggle = function(fn, fn2) {
    var args = arguments,
    guid = fn.guid || $.guid++,
    i = 0,
    toggle = function(event) {
        var lastToggle = ($._data(this, "lastToggle" + fn.guid) || 0) % i;
        $._data(this, "lastToggle" + fn.guid, lastToggle + 1);
        event.preventDefault();
        return args[lastToggle].apply(this, arguments) || false;
    };
    toggle.guid = guid;
    while (i < args.length) {
        args[i++].guid = guid;
    }
    return this.click(toggle);
};

function getCookie(name) {
    if (document.cookie.length > 0) {
        start = document.cookie.indexOf(name + "=");
        if (start != -1) {
            start = start + name.length + 1;
            end = document.cookie.indexOf(";", start);
             if (end == -1) 
            end = document.cookie.length;
            return unescape(document.cookie.substring(start, end))
        }
    }
    return ""
}
function setCookie(name, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = name + "=" + escape(value) + ((expiredays == null) ? "": ";expires=" + exdate.toGMTString()) + ";path=/";
}