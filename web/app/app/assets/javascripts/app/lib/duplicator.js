/** Notify.js - v0.3.1 - 2014/06/29
 * http://notifyjs.com/
 * Copyright (c) 2014 Jaime Pillora - MIT
 */
;(function(t,i,n,e){"use strict";var r,o,s,a,l,h,c,p,u,d,f,A,m,w,g,y,b,v,x,C,S,E,M,k,H,D,F,T=[].indexOf||function(t){for(var i=0,n=this.length;n>i;i++)if(i in this&&this[i]===t)return i;return-1};S="notify",C=S+"js",s=S+"!blank",M={t:"top",m:"middle",b:"bottom",l:"left",c:"center",r:"right"},m=["l","c","r"],F=["t","m","b"],b=["t","b","l","r"],v={t:"b",m:null,b:"t",l:"r",c:null,r:"l"},x=function(t){var i;return i=[],n.each(t.split(/\W+/),function(t,n){var r;return r=n.toLowerCase().charAt(0),M[r]?i.push(r):e}),i},D={},a={name:"core",html:'<div class="'+C+'-wrapper">\n  <div class="'+C+'-arrow"></div>\n  <div class="'+C+'-container"></div>\n</div>',css:"."+C+"-corner {\n  position: fixed;\n  margin: 5px;\n  z-index: 1050;\n}\n\n."+C+"-corner ."+C+"-wrapper,\n."+C+"-corner ."+C+"-container {\n  position: relative;\n  display: block;\n  height: inherit;\n  width: inherit;\n  margin: 3px;\n}\n\n."+C+"-wrapper {\n  z-index: 1;\n  position: absolute;\n  display: inline-block;\n  height: 0;\n  width: 0;\n}\n\n."+C+"-container {\n  display: none;\n  z-index: 1;\n  position: absolute;\n}\n\n."+C+"-hidable {\n  cursor: pointer;\n}\n\n[data-notify-text],[data-notify-html] {\n  position: relative;\n}\n\n."+C+"-arrow {\n  position: absolute;\n  z-index: 2;\n  width: 0;\n  height: 0;\n}"},H={"border-radius":["-webkit-","-moz-"]},f=function(t){return D[t]},o=function(i,e){var r,o,s,a;if(!i)throw"Missing Style name";if(!e)throw"Missing Style definition";if(!e.html)throw"Missing Style HTML";return(null!=(a=D[i])?a.cssElem:void 0)&&(t.console&&console.warn(""+S+": overwriting style '"+i+"'"),D[i].cssElem.remove()),e.name=i,D[i]=e,r="",e.classes&&n.each(e.classes,function(t,i){return r+="."+C+"-"+e.name+"-"+t+" {\n",n.each(i,function(t,i){return H[t]&&n.each(H[t],function(n,e){return r+="  "+e+t+": "+i+";\n"}),r+="  "+t+": "+i+";\n"}),r+="}\n"}),e.css&&(r+="/* styles for "+e.name+" */\n"+e.css),r&&(e.cssElem=y(r),e.cssElem.attr("id","notify-"+e.name)),s={},o=n(e.html),u("html",o,s),u("text",o,s),e.fields=s},y=function(t){var i;i=l("style"),i.attr("type","text/css"),n("head").append(i);try{i.html(t)}catch(e){i[0].styleSheet.cssText=t}return i},u=function(t,i,e){var r;return"html"!==t&&(t="text"),r="data-notify-"+t,p(i,"["+r+"]").each(function(){var i;return i=n(this).attr(r),i||(i=s),e[i]=t})},p=function(t,i){return t.is(i)?t:t.find(i)},E={clickToHide:!0,autoHide:!0,autoHideDelay:5e3,arrowShow:!0,arrowSize:5,breakNewLines:!0,elementPosition:"bottom",globalPosition:"top right",style:"bootstrap",className:"error",showAnimation:"slideDown",showDuration:400,hideAnimation:"slideUp",hideDuration:200,gap:5},g=function(t,i){var e;return e=function(){},e.prototype=t,n.extend(!0,new e,i)},h=function(t){return n.extend(E,t)},l=function(t){return n("<"+t+"></"+t+">")},A={},d=function(t){var i;return t.is("[type=radio]")&&(i=t.parents("form:first").find("[type=radio]").filter(function(i,e){return n(e).attr("name")===t.attr("name")}),t=i.first()),t},w=function(t,i,n){var r,o;if("string"==typeof n)n=parseInt(n,10);else if("number"!=typeof n)return;if(!isNaN(n))return r=M[v[i.charAt(0)]],o=i,t[r]!==e&&(i=M[r.charAt(0)],n=-n),t[i]===e?t[i]=n:t[i]+=n,null},k=function(t,i,n){if("l"===t||"t"===t)return 0;if("c"===t||"m"===t)return n/2-i/2;if("r"===t||"b"===t)return n-i;throw"Invalid alignment"},c=function(t){return c.e=c.e||l("div"),c.e.text(t).html()},r=function(){function t(t,i,e){"string"==typeof e&&(e={className:e}),this.options=g(E,n.isPlainObject(e)?e:{}),this.loadHTML(),this.wrapper=n(a.html),this.options.clickToHide&&this.wrapper.addClass(""+C+"-hidable"),this.wrapper.data(C,this),this.arrow=this.wrapper.find("."+C+"-arrow"),this.container=this.wrapper.find("."+C+"-container"),this.container.append(this.userContainer),t&&t.length&&(this.elementType=t.attr("type"),this.originalElement=t,this.elem=d(t),this.elem.data(C,this),this.elem.before(this.wrapper)),this.container.hide(),this.run(i)}return t.prototype.loadHTML=function(){var t;return t=this.getStyle(),this.userContainer=n(t.html),this.userFields=t.fields},t.prototype.show=function(t,i){var n,r,o,s,a,l=this;if(r=function(){return t||l.elem||l.destroy(),i?i():e},a=this.container.parent().parents(":hidden").length>0,o=this.container.add(this.arrow),n=[],a&&t)s="show";else if(a&&!t)s="hide";else if(!a&&t)s=this.options.showAnimation,n.push(this.options.showDuration);else{if(a||t)return r();s=this.options.hideAnimation,n.push(this.options.hideDuration)}return n.push(r),o[s].apply(o,n)},t.prototype.setGlobalPosition=function(){var t,i,e,r,o,s,a,h;return h=this.getPosition(),a=h[0],s=h[1],o=M[a],t=M[s],r=a+"|"+s,i=A[r],i||(i=A[r]=l("div"),e={},e[o]=0,"middle"===t?e.top="45%":"center"===t?e.left="45%":e[t]=0,i.css(e).addClass(""+C+"-corner"),n("body").append(i)),i.prepend(this.wrapper)},t.prototype.setElementPosition=function(){var t,i,r,o,s,a,l,h,c,p,u,d,f,A,g,y,x,C,S,E,H,D,z,Q,B,R,N,P,U;for(z=this.getPosition(),E=z[0],C=z[1],S=z[2],u=this.elem.position(),h=this.elem.outerHeight(),d=this.elem.outerWidth(),c=this.elem.innerHeight(),p=this.elem.innerWidth(),Q=this.wrapper.position(),s=this.container.height(),a=this.container.width(),A=M[E],y=v[E],x=M[y],l={},l[x]="b"===E?h:"r"===E?d:0,w(l,"top",u.top-Q.top),w(l,"left",u.left-Q.left),U=["top","left"],B=0,N=U.length;N>B;B++)H=U[B],g=parseInt(this.elem.css("margin-"+H),10),g&&w(l,H,g);if(f=Math.max(0,this.options.gap-(this.options.arrowShow?r:0)),w(l,x,f),this.options.arrowShow){for(r=this.options.arrowSize,i=n.extend({},l),t=this.userContainer.css("border-color")||this.userContainer.css("background-color")||"white",R=0,P=b.length;P>R;R++)H=b[R],D=M[H],H!==y&&(o=D===A?t:"transparent",i["border-"+D]=""+r+"px solid "+o);w(l,M[y],r),T.call(b,C)>=0&&w(i,M[C],2*r)}else this.arrow.hide();return T.call(F,E)>=0?(w(l,"left",k(C,a,d)),i&&w(i,"left",k(C,r,p))):T.call(m,E)>=0&&(w(l,"top",k(C,s,h)),i&&w(i,"top",k(C,r,c))),this.container.is(":visible")&&(l.display="block"),this.container.removeAttr("style").css(l),i?this.arrow.removeAttr("style").css(i):e},t.prototype.getPosition=function(){var t,i,n,e,r,o,s,a;if(i=this.options.position||(this.elem?this.options.elementPosition:this.options.globalPosition),t=x(i),0===t.length&&(t[0]="b"),n=t[0],0>T.call(b,n))throw"Must be one of ["+b+"]";return(1===t.length||(e=t[0],T.call(F,e)>=0&&(r=t[1],0>T.call(m,r)))||(o=t[0],T.call(m,o)>=0&&(s=t[1],0>T.call(F,s))))&&(t[1]=(a=t[0],T.call(m,a)>=0?"m":"l")),2===t.length&&(t[2]=t[1]),t},t.prototype.getStyle=function(t){var i;if(t||(t=this.options.style),t||(t="default"),i=D[t],!i)throw"Missing style: "+t;return i},t.prototype.updateClasses=function(){var t,i;return t=["base"],n.isArray(this.options.className)?t=t.concat(this.options.className):this.options.className&&t.push(this.options.className),i=this.getStyle(),t=n.map(t,function(t){return""+C+"-"+i.name+"-"+t}).join(" "),this.userContainer.attr("class",t)},t.prototype.run=function(t,i){var r,o,a,l,h,u=this;if(n.isPlainObject(i)?n.extend(this.options,i):"string"===n.type(i)&&(this.options.className=i),this.container&&!t)return this.show(!1),e;if(this.container||t){o={},n.isPlainObject(t)?o=t:o[s]=t;for(a in o)r=o[a],l=this.userFields[a],l&&("text"===l&&(r=c(r),this.options.breakNewLines&&(r=r.replace(/\n/g,"<br/>"))),h=a===s?"":"="+a,p(this.userContainer,"[data-notify-"+l+h+"]").html(r));return this.updateClasses(),this.elem?this.setElementPosition():this.setGlobalPosition(),this.show(!0),this.options.autoHide?(clearTimeout(this.autohideTimer),this.autohideTimer=setTimeout(function(){return u.show(!1)},this.options.autoHideDelay)):e}},t.prototype.destroy=function(){return this.wrapper.remove()},t}(),n[S]=function(t,i,e){return t&&t.nodeName||t.jquery?n(t)[S](i,e):(e=i,i=t,new r(null,i,e)),t},n.fn[S]=function(t,i){return n(this).each(function(){var e;return e=d(n(this)).data(C),e?e.run(t,i):new r(n(this),t,i)}),this},n.extend(n[S],{defaults:h,addStyle:o,pluginOptions:E,getStyle:f,insertCSS:y}),n(function(){return y(a.css).attr("id","core-notify"),n(i).on("click","."+C+"-hidable",function(){return n(this).trigger("notify-hide")}),n(i).on("notify-hide","."+C+"-wrapper",function(){var t;return null!=(t=n(this).data(C))?t.show(!1):void 0})})})(window,document,jQuery),$.notify.addStyle("bootstrap",{html:"<div>\n<span data-notify-text></span>\n</div>",classes:{base:{"font-weight":"bold",padding:"8px 15px 8px 14px","text-shadow":"0 1px 0 rgba(255, 255, 255, 0.5)","background-color":"#fcf8e3",border:"1px solid #fbeed5","border-radius":"4px","white-space":"nowrap","padding-left":"25px","background-repeat":"no-repeat","background-position":"3px 7px"},error:{color:"#B94A48","background-color":"#F2DEDE","border-color":"#EED3D7","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAtRJREFUeNqkVc1u00AQHq+dOD+0poIQfkIjalW0SEGqRMuRnHos3DjwAH0ArlyQeANOOSMeAA5VjyBxKBQhgSpVUKKQNGloFdw4cWw2jtfMOna6JOUArDTazXi/b3dm55socPqQhFka++aHBsI8GsopRJERNFlY88FCEk9Yiwf8RhgRyaHFQpPHCDmZG5oX2ui2yilkcTT1AcDsbYC1NMAyOi7zTX2Agx7A9luAl88BauiiQ/cJaZQfIpAlngDcvZZMrl8vFPK5+XktrWlx3/ehZ5r9+t6e+WVnp1pxnNIjgBe4/6dAysQc8dsmHwPcW9C0h3fW1hans1ltwJhy0GxK7XZbUlMp5Ww2eyan6+ft/f2FAqXGK4CvQk5HueFz7D6GOZtIrK+srupdx1GRBBqNBtzc2AiMr7nPplRdKhb1q6q6zjFhrklEFOUutoQ50xcX86ZlqaZpQrfbBdu2R6/G19zX6XSgh6RX5ubyHCM8nqSID6ICrGiZjGYYxojEsiw4PDwMSL5VKsC8Yf4VRYFzMzMaxwjlJSlCyAQ9l0CW44PBADzXhe7xMdi9HtTrdYjFYkDQL0cn4Xdq2/EAE+InCnvADTf2eah4Sx9vExQjkqXT6aAERICMewd/UAp/IeYANM2joxt+q5VI+ieq2i0Wg3l6DNzHwTERPgo1ko7XBXj3vdlsT2F+UuhIhYkp7u7CarkcrFOCtR3H5JiwbAIeImjT/YQKKBtGjRFCU5IUgFRe7fF4cCNVIPMYo3VKqxwjyNAXNepuopyqnld602qVsfRpEkkz+GFL1wPj6ySXBpJtWVa5xlhpcyhBNwpZHmtX8AGgfIExo0ZpzkWVTBGiXCSEaHh62/PoR0p/vHaczxXGnj4bSo+G78lELU80h1uogBwWLf5YlsPmgDEd4M236xjm+8nm4IuE/9u+/PH2JXZfbwz4zw1WbO+SQPpXfwG/BBgAhCNZiSb/pOQAAAAASUVORK5CYII=)"},success:{color:"#468847","background-color":"#DFF0D8","border-color":"#D6E9C6","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAutJREFUeNq0lctPE0Ecx38zu/RFS1EryqtgJFA08YCiMZIAQQ4eRG8eDGdPJiYeTIwHTfwPiAcvXIwXLwoXPaDxkWgQ6islKlJLSQWLUraPLTv7Gme32zoF9KSTfLO7v53vZ3d/M7/fIth+IO6INt2jjoA7bjHCJoAlzCRw59YwHYjBnfMPqAKWQYKjGkfCJqAF0xwZjipQtA3MxeSG87VhOOYegVrUCy7UZM9S6TLIdAamySTclZdYhFhRHloGYg7mgZv1Zzztvgud7V1tbQ2twYA34LJmF4p5dXF1KTufnE+SxeJtuCZNsLDCQU0+RyKTF27Unw101l8e6hns3u0PBalORVVVkcaEKBJDgV3+cGM4tKKmI+ohlIGnygKX00rSBfszz/n2uXv81wd6+rt1orsZCHRdr1Imk2F2Kob3hutSxW8thsd8AXNaln9D7CTfA6O+0UgkMuwVvEFFUbbAcrkcTA8+AtOk8E6KiQiDmMFSDqZItAzEVQviRkdDdaFgPp8HSZKAEAL5Qh7Sq2lIJBJwv2scUqkUnKoZgNhcDKhKg5aH+1IkcouCAdFGAQsuWZYhOjwFHQ96oagWgRoUov1T9kRBEODAwxM2QtEUl+Wp+Ln9VRo6BcMw4ErHRYjH4/B26AlQoQQTRdHWwcd9AH57+UAXddvDD37DmrBBV34WfqiXPl61g+vr6xA9zsGeM9gOdsNXkgpEtTwVvwOklXLKm6+/p5ezwk4B+j6droBs2CsGa/gNs6RIxazl4Tc25mpTgw/apPR1LYlNRFAzgsOxkyXYLIM1V8NMwyAkJSctD1eGVKiq5wWjSPdjmeTkiKvVW4f2YPHWl3GAVq6ymcyCTgovM3FzyRiDe2TaKcEKsLpJvNHjZgPNqEtyi6mZIm4SRFyLMUsONSSdkPeFtY1n0mczoY3BHTLhwPRy9/lzcziCw9ACI+yql0VLzcGAZbYSM5CCSZg1/9oc/nn7+i8N9p/8An4JMADxhH+xHfuiKwAAAABJRU5ErkJggg==)"},info:{color:"#3A87AD","background-color":"#D9EDF7","border-color":"#BCE8F1","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QYFAhkSsdes/QAAA8dJREFUOMvVlGtMW2UYx//POaWHXg6lLaW0ypAtw1UCgbniNOLcVOLmAjHZolOYlxmTGXVZdAnRfXQm+7SoU4mXaOaiZsEpC9FkiQs6Z6bdCnNYruM6KNBw6YWewzl9z+sHImEWv+vz7XmT95f/+3/+7wP814v+efDOV3/SoX3lHAA+6ODeUFfMfjOWMADgdk+eEKz0pF7aQdMAcOKLLjrcVMVX3xdWN29/GhYP7SvnP0cWfS8caSkfHZsPE9Fgnt02JNutQ0QYHB2dDz9/pKX8QjjuO9xUxd/66HdxTeCHZ3rojQObGQBcuNjfplkD3b19Y/6MrimSaKgSMmpGU5WevmE/swa6Oy73tQHA0Rdr2Mmv/6A1n9w9suQ7097Z9lM4FlTgTDrzZTu4StXVfpiI48rVcUDM5cmEksrFnHxfpTtU/3BFQzCQF/2bYVoNbH7zmItbSoMj40JSzmMyX5qDvriA7QdrIIpA+3cdsMpu0nXI8cV0MtKXCPZev+gCEM1S2NHPvWfP/hL+7FSr3+0p5RBEyhEN5JCKYr8XnASMT0xBNyzQGQeI8fjsGD39RMPk7se2bd5ZtTyoFYXftF6y37gx7NeUtJJOTFlAHDZLDuILU3j3+H5oOrD3yWbIztugaAzgnBKJuBLpGfQrS8wO4FZgV+c1IxaLgWVU0tMLEETCos4xMzEIv9cJXQcyagIwigDGwJgOAtHAwAhisQUjy0ORGERiELgG4iakkzo4MYAxcM5hAMi1WWG1yYCJIcMUaBkVRLdGeSU2995TLWzcUAzONJ7J6FBVBYIggMzmFbvdBV44Corg8vjhzC+EJEl8U1kJtgYrhCzgc/vvTwXKSib1paRFVRVORDAJAsw5FuTaJEhWM2SHB3mOAlhkNxwuLzeJsGwqWzf5TFNdKgtY5qHp6ZFf67Y/sAVadCaVY5YACDDb3Oi4NIjLnWMw2QthCBIsVhsUTU9tvXsjeq9+X1d75/KEs4LNOfcdf/+HthMnvwxOD0wmHaXr7ZItn2wuH2SnBzbZAbPJwpPx+VQuzcm7dgRCB57a1uBzUDRL4bfnI0RE0eaXd9W89mpjqHZnUI5Hh2l2dkZZUhOqpi2qSmpOmZ64Tuu9qlz/SEXo6MEHa3wOip46F1n7633eekV8ds8Wxjn37Wl63VVa+ej5oeEZ/82ZBETJjpJ1Rbij2D3Z/1trXUvLsblCK0XfOx0SX2kMsn9dX+d+7Kf6h8o4AIykuffjT8L20LU+w4AZd5VvEPY+XpWqLV327HR7DzXuDnD8r+ovkBehJ8i+y8YAAAAASUVORK5CYII=)"},warn:{color:"#C09853","background-color":"#FCF8E3","border-color":"#FBEED5","background-image":"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAABJlBMVEXr6eb/2oD/wi7/xjr/0mP/ykf/tQD/vBj/3o7/uQ//vyL/twebhgD/4pzX1K3z8e349vK6tHCilCWbiQymn0jGworr6dXQza3HxcKkn1vWvV/5uRfk4dXZ1bD18+/52YebiAmyr5S9mhCzrWq5t6ufjRH54aLs0oS+qD751XqPhAybhwXsujG3sm+Zk0PTwG6Shg+PhhObhwOPgQL4zV2nlyrf27uLfgCPhRHu7OmLgAafkyiWkD3l49ibiAfTs0C+lgCniwD4sgDJxqOilzDWowWFfAH08uebig6qpFHBvH/aw26FfQTQzsvy8OyEfz20r3jAvaKbhgG9q0nc2LbZxXanoUu/u5WSggCtp1anpJKdmFz/zlX/1nGJiYmuq5Dx7+sAAADoPUZSAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfdBgUBGhh4aah5AAAAlklEQVQY02NgoBIIE8EUcwn1FkIXM1Tj5dDUQhPU502Mi7XXQxGz5uVIjGOJUUUW81HnYEyMi2HVcUOICQZzMMYmxrEyMylJwgUt5BljWRLjmJm4pI1hYp5SQLGYxDgmLnZOVxuooClIDKgXKMbN5ggV1ACLJcaBxNgcoiGCBiZwdWxOETBDrTyEFey0jYJ4eHjMGWgEAIpRFRCUt08qAAAAAElFTkSuQmCC)"}}});
;(function (original) {
  jQuery.fn.clone = function () {
    var result           = original.apply(this, arguments),
        my_textareas     = this.find('textarea').add(this.filter('textarea')),
        result_textareas = result.find('textarea').add(result.filter('textarea')),
        my_selects       = this.find('select').add(this.filter('select')),
        result_selects   = result.find('select').add(result.filter('select'));

    for (var i = 0, l = my_textareas.length; i < l; ++i) $(result_textareas[i]).val($(my_textareas[i]).val());
    for (var i = 0, l = my_selects.length;   i < l; ++i) {
      for (var j = 0, m = my_selects[i].options.length; j < m; ++j) {
        if (my_selects[i].options[j].selected === true) {
          result_selects[i].options[j].selected = true;
        }
      }
    }
    return result;
  };
})(jQuery.fn.clone);

