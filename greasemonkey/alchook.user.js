// ==UserScript==
// @name ALC test
// @namespace http://d.hatena.ne.jp/bannyan/
// @include   http://www.alc.co.jp/*
// @include   http://eow.alc.co.jp/*/UTF-8/*
//
// @require http://zend.mi.nu/js/jquery-1.3.2.min.js
//
// @resource normal http://zend.mi.nu/images/alchook.png
// @resource on     http://zend.mi.nu/images/alchook_on.png

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

	var AlcHook = {};
	AlcHook = {

		init: function() {
			this.checkAuthenticated();
			this.setStyle();
			this.isExecutable();
			this.postData();
		},

		setStyle: function() {
	        w.document.body.appendChild(
                update(document.createElement('div'), {
                    'style': "background: url(" + GM_getResourceURL('normal') + ")",
                    'id'  : 'alchook'
                })	
            );

            $('#alchook').mouseover(function() {
            	$(this).css('background', "url(" + GM_getResourceURL('on') + ")");
            }).mouseout(function() {
            	$(this).css('background', "url(" + GM_getResourceURL('normal') + ")");
           	}).wrap('<a href="http://zend.mi.nu/"> target="_blank"');

           	$
	    },

		// TODO cache の実装を考える
		checkAuthenticated: function() {
		    GM_xmlhttpRequest({
		        method : 'GET',
		        url : 'http://zend.mi.nu/api/check/',
		        onload : this.bind(this, function(response) {
					if (response.responseText === 'OK') {
						console.log(response.responseText);
						return;
					} else {
						console.log(response.responseText);
						location.href = 'http://zend.mi.nu/index/session/';
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
		        url : 'http://zend.mi.nu/api/create/',
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

