// ==UserScript==
// @name ALC test
// @namespace http://d.hatena.ne.jp/bannyan/
// @include   http://www.alc.co.jp/*
// @include   http://eow.alc.co.jp/*/UTF-8/*

// @require http://alchook.mi.nu/js/jquery-1.3.2.min.js

// @resource normal http://alchook.mi.nu/images/alchook.png
// @resource on     http://alchook.mi.nu/images/alchook_on.png

// ==/UserScript==

GM_addStyle(<><![CDATA[

	#alchook {
	    top        : 5px;
	    right      : 5px;
	    width      : 128px;
	    height     : 23px;
	    position   : fixed;
	}

]]></>);

(function(w) {

	const ALKHOOK_URL = 'http://alchook.mi.nu/';

	var AlcHook = {};
	AlcHook = {

		init: function() {
			this.checkAuthenticated();
			this.addStyle();
			this.isExecutable();
			this.postData();
		},

		addStyle: function() {
	        w.document.body.appendChild(
                update(document.createElement('div'), {
                    'style': "background: url(" + GM_getResourceURL('normal') + ")",
                    'id'   : 'alchook'
                })	
            );

            $('#alchook').mouseover(function() {
            	$(this).css('background', "url(" + GM_getResourceURL('on') + ")");
            }).mouseout(function() {
            	$(this).css('background', "url(" + GM_getResourceURL('normal') + ")");
           	}).wrap('<a href="' + ALKHOOK_URL + '"> target="_blank"');

	    },

		// TODO cache の実装を考える
		checkAuthenticated: function() {
		    GM_xmlhttpRequest({
		        method : 'GET',
		        url : ALKHOOK_URL + 'api/session/',
		        onload : this.bind(this, function(response) {
		        	console.log(response.status);
					if (response.status === 200) {
						return;
					} else {
						location.href = ALKHOOK_URL;
						return;
					}
		        })
		    })
		},

		isExecutable: function() {
			if (location.hostname !== 'eow.alc.co.jp') return;
		},

		postData: function() {
			var search = location.pathname.split('/')[1];
			if (!search) return;
		    GM_xmlhttpRequest({
		        method : 'POST',
		        url : ALKHOOK_URL + 'api/create/',
				data: "query=" + encodeURIComponent(search),
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},

		        onload : function (response) {
					console.log(response);
		        }
		    })
		},

		bind: function(o,f){
			return function () {
				return f.apply(o,arguments)
			}
		}
	}

	AlcHook.init();

	function update(obj, params) {
		if(obj.setAttribute){
			for(var key in params)
			obj.setAttribute(key, params[key]);
		} else {
			for(var key in params)
			obj[key] = params[key];
		}
		return obj;
	}

}) (this.unsafeWindow || this);

