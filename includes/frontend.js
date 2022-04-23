!function(){"use strict";var e={n:function(t){var a=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(a,{a:a}),a},d:function(t,a){for(var n in a)e.o(a,n)&&!e.o(t,n)&&Object.defineProperty(t,n,{enumerable:!0,get:a[n]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,a=window.React,n=window.wp.apiFetch,l=e.n(n),r=window.wp.i18n;function c(e,t){let a=arguments.length>2&&void 0!==arguments[2]?arguments[2]:"...";if(0==e.length||0==t)return"";const n=e.split(" ");return n.length<=t?e:n.splice(0,t).join(" ")+a}function s(e,t){const a=navigator.language||navigator.userLanguage;e=new Date(1e3*e),t=new Date(1e3*t);let n={year:"numeric",month:"long",day:"numeric"};return e.getFullYear()===t.getFullYear()&&e.getMonth()===t.getMonth()&&e.getDate()===t.getDate()&&(n={year:"numeric",month:"long",day:"numeric",hour:"numeric",minute:"numeric"}),new Intl.DateTimeFormat(a,n).formatRange(e,t)}function i(e,t){const a=window.eventBlockLocale.language;return new Intl.DateTimeFormat(a,t).format(1e3*e)}var o=function(e){var a,n,l;const{showImages:i,showCategory:o,showLocation:m,showBookedUp:d,bookedUpWarningThreshold:u,excerptLength:g,textAlignment:_,showAudience:p,showSpeaker:h,item:v}=e,E=v.location&&["city","name"].includes(m)?v.location[m]:"";return(0,t.createElement)("div",{className:"card card--image-top card--primary card--shadow card--hover bg-white card--"+_},i&&(0,t.createElement)("a",{href:v.link,className:"card__image"},(0,t.createElement)("img",{src:null===(a=v.image)||void 0===a||null===(n=a.sizes)||void 0===n||null===(l=n.large)||void 0===l?void 0:l.url})),(0,t.createElement)("div",{className:"card__content"},(0,t.createElement)("a",{href:v.link,className:"card__hidden-link"}),v.category&&o&&(0,t.createElement)("span",{class:"card__label"},v.category.name),(0,t.createElement)("h2",{className:"card__title"},v.title),(0,t.createElement)("h4",{class:"card__subtitle text--primary"},s(v.start,v.end)),(0,t.createElement)("p",{className:"card__text"},c(v.excerpt,g)),p||h||m||d?(0,t.createElement)("div",{class:"card__footer card__subtitle pills pills--small"},p&&(null===(w=v.audience)||void 0===w?void 0:w.length)>0&&(0,t.createElement)("span",{className:"pills__item event__audience"},v.audience),"name"==h&&(null===(f=v.speaker)||void 0===f?void 0:f.id)&&(0,t.createElement)("span",{className:"pills__item event__speaker"},v.speaker.name),m&&(null===(k=v.location)||void 0===k?void 0:k.ID)&&(0,t.createElement)("span",{className:"pills__item event__location"},E),d&&v.bookings&&(d&&v.bookings.has_bookings?(null===(y=v.bookings)||void 0===y?void 0:y.spaces)>u?(0,t.createElement)(t.Fragment,null):(null===(N=v.bookings)||void 0===N?void 0:N.spaces)>0?(0,t.createElement)("span",{className:"pills__item pills__item--warning"},(0,r.__)("Nearly Booked up","events")):(0,t.createElement)("span",{className:"pills__item pills__item--error"},(0,r.__)("Booked up","events")):(0,t.createElement)(t.Fragment,null))):(0,t.createElement)(t.Fragment,null)));var w,f,k,y,N},m=function(e){var a,n,l,r,i,o;const{showImages:m,showCategory:d,showLocation:u,excerptLength:g,textAlignment:_,showAudience:p,showSpeaker:h,item:v}=e,E=v.location&&["city","name"].includes(u)?v.location[u]:"";return(0,t.createElement)("div",{className:"card card--image-left has-white-background card--shadow card--primary card--"+_},m&&(0,t.createElement)("a",{href:v.link,className:"card__image"},(0,t.createElement)("img",{src:null===(a=v.image)||void 0===a||null===(n=a.sizes)||void 0===n||null===(l=n.large)||void 0===l?void 0:l.url}),"image"==h&&v.speaker&&(0,t.createElement)("span",{className:"card__label card__label--image"},(0,t.createElement)("img",{src:null===(r=v.speaker.image)||void 0===r||null===(i=r.sizes)||void 0===i||null===(o=i.thumbnail)||void 0===o?void 0:o.url}),v.speaker.name)),(0,t.createElement)("div",{className:"card__content"},v.category&&d&&(0,t.createElement)("span",{class:"card__label"},v.category.name),(0,t.createElement)("a",{href:v.link,className:"card__title"},v.title),(0,t.createElement)("a",{href:v.link,class:"card__subtitle text--primary"},s(v.start,v.end)),(0,t.createElement)("a",{href:v.link,className:"card__text"},c(v.excerpt,g)),(p||h||u)&&(0,t.createElement)("div",{class:"card__footer card__subtitle card__pills"},p&&v.audience&&(0,t.createElement)("span",{className:"card__pill event__audience"},v.audience),"name"==h&&v.speaker&&(0,t.createElement)("span",{className:"card__pill event__audience"},v.speaker.name),u&&v.location&&(0,t.createElement)("span",{className:"card__pill event__audience"},E))))};function d(e){var a,n,l;const{showImages:r,showCategory:c,showLocation:o,excerptLength:m,textAlignment:d,showAudience:u,showSpeaker:g,item:_}=e,p=_.location&&["city","name"].includes(o)?_.location[o]:"";return(0,t.createElement)("a",{href:_.link,className:"description__item "+d},r&&(0,t.createElement)("img",{className:"description__image",src:null===(a=_.image)||void 0===a||null===(n=a.sizes)||void 0===n||null===(l=n.large)||void 0===l?void 0:l.url}),!r&&(0,t.createElement)("div",{className:"description__date"},(0,t.createElement)("span",{className:"date__day--numeric"},i(_.start,{day:"numeric"})),(0,t.createElement)("span",{className:"date__day--short"},i(_.start,{weekday:"short"})),(0,t.createElement)("span",{className:"date__day--long"},i(_.start,{weekday:"long"})),(0,t.createElement)("span",{className:"date__month--long"},i(_.start,{month:"long"})),(0,t.createElement)("span",{className:"date__month--numeric"},i(_.start,{month:"numeric"})),(0,t.createElement)("span",{className:"date__month--short"},i(_.start,{month:"short"}))),(0,t.createElement)("div",{className:"description__text"},(0,t.createElement)("div",{className:"description__title"},_.title),(0,t.createElement)("div",{class:"description__data"},s(_.start,_.end),(0,t.createElement)("br",null),(0,t.createElement)("div",{class:"description__subtitle"},u&&_.audience&&(0,t.createElement)("span",null,_.audience),"name"==g&&_.speaker&&(0,t.createElement)("span",null,_.speaker.name),o&&_.location&&(0,t.createElement)("span",{className:""},p)))))}var u=function(e){if(!document.event_block_data)return(0,t.createElement)(t.Fragment,null);const{columnsSmall:n,columnsMedium:c,columnsLarge:s,showImages:i,showCategory:u,showLocation:g,showBookedUp:_,bookedUpWarningThreshold:p,style:h,limit:v,order:E,selectedCategory:w,selectedLocation:f,selectedTags:k,scope:y,excerptLength:N,textAlignment:b,showAudience:x,showSpeaker:L,showCategoryFilter:C,showTagFilter:A,showSearch:S,filterPosition:F,excludeCurrent:I}=document.event_block_data[e.block],[B,j]=(0,a.useState)([]),[D,O]=(0,a.useState)({}),[T,z]=(0,a.useState)({}),[M,U]=(0,a.useState)({category:0,tags:[],string:""}),$=(e,t)=>{U({...M,[e]:t})},P=function(){var e;let t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";const a=null===(e=window.eventBlocksLocalization)||void 0===e?void 0:e.rest_url;if(void 0!==a)return""===t?a:a+(a.includes("?")?"&":"?")+t};(0,a.useEffect)((()=>{var e;const t=[v>0&&`limit=${v}`,"order="+E,0!=w&&`category=${w.join(",")}`,!!k.length&&`tag=${k.join(",")}`,""!=y&&`scope=${y}`,!!f&&`location=${f}`,!!I&&`exclude=${null===(e=window.eventBlocksLocalization)||void 0===e?void 0:e.current_id}`].filter(Boolean).join("&");l()({url:P(t)}).then((e=>{j(e);let t={},a={};e.map((e=>{if(e.category){null==t[e.category.id]&&(t[e.category.id]=e.category);for(let t in e.tags)null==a[t]&&(a[t]=e.tags[t])}})),z(a),O(t)}))}),[]);const R=["mini"==h?"description":"grid","mini"==h&&!i&&"description--dates","grid--gap-12",!!F&&"grid__column--span-3","xl:grid--columns-"+s,"md:grid--columns-"+c,"grid--columns-"+n].filter(Boolean).join(" ");return(0,t.createElement)("div",{className:"side"==F?"grid xl:grid--columns-4 grid--gap-12":""},(0,t.createElement)("aside",{className:"filters"},S&&(0,t.createElement)("div",{class:"filter__search"},(0,t.createElement)("div",{class:"input"},(0,t.createElement)("label",null,(0,r.__)("Search","events")),(0,t.createElement)("input",{type:"text",onChange:e=>{$("string",e.target.value)}}))),C&&(0,t.createElement)("div",{className:"filter"},(0,t.createElement)("span",{className:"filter__title"},(0,r.__)("Select category","events")),(0,t.createElement)("a",{class:"filter__pill "+(0==M.category?"filter__pill--active":""),onClick:()=>{$("category",0)}},(0,r.__)("All","events")),Object.keys(D).map(((e,a)=>(0,t.createElement)("a",{className:"filter__pill "+(M.category==parseInt(e)?"filter__pill--active":""),onClick:()=>{$("category",parseInt(e))}},D[e].name)))),A&&(0,t.createElement)("div",{className:"filter "+("side"==F?"filter--columns":"")},(0,t.createElement)("span",{className:"filter__title"},(0,r.__)("Select tags","events")),Object.keys(T).map(((e,a)=>(0,t.createElement)("div",{className:"filter__box checkbox"},(0,t.createElement)("label",null,(0,t.createElement)("input",{type:"checkbox",name:e,onClick:t=>{(e=>{let t=M.tags;if(t.includes(e))return t.splice(t.indexOf(e),1),void $("tags",t);t.push(e),$("tags",t)})(e)},checked:M.tags.includes(e)}),T[e].name)))))),(0,t.createElement)("div",{className:R},(()=>{let e=B;return 0==M.category&&""==M.string&&0==M.tags.length||(0!==M.category&&(e=e.filter((e=>{var t;return(null===(t=e.category)||void 0===t?void 0:t.id)==M.category}))),""!==M.string&&(e=e.filter((e=>e.title.toLowerCase().includes(M.string)))),M.tags.length>0&&(e=e.filter((e=>{let t=!1;for(let a of M.tags)a in e.tags&&(t=!0);return t})))),e})().map(((e,a)=>(0,t.createElement)(t.Fragment,null,"cards"==h&&(0,t.createElement)(o,{item:e,showImages:i,showCategory:u,showLocation:g,excerptLength:N,showBookedUp:_,bookedUpWarningThreshold:p,textAlignment:b,showAudience:x,showSpeaker:L}),"list"==h&&(0,t.createElement)(m,{item:e,showImages:i,showCategory:u,showLocation:g,excerptLength:N,textAlignment:b,showAudience:x,showSpeaker:L}),"mini"==h&&(0,t.createElement)(d,{item:e,showImages:i,showCategory:u,showLocation:g,excerptLength:N,textAlignment:b,showAudience:x,showSpeaker:L}))))))},g=window.ReactDOM,_=e.n(g);document.addEventListener("DOMContentLoaded",(()=>{const e=document.getElementsByClassName("events-upcoming-block");e&&Array.from(e).forEach((e=>{_().render((0,t.createElement)(u,{block:e.dataset.id}),e)}))}))}();