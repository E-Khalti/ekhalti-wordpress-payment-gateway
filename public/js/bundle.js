(this.webpackJsonpwordpress_add_to_cart=this.webpackJsonpwordpress_add_to_cart||[]).push([[0],{35:function(t,e,a){t.exports=a(70)},40:function(t,e,a){},70:function(t,e,a){"use strict";a.r(e);var n=a(1),r=a.n(n),c=a(10),o=a.n(c),i=(a(40),a(11)),l=a(12),s=a(17),u=a(14),p=a(13),d=a(16),h=a(72),m=a(75),f=a(73),g=a(74),v=(a(41),a(33)),w=a.n(v),b=a(9),k=a.n(b),_=a(18),y=a(8),j=a.n(y);function N(){return(N=Object(_.a)(k.a.mark((function t(e,a){var n,r;return k.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n={headers:{"Content-Type":"multipart/form-data"}},(r=new FormData).append("action","ek_addtocart_button"),r.append("type",a.type),r.append("wp_nonce",a.wp_nonce),r.append("title",a.title),r.append("description",a.description),r.append("price",a.price),t.prev=8,t.next=11,j.a.post(e,r,n);case 11:return t.abrupt("return",t.sent);case 14:t.prev=14,t.t0=t.catch(8),console.error(t.t0);case 17:case"end":return t.stop()}}),t,null,[[8,14]])})))).apply(this,arguments)}function E(t,e){return x.apply(this,arguments)}function x(){return(x=Object(_.a)(k.a.mark((function t(e,a){var n,r;return k.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n={headers:{"Content-Type":"multipart/form-data"}},(r=new FormData).append("action",a.action),r.append("wp_nonce",a.wp_nonce),t.prev=4,t.next=7,j.a.post(e,r,n);case 7:return t.abrupt("return",t.sent);case 10:t.prev=10,t.t0=t.catch(4),console.error(t.t0);case 13:case"end":return t.stop()}}),t,null,[[4,10]])})))).apply(this,arguments)}function C(t,e){return O.apply(this,arguments)}function O(){return(O=Object(_.a)(k.a.mark((function t(e,a){var n,r,c;return k.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:for(c in n={headers:{"Content-Type":"multipart/form-data"}},r=new FormData,a)r.append(c,a[c]);return t.prev=3,t.next=6,j.a.post(e,r,n);case 6:return t.abrupt("return",t.sent);case 9:t.prev=9,t.t0=t.catch(3),console.error(t.t0);case 12:case"end":return t.stop()}}),t,null,[[3,9]])})))).apply(this,arguments)}var A=function(t){function e(t){var a;return Object(i.a)(this,e),(a=Object(s.a)(this,Object(u.a)(e).call(this,t))).state={quantity:1},a}return Object(d.a)(e,t),Object(l.a)(e,[{key:"render",value:function(){var t=this,e=this.props.product;return r.a.createElement("div",{className:"card",style:{marginBottom:"10px"}},r.a.createElement("div",{className:"card-body"},r.a.createElement("h4",{className:"card-title"},e.name),r.a.createElement("h5",{className:"card-text"},r.a.createElement("small",null,"price: "),"$",e.price),r.a.createElement("span",{className:"card-text text-success"},r.a.createElement("small",null,"Quantity: "),e.qty),r.a.createElement("button",{className:"btn btn-sm btn-warning float-right",onClick:function(){return t.props.remove(e)}},"Remove from cart")))}}]),e}(r.a.Component),S=function(t){function e(t){var a;return Object(i.a)(this,e),(a=Object(s.a)(this,Object(u.a)(e).call(this,t))).removeFromCart=function(t){var e=a.state.products.filter((function(e){return e.id!==t.id})),n={action:"ek_remove_from_cart",ref:t.ref,wp_nonce:a.props.config.cartNonce};C(a.props.config.ajaxurl,n).then((function(t){}));var r=a.state.total-t.qty*t.price;a.setState({products:e,total:r})},a.clearCart=function(){var t={action:"ek_clear_cart",wp_nonce:a.props.config.clearcartNonce};C(a.props.config.ajaxurl,t).then((function(t){})),a.setState({products:[]})},a.state={products:[],total:0,loading:!1},a.checkoutcart=a.checkoutcart.bind(Object(p.a)(a)),a}return Object(d.a)(e,t),Object(l.a)(e,[{key:"componentDidMount",value:function(){var t=this,e=this,a={action:"ek_get_cart",wp_nonce:this.props.config.cartNonce};e.setState({loading:!0}),C(this.props.config.ajaxurl,a).then((function(a){var n=a.data.products,r=[],c=0;for(var o in n){c+=n[o].price*n[o].quantity;var i={name:n[o].name,qty:n[o].quantity,price:n[o].price,id:n[o].id,ref:o};r.push(i)}t.setState({products:r,total:c}),e.setState({loading:!1})}))}},{key:"checkoutcart",value:function(){var t={action:"ek_addtocart_checkout",wp_nonce:this.props.config.checkoutNonce};C(this.props.config.ajaxurl,t).then((function(t){var e=t.data;"200"==e.status&&(window.location.href=e.link)}))}},{key:"render",value:function(){var t=this,e=this.state,a=e.products,n=e.total;return r.a.createElement("div",{className:" container"},a.map((function(e,a){return r.a.createElement(A,{product:e,remove:t.removeFromCart,key:a})})),a.length?r.a.createElement("div",null,r.a.createElement("h4",null,r.a.createElement("small",null,"Total Amount: "),r.a.createElement("span",{className:"float-right text-primary"},"$",n)),r.a.createElement("hr",null)):"",this.state.loading?r.a.createElement(w.a,{type:"spin",color:"#66666",height:30,width:30}):a.length?"":r.a.createElement("h3",{className:"text-warning"},"No item on the cart"),!this.state.loading&&a.length?r.a.createElement(r.a.Fragment,null,r.a.createElement("button",{className:"btn btn-success float-right",onClick:this.checkoutcart},"Checkout"),r.a.createElement("button",{className:"btn btn-danger float-right",onClick:this.clearCart,style:{marginRight:"10px"}},"Clear Cart")):"",r.a.createElement("br",null),r.a.createElement("br",null),r.a.createElement("br",null))}}]),e}(r.a.Component),q=function(t){function e(t){var a;return Object(i.a)(this,e),(a=Object(s.a)(this,Object(u.a)(e).call(this,t))).state={modal:!1,url:"",ajaxurl:"",cartNonce:"",clearcartNonce:"",checkoutNonce:"",loading:!0,portal:""},a.toggle=a.toggle.bind(Object(p.a)(a)),a}return Object(d.a)(e,t),Object(l.a)(e,[{key:"componentWillMount",value:function(){void 0!==window._siteinfo?(this.userID=window._siteinfo.userID,this.setState({url:window._siteinfo.url,ajaxurl:window._siteinfo.ajaxurl,cartNonce:window._siteinfo.cartNonce,clearcartNonce:window._siteinfo.clearcartNonce,checkoutNonce:window._siteinfo.checkoutNonce})):this.setState({url:"",ajaxurl:"/wp-admin/admin-ajax.php",cartNonce:""})}},{key:"addtocart",value:function(t){var e=this,a={action:"ek_buy_button",type:t.getAttribute("data-type"),wp_nonce:t.getAttribute("data-nonce"),id:t.getAttribute("data-id"),title:t.getAttribute("data-title"),description:t.getAttribute("data-description"),price:t.getAttribute("data-price")};(function(t,e){return N.apply(this,arguments)})(this.state.ajaxurl,a).then((function(t){t.data;e.toggle()})).catch((function(t){console.log(t)}))}},{key:"Checkout",value:function(t){console.log(t.getAttribute("data-id"));var e={action:"ek_addtocart_checkout",wp_nonce:t.getAttribute("data-nonce")};E(this.state.ajaxurl,e).then((function(t){var e=t.data;"200"==e.status&&(window.location.href=e.link)})).catch((function(t){console.log(t)}))}},{key:"getCart",value:function(){var t={action:"ek_get_cart",wp_nonce:this.state.cartNonce};E(this.state.ajaxurl,t).then((function(t){t.data})).catch((function(t){console.log(t)}))}},{key:"toggle",value:function(){this.setState((function(t){return{modal:!t.modal}}))}},{key:"render",value:function(){return r.a.createElement("div",null,r.a.createElement(h.a,{color:"danger",onClick:this.toggle},"Cool"),r.a.createElement(m.a,{isOpen:this.state.modal,toggle:this.toggle,className:"custom-map-modal"},r.a.createElement(f.a,{toggle:this.toggle}," Cart"),r.a.createElement(g.a,null,r.a.createElement(S,{config:{url:this.state.url,ajaxurl:this.state.ajaxurl,cartNonce:this.state.cartNonce,clearcartNonce:this.state.clearcartNonce,checkoutNonce:this.state.checkoutNonce}}))))}}]),e}(r.a.Component);Boolean("localhost"===window.location.hostname||"[::1]"===window.location.hostname||window.location.hostname.match(/^127(?:\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/));o.a.render(r.a.createElement(q,{ref:function(t){window.ourComponent=t}}),document.getElementById("root")),"serviceWorker"in navigator&&navigator.serviceWorker.ready.then((function(t){t.unregister()}))}},[[35,1,2]]]);
//# sourceMappingURL=main.a0c25bd0.chunk.js.map