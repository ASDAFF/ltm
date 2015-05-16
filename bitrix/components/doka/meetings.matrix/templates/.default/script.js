$( document ).ready(function() {
	$(".times-list a").on( "click", function(event) {
		event.preventDefault();
		if($(this).parent().find("select").length > 0){
			var timeChoose = $(this).parent().find("select").val();
			var recHref = $(this).attr("href")+"&to="+timeChoose;
			window.open(recHref,'particip_write', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
		}
		else{
			var insertHtml = $("#time-list"+$(this).attr("data-timeslot")).html();			
			$(this).parent().append(insertHtml);
		}
		
	});

});

 (function(e){e.fn.fixedHeaderTable=function(t){var n={width:"100%",height:"100%",themeClass:"fht-default",borderCollapse:!0,fixedColumns:0,fixedColumn:!1,sortable:!1,autoShow:!0,footer:!1,cloneHeadToFoot:!1,autoResize:!1,create:null},r={},i={init:function(t){r=e.extend({},n,t);return this.each(function(){var t=e(this);if(s._isTable(t)){i.setup.apply(this,Array.prototype.slice.call(arguments,1));e.isFunction(r.create)&&r.create.call(this)}else e.error("Invalid table mark-up")})},setup:function(){var t=e(this),n=this,o=t.find("thead"),u=t.find("tfoot"),a=0,f,l,c,h,p;r.originalTable=e(this).clone();r.includePadding=s._isPaddingIncludedWithWidth();r.scrollbarOffset=s._getScrollbarWidth();r.themeClassName=r.themeClass;r.width.search("%")>-1?p=t.parent().width()-r.scrollbarOffset:p=r.width-r.scrollbarOffset;t.css({width:p});if(!t.closest(".fht-table-wrapper").length){t.addClass("fht-table");t.wrap('<div class="fht-table-wrapper"></div>')}f=t.closest(".fht-table-wrapper");r.fixedColumn==1&&r.fixedColumns<=0&&(r.fixedColumns=1);if(r.fixedColumns>0&&f.find(".fht-fixed-column").length==0){t.wrap('<div class="fht-fixed-body"></div>');e('<div class="fht-fixed-column"></div>').prependTo(f);h=f.find(".fht-fixed-body")}f.css({width:r.width,height:r.height}).addClass(r.themeClassName);t.hasClass("fht-table-init")||t.wrap('<div class="fht-tbody"></div>');c=t.closest(".fht-tbody");var d=s._getTableProps(t);s._setupClone(c,d.tbody);if(!t.hasClass("fht-table-init")){r.fixedColumns>0?l=e('<div class="fht-thead"><table class="fht-table"></table></div>').prependTo(h):l=e('<div class="fht-thead"><table class="fht-table"></table></div>').prependTo(f);l.find("table.fht-table").addClass(r.originalTable.attr("class"));o.clone().appendTo(l.find("table"))}else l=f.find("div.fht-thead");s._setupClone(l,d.thead);t.css({"margin-top":-l.outerHeight(!0)});if(r.footer==1){s._setupTableFooter(t,n,d);u.length||(u=f.find("div.fht-tfoot table"));a=u.outerHeight(!0)}var v=f.height()-o.outerHeight(!0)-a-d.border;c.css({height:v});t.addClass("fht-table-init");typeof r.altClass!="undefined"&&i.altRows.apply(n);r.fixedColumns>0&&s._setupFixedColumn(t,n,d);r.autoShow||f.hide();s._bindScroll(c,d);return n},resize:function(){var e=this;return e},altRows:function(t){var n=e(this),i=typeof t!="undefined"?t:r.altClass;n.closest(".fht-table-wrapper").find("tbody tr:odd:not(:hidden)").addClass(i)},show:function(t,n,r){var i=e(this),s=this,o=i.closest(".fht-table-wrapper");if(typeof t!="undefined"&&typeof t=="number"){o.show(t,function(){e.isFunction(n)&&n.call(this)});return s}if(typeof t!="undefined"&&typeof t=="string"&&typeof n!="undefined"&&typeof n=="number"){o.show(t,n,function(){e.isFunction(r)&&r.call(this)});return s}i.closest(".fht-table-wrapper").show();e.isFunction(t)&&t.call(this);return s},hide:function(t,n,r){var i=e(this),s=this,o=i.closest(".fht-table-wrapper");if(typeof t!="undefined"&&typeof t=="number"){o.hide(t,function(){e.isFunction(r)&&r.call(this)});return s}if(typeof t!="undefined"&&typeof t=="string"&&typeof n!="undefined"&&typeof n=="number"){o.hide(t,n,function(){e.isFunction(r)&&r.call(this)});return s}i.closest(".fht-table-wrapper").hide();e.isFunction(r)&&r.call(this);return s},destroy:function(){var t=e(this),n=this,r=t.closest(".fht-table-wrapper");t.insertBefore(r).removeAttr("style").append(r.find("tfoot")).removeClass("fht-table fht-table-init").find(".fht-cell").remove();r.remove();return n}},s={_isTable:function(e){var t=e,n=t.is("table"),r=t.find("thead").length>0,i=t.find("tbody").length>0;return n&&r&&i?!0:!1},_bindScroll:function(e){var t=e,n=t.closest(".fht-table-wrapper"),i=t.siblings(".fht-thead"),s=t.siblings(".fht-tfoot");t.bind("scroll",function(){if(r.fixedColumns>0){var e=n.find(".fht-fixed-column");e.find(".fht-tbody table").css({"margin-top":-t.scrollTop()})}i.find("table").css({"margin-left":-this.scrollLeft});(r.footer||r.cloneHeadToFoot)&&s.find("table").css({"margin-left":-this.scrollLeft})})},_fixHeightWithCss:function(e,t){r.includePadding?e.css({height:e.height()+t.border}):e.css({height:e.parent().height()+t.border})},_fixWidthWithCss:function(t,n,i){r.includePadding?t.each(function(){e(this).css({width:i==undefined?e(this).width()+n.border:i+n.border})}):t.each(function(){e(this).css({width:i==undefined?e(this).parent().width()+n.border:i+n.border})})},_setupFixedColumn:function(t,n,i){var o=t,u=o.closest(".fht-table-wrapper"),a=u.find(".fht-fixed-body"),f=u.find(".fht-fixed-column"),l=e('<div class="fht-thead"><table class="fht-table"><thead><tr></tr></thead></table></div>'),c=e('<div class="fht-tbody"><table class="fht-table"><tbody></tbody></table></div>'),h=e('<div class="fht-tfoot"><table class="fht-table"><tfoot><tr></tr></tfoot></table></div>'),p=u.width(),d=a.find(".fht-tbody").height()-r.scrollbarOffset,v,m,g,y,b;l.find("table.fht-table").addClass(r.originalTable.attr("class"));c.find("table.fht-table").addClass(r.originalTable.attr("class"));h.find("table.fht-table").addClass(r.originalTable.attr("class"));v=a.find(".fht-thead thead tr > *:lt("+r.fixedColumns+")");g=r.fixedColumns*i.border;v.each(function(){g+=e(this).outerWidth(!0)});s._fixHeightWithCss(v,i);s._fixWidthWithCss(v,i);var w=[];v.each(function(){w.push(e(this).width())});b="tbody tr > *:not(:nth-child(n+"+(r.fixedColumns+1)+"))";m=a.find(b).each(function(t){s._fixHeightWithCss(e(this),i);s._fixWidthWithCss(e(this),i,w[t%r.fixedColumns])});l.appendTo(f).find("tr").append(v.clone());c.appendTo(f).css({"margin-top":-1,height:d+i.border});m.each(function(t){if(t%r.fixedColumns==0){y=e("<tr></tr>").appendTo(c.find("tbody"));r.altClass&&e(this).parent().hasClass(r.altClass)&&y.addClass(r.altClass)}e(this).clone().appendTo(y)});f.css({height:0,width:g});var E=f.find(".fht-tbody .fht-table").height()-f.find(".fht-tbody").height();f.find(".fht-table").bind("mousewheel",function(t,n,r,i){if(i==0)return;var s=parseInt(e(this).css("marginTop"),10)+(i>0?120:-120);s>0&&(s=0);s<-E&&(s=-E);e(this).css("marginTop",s);a.find(".fht-tbody").scrollTop(-s).scroll();return!1});a.css({width:p});if(r.footer==1||r.cloneHeadToFoot==1){var S=a.find(".fht-tfoot tr > *:lt("+r.fixedColumns+")"),x;s._fixHeightWithCss(S,i);h.appendTo(f).find("tr").append(S.clone());x=h.find("table").innerWidth();h.css({top:r.scrollbarOffset,width:x})}},_setupTableFooter:function(t,n,i){var o=t,u=o.closest(".fht-table-wrapper"),a=o.find("tfoot"),f=u.find("div.fht-tfoot");f.length||(r.fixedColumns>0?f=e('<div class="fht-tfoot"><table class="fht-table"></table></div>').appendTo(u.find(".fht-fixed-body")):f=e('<div class="fht-tfoot"><table class="fht-table"></table></div>').appendTo(u));f.find("table.fht-table").addClass(r.originalTable.attr("class"));switch(!0){case!a.length&&r.cloneHeadToFoot==1&&r.footer==1:var l=u.find("div.fht-thead");f.empty();l.find("table").clone().appendTo(f);break;case a.length&&r.cloneHeadToFoot==0&&r.footer==1:f.find("table").append(a).css({"margin-top":-i.border});s._setupClone(f,i.tfoot)}},_getTableProps:function(t){var n={thead:{},tbody:{},tfoot:{},border:0},i=1;r.borderCollapse==1&&(i=2);n.border=(t.find("th:first-child").outerWidth()-t.find("th:first-child").innerWidth())/i;t.find("thead tr:first-child > *").each(function(t){n.thead[t]=e(this).width()+n.border});t.find("tfoot tr:first-child > *").each(function(t){n.tfoot[t]=e(this).width()+n.border});t.find("tbody tr:first-child > *").each(function(t){n.tbody[t]=e(this).width()+n.border});return n},_setupClone:function(t,n){var i=t,s=i.find("thead").length?"thead tr:first-child > *":i.find("tfoot").length?"tfoot tr:first-child > *":"tbody tr:first-child > *",o;i.find(s).each(function(t){o=e(this).find("div.fht-cell").length?e(this).find("div.fht-cell"):e('<div class="fht-cell"></div>').appendTo(e(this));o.css({width:parseInt(n[t],10)});if(!e(this).closest(".fht-tbody").length&&e(this).is(":last-child")&&!e(this).closest(".fht-fixed-column").length){var i=Math.max((e(this).innerWidth()-e(this).width())/2,r.scrollbarOffset);e(this).css({"padding-right":i+"px"})}})},_isPaddingIncludedWithWidth:function(){var t=e('<table class="fht-table"><tr><td style="padding: 10px; font-size: 10px;">test</td></tr></table>'),n,i;t.addClass(r.originalTable.attr("class"));t.appendTo("body");n=t.find("td").height();t.find("td").css("height",t.find("tr").height());i=t.find("td").height();t.remove();return n!=i?!0:!1},_getScrollbarWidth:function(){var t=0;if(!t)if(/msie/.test(navigator.userAgent.toLowerCase())){var n=e('<textarea cols="10" rows="2"></textarea>').css({position:"absolute",top:-1e3,left:-1e3}).appendTo("body"),r=e('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>').css({position:"absolute",top:-1e3,left:-1e3}).appendTo("body");t=n.width()-r.width()+2;n.add(r).remove()}else{var i=e("<div />").css({width:100,height:100,overflow:"auto",position:"absolute",top:-1e3,left:-1e3}).prependTo("body").append("<div />").find("div").css({width:"100%",height:200});t=100-i.width();i.parent().remove()}return t}};if(i[t])return i[t].apply(this,Array.prototype.slice.call(arguments,1));if(typeof t=="object"||!t)return i.init.apply(this,arguments);e.error('Method "'+t+'" does not exist in fixedHeaderTable plugin!')}})(jQuery);

