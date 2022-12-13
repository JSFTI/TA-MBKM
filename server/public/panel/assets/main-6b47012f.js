import{_ as S,o as v,c as U,a as t,b as h,V as R,u as F,d as j,e as G,f as H,r as x,g as Q,h as q,i as z,j as M,k as W,l as J,m as K,n as X,p as Y,q as Z,s as ee,t as k,w as s,v as te,x as i,y as A,z as oe,A as E,B as ne,C as ie,D as se,E as re,F as ae,G as ce,H as le,I as pe,J as P,K as de,L as ue,M as me,N as _e,O as fe}from"./vendor-d8963f8a.js";const ge="modulepreload",ve=function(o){return"/public/panel/"+o},D={},_=function(r,a,c){if(!a||a.length===0)return r();const f=document.getElementsByTagName("link");return Promise.all(a.map(e=>{if(e=ve(e),e in D)return;D[e]=!0;const l=e.endsWith(".css"),g=l?'[rel="stylesheet"]':"";if(!!c)for(let u=f.length-1;u>=0;u--){const m=f[u];if(m.href===e&&(!l||m.rel==="stylesheet"))return}else if(document.querySelector(`link[href="${e}"]${g}`))return;const n=document.createElement("link");if(n.rel=l?"stylesheet":ge,l||(n.as="script",n.crossOrigin=""),n.href=e,document.head.appendChild(n),l)return new Promise((u,m)=>{n.addEventListener("load",u),n.addEventListener("error",()=>m(new Error(`Unable to preload CSS for ${e}`)))})})).then(()=>r())},he={},Ve=h("h1",{class:"text-3xl text-themeable"}," Dashboard ",-1);function be(o,r){const a=R;return v(),U("div",null,[Ve,t(a,{class:"my-4"})])}const ke=S(he,[["render",be]]),ye=()=>_(()=>import("./staffs-6e310f93.js"),["assets/staffs-6e310f93.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css","assets/lodash-89c1e3b6.js","assets/ProfilePictureUpdater.vue_vue_type_style_index_0_lang-b71d05d6.js","assets/ProfilePictureUpdater-e98c3e38.css","assets/index-caac9ef2.js","assets/staffs-88307efe.css"]),we=()=>_(()=>import("./profile-48f43d9e.js"),["assets/profile-48f43d9e.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css","assets/ProfilePictureUpdater.vue_vue_type_style_index_0_lang-b71d05d6.js","assets/ProfilePictureUpdater-e98c3e38.css","assets/lodash-89c1e3b6.js"]),xe=()=>_(()=>import("./carousel-ab12f0b6.js"),["assets/carousel-ab12f0b6.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css","assets/lodash-89c1e3b6.js","assets/dayjs.min-f760afe9.js"]),Ae=()=>_(()=>import("./new-8b3f1ab6.js"),["assets/new-8b3f1ab6.js","assets/ProductDetailForm.vue_vue_type_script_setup_true_lang-ef20bde5.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css","assets/ACurrencyInput.vue_vue_type_script_setup_true_lang-cebbc278.js","assets/lodash-89c1e3b6.js","assets/ProductDetailForm-628e642e.css"]),Ee=()=>_(()=>import("./index-31fe61e0.js"),["assets/index-31fe61e0.js","assets/ACurrencyInput.vue_vue_type_script_setup_true_lang-cebbc278.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css","assets/lodash-89c1e3b6.js","assets/index-caac9ef2.js","assets/dayjs.min-f760afe9.js"]),Pe=()=>_(()=>import("./_...all_-3af72031.js"),["assets/_...all_-3af72031.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css"]),De=()=>_(()=>import("./_productId_-c11142e5.js"),["assets/_productId_-c11142e5.js","assets/vendor-d8963f8a.js","assets/vendor-fe2e8907.css","assets/lodash-89c1e3b6.js","assets/ProductDetailForm.vue_vue_type_script_setup_true_lang-ef20bde5.js","assets/ACurrencyInput.vue_vue_type_script_setup_true_lang-cebbc278.js","assets/ProductDetailForm-628e642e.css"]),Le=[{name:"users-staffs",path:"/users/staffs",component:ye,props:!0},{name:"settings-profile",path:"/settings/profile",component:we,props:!0},{name:"settings-carousel",path:"/settings/carousel",component:xe,props:!0},{name:"products-new",path:"/products/new",component:Ae,props:!0},{name:"products",path:"/products",component:Ee,props:!0},{name:"index",path:"/",component:ke,props:!0},{name:"all",path:"/:all(.*)*",component:Pe,props:!0},{name:"products-productId",path:"/products/:productId",component:De,props:!0}],V=F(),Re=j(V),Be=G("user",{state:()=>window.user});function L(o){return o[0]==="/"&&(o=o.substring(1)),`https://arkastore.jasonsuryafaylim.my.id//${o}`}function Ge(o){return new Promise((r,a)=>{const c=new FileReader;c.onloadend=f=>{var l;const e=(l=f.target)==null?void 0:l.result;if(typeof e!="string"){a("Can't read file");return}r(e)},c.readAsDataURL(o)})}const Ce={class:"flex gap-3 mr-5"},Ie={class:"text-lg"},Te={class:"m-5"},Oe=H({__name:"App",setup(o){const r=x(!0),a=x([]),c=Be();return(f,e)=>{const l=Q,g=q,y=z,n=M,u=R,m=W,B=J,C=K,I=te("RouterView"),T=X,O=Y,$=Z,N=ee;return v(),k(N,null,{default:s(()=>[t($,{theme:i(V)?"dark":"light"},{default:s(()=>[t(O,null,{default:s(()=>[t(y,{title:"Store Admin Panel"},{prepend:s(()=>[t(l,{onClick:e[0]||(e[0]=p=>r.value=!i(r))})]),default:s(()=>[h("div",Ce,[t(g,{icon:i(V)?"i-ic:baseline-dark-mode":"i-ic:baseline-light-mode",onClick:e[1]||(e[1]=p=>i(Re)(!i(V)))},null,8,["icon"]),t(g,{href:i(L)("/logout"),icon:"i-ic:round-log-out"},null,8,["href"])])]),_:1}),t(C,{modelValue:i(r),"onUpdate:modelValue":e[3]||(e[3]=p=>A(r)?r.value=p:null),class:"pt-3",elevation:"10"},{default:s(()=>[t(B,{opened:i(a),"onUpdate:opened":e[2]||(e[2]=p=>A(a)?a.value=p:null)},{default:s(()=>{var p,w;return[(v(),k(n,{key:i(c).profile_picture??"nothing","prepend-avatar":i(L)(`/api/users/${i(c).id}/profile-picture`),subtitle:(p=i(c).role)==null?void 0:p.display_name,nav:""},{title:s(()=>[h("div",Ie,oe(i(c).name),1)]),_:1},8,["prepend-avatar","subtitle"])),t(u,{class:"my-5"}),t(n,{"prepend-icon":"i-ic:round-dashboard",to:"/",nav:"",title:"Dashboard"}),t(m,{value:"Products",fluid:""},{activator:s(({props:b})=>[t(n,E(b,{"prepend-icon":"i-mdi:package",nav:"",title:"Products"}),null,16)]),default:s(()=>[t(n,{nav:"",title:"All Products","prepend-icon":"i-mdi:package",to:"/products"}),t(n,{nav:"",title:"Add Product","prepend-icon":"i-ic:round-add",to:"/products/new"})]),_:1}),((w=i(c).role)==null?void 0:w.name)==="admin"?(v(),k(n,{key:0,"prepend-icon":"i-ic:baseline-people",to:"/users/staffs",nav:"",title:"Users"})):ne("",!0),t(m,{value:"Settings",fluid:""},{activator:s(({props:b})=>[t(n,E(b,{"prepend-icon":"i-ic:round-settings",nav:"",title:"Settings"}),null,16)]),default:s(()=>[t(n,{nav:"",title:"Carousel","prepend-icon":"i-mdi:view-carousel-outline",to:"/settings/carousel"}),t(n,{nav:"",title:"Profile","prepend-icon":"i-ic:baseline-person",to:"/settings/profile"})]),_:1})]}),_:1},8,["opened"])]),_:1},8,["modelValue"]),t(T,{tag:"main",scrollable:"",class:"bg-light dark:bg-black"},{default:s(()=>[h("div",Te,[t(I)])]),_:1})]),_:1})]),_:1},8,["theme"])]),_:1})}}}),$e={collapse:"i-mdi:chevron-up",complete:"i-mdi:check",cancel:"i-mdi:close-circle",close:"i-mdi:close",delete:"i-mdi:close-circle",clear:"i-mdi:close-circle",success:"i-mdi:check-circle",info:"i-mdi:information",warning:"i-mdi:alert-circle",error:"i-mdi:close-circle",prev:"i-mdi:chevron-left",next:"i-mdi:chevron-right",checkboxOn:"i-mdi:checkbox-marked",checkboxOff:"i-mdi:checkbox-blank-outline",checkboxIndeterminate:"i-mdi:minus-box",delimiter:"i-mdi:circle",sort:"i-mdi:arrow-up",expand:"i-mdi:chevron-down",menu:"i-mdi:menu",subgroup:"i-mdi:menu-down",dropdown:"i-mdi:menu-down",radioOn:"i-mdi:radiobox-marked",radioOff:"i-mdi:radiobox-blank",edit:"i-mdi:pencil",ratingEmpty:"i-mdi:star-outline",ratingFull:"i-mdi:star",ratingHalf:"i-mdi:star-half-full",loading:"i-mdi:cached",first:"i-mdi:page-first",last:"i-mdi:page-last",unfold:"i-mdi:unfold-more-horizontal",file:"i-mdi:paperclip",plus:"i-mdi:plus",minus:"i-mdi:minus"},Ne={component:o=>ie(o.tag,{class:o.icon,disabled:o.disabled})};se.defaults.baseURL="https://arkastore.jasonsuryafaylim.my.id//api";const Se=re({components:_e,directives:fe,icons:{defaultSet:"mdi",sets:{mdi:Ne},aliases:$e}}),Ue=ae(),d=ce(Oe),Fe=le({history:pe("/panel/"),routes:Le,parseQuery(o){return P.parse(o,{parseArrays:!0})},stringifyQuery(o){return P.stringify(o,{arrayFormat:"brackets",encode:!1})??""}});d.use(Fe);d.use(Se);d.use(Ue);d.use(de);d.component("Draggable",ue);d.component("CollapseTransition",me);d.mount("#app");d.config.globalProperties.window=window;export{Ge as f,Be as u};
//# sourceMappingURL=main-6b47012f.js.map
