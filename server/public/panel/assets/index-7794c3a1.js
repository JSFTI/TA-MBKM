import{_ as ve}from"./ACurrencyInput.vue_vue_type_script_setup_true_lang-cebbc278.js";import{f as J,P as K,r as V,D as P,af as $,aw as ee,R as le,ax as _e,ay as N,o as u,c as m,b as r,a as t,w as p,A,x as o,y as w,ad as te,j as Ve,h as q,a0 as be,aA as he,a1 as O,a3 as xe,a4 as ye,Q as ke,v as Ce,Y as Se,Z as we,a5 as Me,a6 as Ue,aj as Te,X as $e,t as W,B as H,a9 as B,a8 as z,al as X,aa as ze}from"./vendor-d8963f8a.js";import{l as j}from"./lodash-89c1e3b6.js";import{u as F,a as je,F as Z,c as Pe,g as Re}from"./index-b15a03e3.js";import{d as G}from"./dayjs.min-f760afe9.js";import{u as De}from"./main-38cd24a7.js";const Be={class:"flex flex-col gap-2"},Fe={class:"flex flex-col md:flex-row gap-5"},Ie={class:"flex flex-col md:flex-row gap-5"},Ye={class:"flex flex-col md:flex-row gap-5"},Ae={class:"grid grid-cols-1 md:grid-cols-2"},He={class:"grid grid-cols-1 md:grid-cols-2"},Ne={class:"flex justify-center gap-10"},qe=r("div",{class:"i-mdi:filter text-2xl"},null,-1),Ee=r("div",{class:"i-mdi:filter-remove text-2xl"},null,-1),Le=J({__name:"ComplexProductFilter",props:{filters:null},emits:["update:filters"],setup(E,{emit:d}){const v=E,s=K(j.exports.cloneDeep(v.filters)),R=V([]),_=V([]),y=te(l=>{if(l.length===0){_.value=[];return}P.get("tags",{params:{search:l,limit:10}}).then(n=>{_.value=n.data.filter(g=>{var h;return!((h=s.tags)!=null&&h.includes(g.name))})})},200);function M(){Object.assign(s,{categories:[],tags:[],price:void 0,stock:void 0,published:null}),d("update:filters",s)}function U(){d("update:filters",s)}P.get("categories").then(l=>{R.value=l.data});const k=$({get(){var l;return((l=s.price)==null?void 0:l.gte)??null},set(l){s.price={...s.price,gte:l??void 0}}}),b=$({get(){var l;return((l=s.price)==null?void 0:l.lte)??null},set(l){s.price={...s.price,lte:l??void 0}}}),C=$({get(){var l;return typeof s.stock=="string"?null:((l=s.stock)==null?void 0:l.gte)??null},set(l){l!=null&&(s.stock={...typeof s.stock=="object"?s.stock:{},gte:l??void 0})}}),T=$({get(){var l;return typeof s.stock=="string"?null:((l=s.stock)==null?void 0:l.lte)??null},set(l){l!=null&&(s.stock={...typeof s.stock=="object"?s.stock:{},lte:l??void 0})}}),D=$(()=>{var n,g;const{filters:l}=v;return((n=l.categories)==null?void 0:n.length)||((g=l.tags)==null?void 0:g.length)||typeof l.stock!="object"&&l.price!=null||!j.exports.isEmpty(l.price)||typeof l.stock!="object"&&l.stock!=null||!j.exports.isEmpty(l.stock)||l.published!==null});return(l,n)=>{const g=ee,h=le,I=Ve,Y=_e,S=ve,e=N,i=q;return u(),m("div",Be,[r("div",Fe,[t(h,{modelValue:o(s).categories,"onUpdate:modelValue":n[0]||(n[0]=a=>o(s).categories=a),class:"w-full md:w-1/2",multiple:"",items:o(R),"closable-chips":"","item-title":"name","item-value":"name",label:"Category",chips:"",clearable:"","hide-details":""},{chip:p(({props:a,item:x})=>[t(g,A(a,{text:x.value}),null,16,["text"])]),_:1},8,["modelValue","items"]),t(Y,{modelValue:o(s).tags,"onUpdate:modelValue":n[1]||(n[1]=a=>o(s).tags=a),class:"w-full md:w-1/2",items:o(_),"closable-chips":"",multiple:"","item-title":"name","item-value":"name",clearable:"",label:"Tags",chips:"","hide-details":"","onUpdate:search":o(y)},{chip:p(({props:a,item:x})=>[t(g,A(a,{text:x.value}),null,16,["text"])]),item:p(({props:a,item:x})=>[t(I,A(a,{title:x.value}),null,16,["title"])]),_:1},8,["modelValue","items","onUpdate:search"])]),r("div",Ie,[t(S,{modelValue:o(k),"onUpdate:modelValue":n[2]||(n[2]=a=>w(k)?k.value=a:null),label:"Min. Price","hide-details":""},null,8,["modelValue"]),t(S,{modelValue:o(b),"onUpdate:modelValue":n[3]||(n[3]=a=>w(b)?b.value=a:null),label:"Max. Price","hide-details":""},null,8,["modelValue"])]),r("div",Ye,[t(S,{modelValue:o(C),"onUpdate:modelValue":n[4]||(n[4]=a=>w(C)?C.value=a:null),disabled:o(s).stock!=null&&["number","string"].includes(typeof o(s).stock),label:"Min. Stock","hide-details":""},null,8,["modelValue","disabled"]),t(S,{modelValue:o(T),"onUpdate:modelValue":n[5]||(n[5]=a=>w(T)?T.value=a:null),disabled:o(s).stock!=null&&["number","string"].includes(typeof o(s).stock),label:"Max. Stock","hide-details":""},null,8,["modelValue","disabled"])]),r("div",Ae,[t(e,{modelValue:o(s).stock,"onUpdate:modelValue":n[6]||(n[6]=a=>o(s).stock=a),box:"","true-value":0,"false-value":{},label:"Stock empty","hide-details":""},null,8,["modelValue"]),t(e,{modelValue:o(s).stock,"onUpdate:modelValue":n[7]||(n[7]=a=>o(s).stock=a),box:"","true-value":"infinite","false-value":{},label:"Stock infinite","hide-details":""},null,8,["modelValue"])]),r("div",He,[t(e,{modelValue:o(s).published,"onUpdate:modelValue":n[8]||(n[8]=a=>o(s).published=a),box:"","true-value":1,"false-value":null,label:"Show published products","hide-details":""},null,8,["modelValue"]),t(e,{modelValue:o(s).published,"onUpdate:modelValue":n[9]||(n[9]=a=>o(s).published=a),box:"","true-value":"0","false-value":null,label:"Show unpublished products","hide-details":""},null,8,["modelValue"])]),r("div",Ne,[t(i,{color:"primary",size:"large",disabled:o(j.exports.isEqual)(v.filters,o(s)),onClick:n[10]||(n[10]=a=>U())},{default:p(()=>[qe]),_:1},8,["disabled"]),t(i,{color:"danger",size:"large",disabled:!o(D),onClick:n[11]||(n[11]=a=>M())},{default:p(()=>[Ee]),_:1},8,["disabled"])])])}}}),Qe={class:"flex flex-col md:items-center md:flex-row md:px-4 gap-5 mb-5"},Oe={class:"flex gap-5 items-center"},We={class:"w-30"},Xe={class:"flex gap-5 ml-auto"},Ze={class:"w-100"},Ge={key:0,class:"mb-5"},Je={class:"relative rounded overflow-x-auto w-full"},Ke=["colSpan","onClick"],el={class:"flex items-center gap-4"},ll={key:1},tl=["colspan"],ol={key:1},sl=["colspan"],al={class:"mt-5"},ml=J({__name:"index",setup(E){const d=Pe(),v=be(),s=De(),R=V([]),_=V(!1),y=V(!0),M=V(!1),U=V(null),k=F("page","1"),b=F("limit","10"),C=F("search",null),T=F("sortBy",null),D=K(j.exports.pick(v.currentRoute.value.query,["categories","tags","price","stock"])),l=V([]),n=[d.display({id:"action",maxSize:40,header:"",cell:e=>t("div",{class:"flex justify-center"},[t(q,{to:`/products/${e.row.original.id}`,icon:"i-mdi:magnify"},null)])}),d.display({id:"published",maxSize:50,header:"Published",cell:e=>{var i;return t("div",{class:"flex justify-center"},[((i=s.role)==null?void 0:i.name)==="admin"?t(N,{"hide-details":!0,inline:!0,class:"place-items-center",onchange:a=>I(e.row.original,!!a.target.checked),"true-value":!0,"model-value":e.row.original.published},null):t("div",{class:e.row.original.published?"i-mdi:check":""},null)])}}),d.accessor("thumbnail",{header:"Thumbnail",id:"thumbnail",minSize:100,cell:e=>{var a;return e.getValue()?t(he,{cover:!0,src:(a=e.getValue())==null?void 0:a.url},null):"No thumbnail set"}}),d.accessor("name",{header:"Name",id:"name",minSize:400,cell:e=>e.renderValue()}),d.accessor("category",{header:"Category",id:"category",minSize:100,cell:e=>{var i;return(i=e.getValue())==null?void 0:i.name}}),d.accessor("price",{header:"Price",id:"price",minSize:100,enableSorting:!0,cell:e=>new Intl.NumberFormat("id-ID",{style:"currency",currency:"IDR"}).format(e.getValue())}),d.accessor("stock",{header:"Stock",id:"stock",minSize:25,enableSorting:!0,cell:e=>e.getValue()??"-"}),d.accessor("tags",{header:"Tags",id:"tags",minSize:500,cell:e=>t("div",{class:"flex gap-2 flex-wrap"},[(e.getValue()??[]).map(i=>t(ee,{text:i.name},null))])}),d.accessor("created_at",{id:"created_at",header:"Created At",minSize:200,cell:e=>{const i=G(e.getValue());return t("div",null,[t("div",null,[i.format("DD MMMM YYYY")]),t("div",null,[i.format("HH:MM:ss")])])}}),d.accessor("updated_at",{id:"updated_at",header:"Updated At",minSize:200,cell:e=>{if(!e.getValue())return"-";const i=G(e.getValue());return t("div",null,[t("div",null,[i.format("DD MMMM YYYY")]),t("div",null,[i.format("HH:MM:ss")])])}})],g=je({columns:n,get data(){var e;return((e=U.value)==null?void 0:e.data)??[]},state:{get sorting(){return l.value}},onSortingChange:e=>{l.value=typeof e=="function"?e(l.value):e},defaultColumn:{enableSorting:!1},getCoreRowModel:Re()});function h(){y.value=!0,M.value=!1,P.get("products",{params:v.currentRoute.value.query}).then(e=>{U.value=e.data}).catch(()=>{M.value=!0}).finally(()=>{y.value=!1})}function I(e,i){P[i?"put":"delete"](`products/${e.id}/published`).then(()=>{e.published=i})}function Y(e){Object.assign(D,e),v.replace({query:{...v.currentRoute.value.query,...e}})}const S=te(e=>{C.value=e||null},200);return O(()=>v.currentRoute.value.query,()=>{h()},{deep:!0}),O(()=>l.value,()=>{let e="";for(const i of l.value)e+=`${(i.desc?"-":"+")+i.id},`;T.value=e.slice(0,-1)||null}),h(),P.get("categories").then(e=>{R.value=e.data}),(e,i)=>{const a=q,x=xe,oe=ye,se=le,ae=ke,ne=N,ie=Le,re=Ce("CollapseTransition"),ue=Se,de=we,ce=Me,me=Ue,pe=Te,ge=$e;return u(),W(ge,null,{default:p(()=>[t(oe,{title:"Products",color:"rgba(0,0,0,0)"},{default:p(()=>[t(x,null,{default:p(()=>[t(a,{icon:"i-ic:round-add",class:"!bg-success !text-white",to:"/products/new"})]),_:1})]),_:1}),t(pe,null,{default:p(()=>{var L;return[r("div",Qe,[r("div",Oe,[r("div",We,[t(se,{modelValue:o(b),"onUpdate:modelValue":i[0]||(i[0]=f=>w(b)?b.value=f:null),class:"w-30",items:[10,25,50,100],label:"Limit","hide-details":""},null,8,["modelValue"])])]),r("div",Xe,[r("div",Ze,[t(ae,{"model-value":o(C),label:"Search","hide-details":"","onUpdate:modelValue":o(S)},null,8,["model-value","onUpdate:modelValue"])]),r("div",null,[t(ne,{modelValue:o(_),"onUpdate:modelValue":i[1]||(i[1]=f=>w(_)?_.value=f:null),"true-icon":"i-mdi:filter-cog","false-icon":"i-mdi:filter-cog-outline","hide-details":""},null,8,["modelValue"])])])]),r("div",null,[t(re,null,{default:p(()=>[o(_)?(u(),m("div",Ge,[t(ie,{filters:o(D),"onUpdate:filters":Y},null,8,["filters"])])):H("",!0)]),_:1}),r("div",Je,[t(de,{"model-value":o(y),persistent:"",contained:"",class:"items-center justify-center"},{default:p(()=>[t(ue,{size:"64",color:"primary",indeterminate:""})]),_:1},8,["model-value"]),t(ce,null,{default:p(()=>[r("thead",null,[(u(!0),m(z,null,B(o(g).getHeaderGroups(),f=>(u(),m("tr",{key:f.id},[(u(!0),m(z,null,B(f.headers,c=>(u(),m("th",{key:c.id,colSpan:c.colSpan,class:X({"cursor-pointer select-none":c.column.getCanSort()}),style:ze({minWidth:`${c.column.getSize()}px`}),onClick:fe=>{var Q;return(Q=c.column.getToggleSortingHandler())==null?void 0:Q(fe)}},[r("div",el,[c.isPlaceholder?H("",!0):(u(),W(o(Z),{key:0,render:c.column.columnDef.header,props:c.getContext()},null,8,["render","props"])),r("div",{class:X({asc:"i-mdi:chevron-up",desc:"i-mdi:chevron-down"}[c.column.getIsSorted()])},null,2)])],14,Ke))),128))]))),128))]),r("tbody",null,[o(M)?(u(),m("tr",ol,[r("td",{colspan:n.length,class:"text-center font-bold !text-2xl"}," Server Error ",8,sl)])):(u(),m(z,{key:0},[o(g).getRowModel().rows.length>0?(u(!0),m(z,{key:0},B(o(g).getRowModel().rows,f=>(u(),m("tr",{key:f.id},[(u(!0),m(z,null,B(f.getVisibleCells(),c=>(u(),m("td",{key:c.id},[t(o(Z),{render:c.column.columnDef.cell,props:c.getContext()},null,8,["render","props"])]))),128))]))),128)):o(y)?H("",!0):(u(),m("tr",ll,[r("td",{colspan:n.length,class:"text-center font-bold !text-2xl"}," No Data Available ",8,tl)]))],64))])]),_:1})])]),r("div",al,[t(me,{"model-value":+o(k),length:(L=o(U))==null?void 0:L.last_page,rounded:"circle","onUpdate:modelValue":i[2]||(i[2]=f=>k.value=f.toString())},null,8,["model-value","length"])])]}),_:1})]),_:1})}}});export{ml as default};
//# sourceMappingURL=index-7794c3a1.js.map