;(function($) {
  "use strict";
 
  /**
   * todo: row status (unsaved, saved, new, validation_error)
   * before page unload, alert user if there is unsaved information
   * triggers add row, remove row
   * validation?
   * button classes and options to override
   */
 
  var duplicator = function(data) {
    var target = $(this);
 
    if (target.find('.duplicator_container').length)
      return target;
 
    if (target.css('position') === 'static') {
      target.css('position', 'relative');
    }
 
    var _default = {
      row: '.row',
      id_field: 'id',
      buttons: {
        add_row: ['<i class="fa fa-plus"></i> Add New Row',
          function() {
            /* no arguments because add_row expect the arguments to be the same as remove row */
            fn.add_row();
          }
        ],
      },
      row_buttons: {
        remove_row: ['<i class="fa fa-minus"></i>',
          function() {
            fn.remove_row.apply(this, arguments);
          }
        ],
        save_row: ['<i class="fa fa-floppy-o"></i>',
          function() {
            fn.save_row.apply(this, arguments);
          }
        ],
      },
      no_data_message: '<p>No data found.</p>',
      callbacks: {},
      button_class: 'btn'
    };
    var data = data || {};
    data = $.extend(true, {}, _default, data);
    var options = data;
 
    var row = $(data.row, target)
      .first()
      .wrap('<div class="duplicator_container"></div>')
      .css('position', 'relative');
    if (row.length === 0) {
      throw data.row + ' not found in target element';
      return;
    };
    $('input:not([type=checkbox],[type=radio]),select,textarea', row).val('');
    $('input[type=checkbox],input[type=radio]', row).prop('checked', '');
    $(data.row, target).remove();
 
    var container = $('.duplicator_container', target);
 
    var fn = {
      init: function() {
        var controls = $('<div class="duplicator_controls"></div>')
          .css({
            position: 'absolute',
            right: '0px',
            bottom:'-10px',
          });
        $.each(data.buttons, function(name) {
          var fn = this;
          var btn_text = name;
          if (this.length) {
            if (this.length === 2) {
              fn = this[1];
              btn_text = this[0]
            } else {
              fn = this[0];
            }
          };
          if (typeof fn === 'function') {
            var that = fn;
            var button = $('<button></button>')
              .addClass(data.button_class)
              .html(btn_text)
              .on('click', function(e) {
                var args = [e, name, target, row.clone(), data];
                if (data.callbacks[name] && typeof data.callbacks[name] === 'function') {
                  if (data.callbacks[name].apply(button, args) !== false) {
                    that.apply(button, args);
                  }
                } else {
                  that.apply(button, args);
                }
              });
            controls.append(button);
          };
        });
        controls.prependTo(target);
        fn.populate_rows_data();
      },
      make_row_controls: function(row) {
        var controls = $('<div class="duplicator_row_controls btn-group btn-group-xs"></div>')
          .css({
            position: 'absolute',
            top: '0px',
            right: '7px',
            'z-index': 10
          })
          .hide();
        $.each(data.row_buttons, function(name) {
          var fn = this;
          var btn_text = name;
          if (this.length) {
            if (this.length === 2) {
              fn = this[1];
              btn_text = this[0];
            } else {
              fn = this[0];
            }
          };
          if (typeof fn === 'function') {
            var that = fn;
            var button = $('<button></button>')
              .addClass(data.button_class)
              .html(btn_text)
              .css({
                clear: 'both',
                width: '24px',
                float: 'right'
              })
              .on('click', function(e) {
                var args = [e, name, target, row, data];
                if (data.callbacks[name] && typeof data.callbacks[name] === 'function') {
                  if (data.callbacks[name].apply(button, args) !== false) {
                    that.apply(button, args);
                  }
                } else {
                  that.apply(button, args);
                }
              });
            controls.append(button);
          };
        });
        row.hover(function() {
          controls.show();
        }, function() {
          controls.hide();
        });
        controls.prependTo(row);
      },
      populate_row_data: function(row, data) {
        $.each(data, function(key, value){
          var input = $('[name=' + key + ']', row);
          if(input.length) {
            if(['checkbox', 'radio'].indexOf(input.attr('type')) > -1) {
              if(value)
                input.prop('checked',true);
            } else
              input.val(value);
          }
        });
        row.data(data);
      },
      populate_rows_data: function() {
        if(data.remotes.get) {
          fn.get_rows_data(function(datarows){
            datarows.forEach(function(datarow){
              fn.add_row(datarow);
            });
          });
        }
      },
      add_row: function(data) {
        var new_row = row.clone();
        $('input,select,textarea', new_row).prop({
          disabled: '',
          readonly: ''
        });
        if (data) {
          fn.populate_row_data(new_row, data);
        }
        new_row.appendTo(container);
        $('input,select,textarea', new_row).first().focus();
        fn.make_row_controls(new_row);
        if(options.onAddRow) {
          options.onAddRow.call(window, new_row);
        }
      },
      remove_row: function(event, action, instance, row) {
        bootbox.confirm('Are you sure you want to remove this row?', function(res){
          if(res) {
            var row_data = row.data();
            var remove_row_el = function() {
              row.remove();
              row.data({});
            }
            if (row_data[data.id_field] && data.remotes.delete) {
              return fn.remove_remote_row(row_data, remove_row_el);
            }
            remove_row_el();
            if(options.onRemoveRow) {
              options.onRemoveRow.call(window, new_row);
            }
          }
        })
      },
      save_row: function(event, action, instance, row) {
        var form_data = $('<form></form>')
          .append(row.clone())
          .serializeJSON();
        var row_data = row.data();
        var apply_row_data = function(form_data) {
          row.data(form_data);
        }
        if(row_data[data.id_field])
          form_data = $.extend(true, {id: row_data[data.id_field]}, form_data);
        if (form_data[data.id_field] && data.remotes.put) {
          return fn.update_row_data(form_data, apply_row_data);
        } else if (data.remotes.post) {
          return fn.insert_row_data(form_data, apply_row_data);
        }
        apply_row_data(form_data);
      },
      /* utils */
      getPath: function(type, id) {
        var path = data.remotes[type];
        if (id) {
          if (path.split('').pop() !== '/') {
            path = path + '/';
          }
          path = path + id;
        }
        return path;
      },
 
      /* remotes */
      get_rows_data: function(callback) {
        $.getJSON(fn.getPath('get'))
        .success(callback)
        .error(function(){
          $.notify('Problem getting data.', 'error');
        });
      },
      remove_remote_row: function(row_data, callback) {
        $.ajax(fn.getPath('delete', row_data[data.id_field]), {
          method: 'DELETE',
        }).success(function() {
          $.notify('Data deleted', 'success');
          callback();
        }).error(function(){
          $.notify('Problem deleting data.', 'error');
        });
      },
      insert_row_data: function(form_data, callback) {
        $.ajax(fn.getPath('post'), {
          method: 'POST',
          data: form_data,
        }).success(function(return_data) {
          if (typeof return_data !== 'object') {
            try {
              return_data = JSON.parse(return_data);
            } catch (e) {}
          }
          $.notify('Data inserted', 'success');
          callback(return_data);
        }).error(function(){
          $.notify('Problem inserting data.', 'error');
        });
      },
      update_row_data: function(form_data, callback) {
        $.ajax(fn.getPath('put', form_data[data.id_field]), {
          method: 'PUT',
          data: form_data,
        }).success(function(return_data) {
          $.notify('Data updated', 'success');
          callback(return_data);
        }).error(function(){
          $.notify('Problem updating data.', 'error');
        });
      }
    };
 
    fn.init();
    return target;
  }
 
  $.fn.duplicator = duplicator;
 
 
}).call(this, (jQuery || Zepto));
